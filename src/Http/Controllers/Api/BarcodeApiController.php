<?php

namespace Dev3bdulrahman\Barcode\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Traits\HasApiResponse;
use Dev3bdulrahman\Barcode\Http\Requests\Api\StoreBarcodeTemplateApiRequest;
use Dev3bdulrahman\Barcode\Http\Requests\Api\UpdateBarcodeTemplateApiRequest;
use Dev3bdulrahman\Barcode\Http\Resources\BarcodeTemplateResource;
use Dev3bdulrahman\Barcode\Models\BarcodeTemplate;
use Dev3bdulrahman\Barcode\Models\BarcodeJob;
use Dev3bdulrahman\Barcode\Services\BarcodeService;
use Dev3bdulrahman\Barcode\Services\LabelDesignerService;
use App\Models\Product;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class BarcodeApiController extends Controller
{
    use HasApiResponse;

    public function index(Request $request): JsonResponse
    {
        $this->authorize('viewAny', BarcodeTemplate::class);

        $templates = BarcodeTemplate::query()
            ->when($request->search, fn($q, $v) => $q->where('name', 'like', "%{$v}%"))
            ->orderBy('id', 'desc')
            ->paginate((int) $request->get('per_page', 10));

        return $this->success(
            BarcodeTemplateResource::collection($templates->items()),
            __('barcode::barcode.retrieved'),
            200,
            [
                'current_page' => $templates->currentPage(),
                'last_page' => $templates->lastPage(),
                'per_page' => $templates->perPage(),
                'total' => $templates->total(),
            ]
        );
    }

    public function store(StoreBarcodeTemplateApiRequest $request): JsonResponse
    {
        $this->authorize('create', BarcodeTemplate::class);

        $data = $request->validated();
        $data['company_id'] = auth()->user()->company_id;

        if (!empty($data['is_default'])) {
            BarcodeTemplate::where('company_id', auth()->user()->company_id)
                ->where('is_default', true)
                ->update(['is_default' => false]);
        }

        $template = BarcodeTemplate::create($data);

        return $this->success(
            new BarcodeTemplateResource($template),
            __('barcode::barcode.created'),
            201
        );
    }

    public function show(BarcodeTemplate $barcodeTemplate): JsonResponse
    {
        $this->authorize('view', $barcodeTemplate);

        return $this->success(
            new BarcodeTemplateResource($barcodeTemplate),
            __('barcode::barcode.retrieved')
        );
    }

    public function update(UpdateBarcodeTemplateApiRequest $request, BarcodeTemplate $barcodeTemplate): JsonResponse
    {
        $this->authorize('update', $barcodeTemplate);

        $data = $request->validated();

        if (!empty($data['is_default'])) {
            BarcodeTemplate::where('company_id', auth()->user()->company_id)
                ->where('is_default', true)
                ->where('id', '!=', $barcodeTemplate->id)
                ->update(['is_default' => false]);
        }

        $barcodeTemplate->update($data);

        return $this->success(
            new BarcodeTemplateResource($barcodeTemplate->fresh()),
            __('barcode::barcode.updated')
        );
    }

    public function destroy(BarcodeTemplate $barcodeTemplate): JsonResponse
    {
        $this->authorize('delete', $barcodeTemplate);

        $barcodeTemplate->delete();

        return $this->success(null, __('barcode::barcode.deleted'));
    }

    public function preview(Request $request, LabelDesignerService $service): JsonResponse
    {
        $request->validate([
            'template_id' => 'required|exists:barcode_templates,id',
            'product_id' => 'required|exists:products,id',
        ]);

        $template = BarcodeTemplate::findOrFail($request->template_id);
        $product = Product::findOrFail($request->product_id);

        $preview = $service->previewLabel($template, $product);

        return $this->success($preview, __('barcode::barcode.preview_ready'));
    }

    public function generateBarcode(Request $request, BarcodeService $service): JsonResponse
    {
        $request->validate([
            'data' => 'required|string',
            'type' => 'nullable|string|in:code128,ean13,ean8,upca,qr',
        ]);

        $image = $service->generateBarcode($request->data, $request->type ?? 'code128');

        return $this->success([
            'barcode' => 'data:image/png;base64,' . $image,
            'data' => $request->data,
            'type' => $request->type ?? 'code128',
        ], __('barcode::barcode.generated'));
    }

    public function generateQR(Request $request, BarcodeService $service): JsonResponse
    {
        $request->validate([
            'data' => 'required|string',
        ]);

        $image = $service->generateQRCode($request->data);

        return $this->success([
            'qr_code' => 'data:image/png;base64,' . $image,
            'data' => $request->data,
        ], __('barcode::barcode.generated'));
    }

    public function jobs(Request $request): JsonResponse
    {
        $this->authorize('viewAny', BarcodeJob::class);

        $jobs = BarcodeJob::with(['template', 'creator'])
            ->orderBy('id', 'desc')
            ->paginate((int) $request->get('per_page', 10));

        return $this->success($jobs->items(), __('barcode::barcode.retrieved'), 200, [
            'current_page' => $jobs->currentPage(),
            'last_page' => $jobs->lastPage(),
            'per_page' => $jobs->perPage(),
            'total' => $jobs->total(),
        ]);
    }
}
