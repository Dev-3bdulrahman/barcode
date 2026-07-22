<?php

namespace Dev3bdulrahman\Barcode\Http\Controllers\Web\Admin\Templates;

use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Url;
use Dev3bdulrahman\Barcode\Models\BarcodeTemplate;
use Dev3bdulrahman\Barcode\Services\LabelDesignerService;
use App\Models\Product;

class Index extends Component
{
    use WithPagination;

    #[Url(as: 'q')]
    public string $search = '';

    public ?int $templateId = null;
    public string $name = '';
    public string $name_ar = '';
    public string $width_mm = '50.00';
    public string $height_mm = '30.00';
    public string $margin_top = '2.00';
    public string $margin_bottom = '2.00';
    public string $margin_left = '2.00';
    public string $margin_right = '2.00';
    public string $barcode_type = 'code128';
    public bool $show_price = true;
    public bool $show_name = true;
    public bool $show_sku = true;
    public int $font_size = 10;
    public string $label_fields = '';
    public bool $is_default = false;
    public bool $is_active = true;

    public bool $showFormModal = false;
    public bool $showPreviewModal = false;

    public ?int $previewProductId = null;
    public array $previewData = [];

    protected $listeners = ['delete' => 'deleteTemplate'];

    #[Layout('layouts.admin')]
    public function mount(): void
    {
        //
    }

    public function updatingSearch(): void
    {
        $this->resetPage();
    }

    public function openCreateModal(): void
    {
        $this->resetForm();
        $this->showFormModal = true;
    }

    public function openEditModal(int $id): void
    {
        $template = BarcodeTemplate::findOrFail($id);
        $this->templateId = $template->id;
        $this->name = $template->name;
        $this->name_ar = $template->name_ar ?? '';
        $this->width_mm = (string) $template->width_mm;
        $this->height_mm = (string) $template->height_mm;
        $this->margin_top = (string) $template->margin_top;
        $this->margin_bottom = (string) $template->margin_bottom;
        $this->margin_left = (string) $template->margin_left;
        $this->margin_right = (string) $template->margin_right;
        $this->barcode_type = $template->barcode_type;
        $this->show_price = $template->show_price;
        $this->show_name = $template->show_name;
        $this->show_sku = $template->show_sku;
        $this->font_size = $template->font_size;
        $this->label_fields = $template->label_fields ? json_encode($template->label_fields) : '';
        $this->is_default = $template->is_default;
        $this->is_active = $template->is_active;
        $this->showFormModal = true;
    }

    public function save(): void
    {
        $this->validate([
            'name' => 'required|string|max:255',
            'width_mm' => 'required|numeric|min:10|max:200',
            'height_mm' => 'required|numeric|min:10|max:300',
            'margin_top' => 'required|numeric|min:0|max:50',
            'margin_bottom' => 'required|numeric|min:0|max:50',
            'margin_left' => 'required|numeric|min:0|max:50',
            'margin_right' => 'required|numeric|min:0|max:50',
            'barcode_type' => 'required|in:code128,ean13,ean8,upca,qr',
            'font_size' => 'required|integer|min:6|max:72',
        ]);

        $data = [
            'company_id' => auth()->user()->company_id,
            'name' => $this->name,
            'name_ar' => $this->name_ar,
            'width_mm' => $this->width_mm,
            'height_mm' => $this->height_mm,
            'margin_top' => $this->margin_top,
            'margin_bottom' => $this->margin_bottom,
            'margin_left' => $this->margin_left,
            'margin_right' => $this->margin_right,
            'barcode_type' => $this->barcode_type,
            'show_price' => $this->show_price,
            'show_name' => $this->show_name,
            'show_sku' => $this->show_sku,
            'font_size' => $this->font_size,
            'label_fields' => $this->label_fields ? json_decode($this->label_fields, true) : null,
            'is_default' => $this->is_default,
            'is_active' => $this->is_active,
        ];

        if ($this->is_default) {
            BarcodeTemplate::where('company_id', auth()->user()->company_id)
                ->where('is_default', true)
                ->update(['is_default' => false]);
        }

        if ($this->templateId) {
            $template = BarcodeTemplate::findOrFail($this->templateId);
            $template->update($data);
        } else {
            BarcodeTemplate::create($data);
        }

        $this->resetForm();
        $this->showFormModal = false;
        session()->flash('message', __('barcode::barcode.saved'));
    }

    public function openPreview(int $id): void
    {
        $template = BarcodeTemplate::findOrFail($id);
        $product = Product::where('company_id', auth()->user()->company_id)->first();

        if (!$product) {
            session()->flash('error', __('barcode::barcode.no_product_preview'));
            return;
        }

        $service = app(LabelDesignerService::class);
        $this->previewData = $service->previewLabel($template, $product);
        $this->previewProductId = $id;
        $this->showPreviewModal = true;
    }

    public function deleteTemplate(int $id): void
    {
        $template = BarcodeTemplate::findOrFail($id);
        $template->delete();
        session()->flash('message', __('barcode::barcode.deleted'));
    }

    public function resetForm(): void
    {
        $this->templateId = null;
        $this->name = '';
        $this->name_ar = '';
        $this->width_mm = '50.00';
        $this->height_mm = '30.00';
        $this->margin_top = '2.00';
        $this->margin_bottom = '2.00';
        $this->margin_left = '2.00';
        $this->margin_right = '2.00';
        $this->barcode_type = 'code128';
        $this->show_price = true;
        $this->show_name = true;
        $this->show_sku = true;
        $this->font_size = 10;
        $this->label_fields = '';
        $this->is_default = false;
        $this->is_active = true;
    }

    public function render()
    {
        $query = BarcodeTemplate::query();

        if (!empty($this->search)) {
            $query->where(function ($q) {
                $q->where('name', 'like', "%{$this->search}%")
                  ->orWhere('name_ar', 'like', "%{$this->search}%");
            });
        }

        $templates = $query->orderBy('id', 'desc')->paginate(10);

        return view('barcode::livewire.admin.templates.index', [
            'templates' => $templates,
        ]);
    }
}
