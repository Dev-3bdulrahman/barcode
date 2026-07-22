<?php

namespace Dev3bdulrahman\Barcode\Services;

use Picqer\Barcode\BarcodeGeneratorPNG;
use Picqer\Barcode\BarcodeGeneratorSVG;
use Picqer\Barcode\BarcodeGeneratorJPG;
use Dev3bdulrahman\Barcode\Models\BarcodeTemplate;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Models\Product;

class BarcodeService
{
    public function generateBarcode(string $data, string $type = 'code128'): string
    {
        $generator = new BarcodeGeneratorPNG();

        $typeMap = [
            'code128' => $generator::TYPE_CODE_128,
            'ean13' => $generator::TYPE_EAN_13,
            'ean8' => $generator::TYPE_EAN_8,
            'upca' => $generator::TYPE_UPC_A,
            'qr' => $generator::TYPE_QR_CODE,
        ];

        $barcodeType = $typeMap[$type] ?? $generator::TYPE_CODE_128;

        return base64_encode($generator->getBarcode($data, $barcodeType, 2, 60));
    }

    public function generateBarcodeSvg(string $data, string $type = 'code128'): string
    {
        $generator = new BarcodeGeneratorSVG();

        $typeMap = [
            'code128' => $generator::TYPE_CODE_128,
            'ean13' => $generator::TYPE_EAN_13,
            'ean8' => $generator::TYPE_EAN_8,
            'upca' => $generator::TYPE_UPC_A,
            'qr' => $generator::TYPE_QR_CODE,
        ];

        $barcodeType = $typeMap[$type] ?? $generator::TYPE_CODE_128;

        return $generator->getBarcode($data, $barcodeType, 2, 60);
    }

    public function generateLabel(BarcodeTemplate $template, Product $product, $variant = null): array
    {
        $barcodeData = $product->barcode ?? $product->sku ?? (string) $product->id;
        $barcodeImage = $this->generateBarcode($barcodeData, $template->barcode_type);

        return [
            'barcode_image' => $barcodeImage,
            'barcode_data' => $barcodeData,
            'product_name' => $product->name,
            'product_sku' => $product->sku,
            'price' => $product->selling_price ?? $product->price ?? 0,
            'variant_name' => $variant ? $variant->name ?? null : null,
            'template' => $template,
        ];
    }

    public function generatePdfLabels(BarcodeTemplate $template, array $products): string
    {
        $labels = [];
        foreach ($products as $productData) {
            $product = $productData['product'];
            $variant = $productData['variant'] ?? null;
            $quantity = $productData['quantity'] ?? 1;

            for ($i = 0; $i < $quantity; $i++) {
                $labels[] = $this->generateLabel($template, $product, $variant);
            }
        }

        $pdf = Pdf::loadView('barcode::pdf.labels', [
            'labels' => $labels,
            'template' => $template,
        ]);

        $pdf->setPaper([0, 0, $template->width_mm * 2.83465, $template->height_mm * 2.83465 * count($labels)]);

        $filename = 'barcode_labels_' . time() . '.pdf';
        $path = storage_path('app/public/barcode/' . $filename);

        if (!is_dir(storage_path('app/public/barcode'))) {
            mkdir(storage_path('app/public/barcode'), 0755, true);
        }

        $pdf->save($path);

        return $filename;
    }

    public function generateQRCode(string $data): string
    {
        return $this->generateBarcode($data, 'qr');
    }
}
