<!DOCTYPE html>
<html dir="{{ app()->getLocale() === 'ar' ? 'rtl' : 'ltr' }}">
<head>
    <meta charset="utf-8">
    <title>Barcode Labels</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            font-family: sans-serif;
            font-size: {{ $template->font_size }}px;
        }
        .label {
            width: {{ $template->width_mm }}mm;
            height: {{ $template->height_mm }}mm;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            text-align: center;
            padding: 2mm;
            page-break-inside: avoid;
            float: left;
        }
        .label .name { font-weight: bold; margin-bottom: 1mm; }
        .label .sku { color: #666; font-size: 0.9em; margin-bottom: 1mm; }
        .label .variant { color: #666; font-size: 0.9em; margin-bottom: 1mm; }
        .label .barcode-img { margin-bottom: 1mm; }
        .label .barcode-data { font-family: monospace; font-size: 0.85em; color: #333; }
        .label .price { font-weight: bold; margin-top: 1mm; }
    </style>
</head>
<body>
    @foreach($labels as $label)
        <div class="label">
            @if($template->show_name && !empty($label['product_name']))
                <div class="name">{{ $label['product_name'] }}</div>
            @endif
            @if($template->show_sku && !empty($label['product_sku']))
                <div class="sku">{{ $label['product_sku'] }}</div>
            @endif
            @if(!empty($label['variant_name']))
                <div class="variant">{{ $label['variant_name'] }}</div>
            @endif
            <div class="barcode-img">
                <img src="data:image/png;base64,{{ $label['barcode_image'] }}" alt="barcode" style="max-width: 100%;">
            </div>
            <div class="barcode-data">{{ $label['barcode_data'] }}</div>
            @if($template->show_price && isset($label['price']) && $label['price'] > 0)
                <div class="price">{{ number_format($label['price'], 2) }}</div>
            @endif
        </div>
    @endforeach
</body>
</html>
