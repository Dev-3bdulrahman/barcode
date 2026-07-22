<?php

namespace Dev3bdulrahman\Barcode\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class BarcodeTemplateResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'company_id' => $this->company_id,
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
            'label_fields' => $this->label_fields,
            'is_default' => $this->is_default,
            'is_active' => $this->is_active,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
