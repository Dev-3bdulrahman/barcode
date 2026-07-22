<?php

namespace Dev3bdulrahman\Barcode\Http\Requests\Api;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class StoreBarcodeTemplateApiRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'name_ar' => 'nullable|string|max:255',
            'width_mm' => 'required|numeric|min:10|max:200',
            'height_mm' => 'required|numeric|min:10|max:300',
            'margin_top' => 'required|numeric|min:0|max:50',
            'margin_bottom' => 'required|numeric|min:0|max:50',
            'margin_left' => 'required|numeric|min:0|max:50',
            'margin_right' => 'required|numeric|min:0|max:50',
            'barcode_type' => 'required|in:code128,ean13,ean8,upca,qr',
            'show_price' => 'boolean',
            'show_name' => 'boolean',
            'show_sku' => 'boolean',
            'font_size' => 'integer|min:6|max:72',
            'label_fields' => 'nullable|array',
            'is_default' => 'boolean',
            'is_active' => 'boolean',
        ];
    }

    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(
            response()->json([
                'success' => false,
                'message' => __('barcode::barcode.validation_failed'),
                'data' => null,
                'meta' => [],
                'errors' => $validator->errors()->toArray(),
            ], 422)
        );
    }
}
