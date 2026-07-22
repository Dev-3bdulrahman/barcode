<div class="text-center" style="font-size: {{ $template->font_size }}px;">
    @if($template->show_name && !empty($label['product_name']))
        <div class="font-semibold text-gray-900 mb-1 truncate">{{ $label['product_name'] }}</div>
    @endif
    @if($template->show_sku && !empty($label['product_sku']))
        <div class="text-gray-500 text-xs mb-1">{{ $label['product_sku'] }}</div>
    @endif
    @if(!empty($label['variant_name']))
        <div class="text-gray-500 text-xs mb-1">{{ $label['variant_name'] }}</div>
    @endif
    <div class="mb-1">
        <img src="data:image/png;base64,{{ $label['barcode_image'] }}" alt="barcode" class="inline-block" style="max-width: 100%;">
    </div>
    <div class="text-xs text-gray-600 font-mono">{{ $label['barcode_data'] }}</div>
    @if($template->show_price && isset($label['price']) && $label['price'] > 0)
        <div class="font-bold text-gray-900 mt-1">{{ number_format($label['price'], 2) }}</div>
    @endif
</div>
