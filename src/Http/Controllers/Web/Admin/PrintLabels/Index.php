<?php

namespace Dev3bdulrahman\Barcode\Http\Controllers\Web\Admin\PrintLabels;

use Livewire\Component;
use Livewire\Attributes\Layout;
use Dev3bdulrahman\Barcode\Models\BarcodeTemplate;
use Dev3bdulrahman\Barcode\Models\BarcodeJob;
use Dev3bdulrahman\Barcode\Services\BarcodeService;
use App\Models\Product;

class Index extends Component
{
    public ?int $selectedTemplateId = null;
    public array $selectedProductIds = [];
    public int $quantityPerLabel = 1;
    public bool $generatingPdf = false;
    public ?string $generatedFile = null;
    public ?int $totalLabels = null;

    protected $listeners = ['generatePdf' => 'generate'];

    #[Layout('layouts.admin')]
    public function mount(): void
    {
        //
    }

    public function updatedSelectedTemplateId(): void
    {
        $this->generatedFile = null;
    }

    public function toggleProduct(int $productId): void
    {
        if (in_array($productId, $this->selectedProductIds)) {
            $this->selectedProductIds = array_values(array_diff($this->selectedProductIds, [$productId]));
        } else {
            $this->selectedProductIds[] = $productId;
        }
        $this->generatedFile = null;
    }

    public function generate(): void
    {
        $this->validate([
            'selectedTemplateId' => 'required|exists:barcode_templates,id',
            'selectedProductIds' => 'required|array|min:1',
            'selectedProductIds.*' => 'exists:products,id',
            'quantityPerLabel' => 'required|integer|min:1|max:100',
        ]);

        $this->generatingPdf = true;

        $template = BarcodeTemplate::findOrFail($this->selectedTemplateId);
        $products = Product::whereIn('id', $this->selectedProductIds)->get();

        $productData = $products->map(function ($product) {
            return [
                'product' => $product,
                'variant' => null,
                'quantity' => $this->quantityPerLabel,
            ];
        })->toArray();

        $service = app(BarcodeService::class);
        $filename = $service->generatePdfLabels($template, $productData);

        $this->totalLabels = count($productData) * $this->quantityPerLabel;
        $this->generatedFile = $filename;
        $this->generatingPdf = false;

        BarcodeJob::create([
            'company_id' => auth()->user()->company_id,
            'template_id' => $template->id,
            'product_ids' => $this->selectedProductIds,
            'variant_ids' => [],
            'quantity_per_label' => $this->quantityPerLabel,
            'total_labels' => $this->totalLabels,
            'status' => 'completed',
            'file_path' => $filename,
            'created_by' => auth()->id(),
        ]);

        $this->dispatch('pdf-generated');
    }

    public function render()
    {
        $templates = BarcodeTemplate::where('is_active', true)->get();
        $products = Product::where('company_id', auth()->user()->company_id)
            ->where('is_active', true)
            ->get();
        $selectedTemplate = $this->selectedTemplateId ? BarcodeTemplate::find($this->selectedTemplateId) : null;

        return view('barcode::livewire.admin.print-labels.index', [
            'templates' => $templates,
            'products' => $products,
            'selectedTemplate' => $selectedTemplate,
        ]);
    }
}
