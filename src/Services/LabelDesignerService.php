<?php

namespace Dev3bdulrahman\Barcode\Services;

use Dev3bdulrahman\Barcode\Models\BarcodeTemplate;
use App\Models\Product;

class LabelDesignerService
{
    public function previewLabel(BarcodeTemplate $template, Product $product): array
    {
        $barcodeService = app(BarcodeService::class);
        $label = $barcodeService->generateLabel($template, $product);

        $html = view('barcode::livewire.admin.templates._label-preview', [
            'label' => $label,
            'template' => $template,
        ])->render();

        return [
            'html' => $html,
            'width_mm' => $template->width_mm,
            'height_mm' => $template->height_mm,
            'barcode_image' => $label['barcode_image'],
        ];
    }

    public function calculateLabelPosition(int $index, BarcodeTemplate $template): array
    {
        $labelsPerRow = max(1, (int) (210 / $template->width_mm));
        $labelsPerColumn = max(1, (int) (297 / $template->height_mm));

        $row = (int) ($index / $labelsPerRow);
        $col = $index % $labelsPerRow;

        $x = $template->margin_left + ($col * ($template->width_mm + $template->margin_left + $template->margin_right));
        $y = $template->margin_top + ($row * ($template->height_mm + $template->margin_top + $template->margin_bottom));

        return [
            'row' => $row,
            'col' => $col,
            'x_mm' => $x,
            'y_mm' => $y,
            'labels_per_row' => $labelsPerRow,
            'labels_per_column' => $labelsPerColumn,
            'total_per_page' => $labelsPerRow * $labelsPerColumn,
        ];
    }
}
