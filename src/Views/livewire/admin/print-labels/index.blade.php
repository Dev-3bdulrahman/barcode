<div class="p-6">
    <div class="flex flex-col md:flex-row md:items-center justify-between mb-8 gap-4">
        <div>
            <h2 class="text-2xl font-bold text-gray-900 dark:text-white">{{ __('barcode::barcode.print_labels') }}</h2>
            <p class="text-gray-500 text-sm mt-1">{{ __('barcode::barcode.manage_print') }}</p>
        </div>
    </div>

    @if (session('message'))
        <div class="mb-6 p-4 bg-green-50 dark:bg-green-950/30 border border-green-200 dark:border-green-800 rounded-lg text-green-700 dark:text-green-400 text-sm">
            {{ session('message') }}
        </div>
    @endif

    @if (session('error'))
        <div class="mb-6 p-4 bg-red-50 dark:bg-red-950/30 border border-red-200 dark:border-red-800 rounded-lg text-red-700 dark:text-red-400 text-sm">
            {{ session('error') }}
        </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <div class="lg:col-span-1 space-y-6">
            <div class="bg-white dark:bg-gray-900 rounded-xl shadow-sm border border-gray-100 dark:border-gray-800 p-6">
                <h3 class="text-sm font-bold text-gray-400 uppercase mb-4">{{ __('barcode::barcode.select_template') }}</h3>
                <select wire:model.live="selectedTemplateId"
                    class="w-full py-2.5 px-3 border border-gray-200 dark:border-gray-700 rounded-lg bg-gray-50 dark:bg-gray-800 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 dark:text-white">
                    <option value="">{{ __('barcode::barcode.select_template') }}</option>
                    @foreach($templates as $template)
                        <option value="{{ $template->id }}">{{ $template->name }}</option>
                    @endforeach
                </select>
                @error('selectedTemplateId') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
            </div>

            @if($selectedTemplate)
                <div class="bg-white dark:bg-gray-900 rounded-xl shadow-sm border border-gray-100 dark:border-gray-800 p-6">
                    <h3 class="text-sm font-bold text-gray-400 uppercase mb-4">{{ __('barcode::barcode.template') }}</h3>
                    <div class="space-y-2 text-sm">
                        <div class="flex justify-between">
                            <span class="text-gray-500">{{ __('barcode::barcode.label_width') }}:</span>
                            <span class="font-semibold text-gray-900 dark:text-white">{{ $selectedTemplate->width_mm }} mm</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-500">{{ __('barcode::barcode.label_height') }}:</span>
                            <span class="font-semibold text-gray-900 dark:text-white">{{ $selectedTemplate->height_mm }} mm</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-500">{{ __('barcode::barcode.barcode_type') }}:</span>
                            <span class="font-semibold text-gray-900 dark:text-white">{{ __("barcode::barcode.{$selectedTemplate->barcode_type}") }}</span>
                        </div>
                    </div>
                </div>
            @endif
        </div>

        <div class="lg:col-span-2 space-y-6">
            <div class="bg-white dark:bg-gray-900 rounded-xl shadow-sm border border-gray-100 dark:border-gray-800 p-6">
                <h3 class="text-sm font-bold text-gray-400 uppercase mb-4">{{ __('barcode::barcode.select_products') }}</h3>

                <div class="max-h-80 overflow-y-auto space-y-2">
                    @forelse($products as $product)
                        <label class="flex items-center gap-3 p-3 rounded-lg border border-gray-100 dark:border-gray-800 hover:bg-gray-50 dark:hover:bg-gray-800/50 cursor-pointer transition-colors">
                            <input type="checkbox" value="{{ $product->id }}"
                                wire:change="toggleProduct({{ $product->id }})"
                                {{ in_array($product->id, $selectedProductIds) ? 'checked' : '' }}
                                class="rounded border-gray-300 dark:border-gray-600 text-blue-600 focus:ring-blue-500">
                            <div class="flex-1 min-w-0">
                                <div class="font-medium text-gray-900 dark:text-white truncate">{{ $product->translated_name }}</div>
                                @if($product->sku)
                                    <div class="text-xs text-gray-400">{{ $product->sku }}</div>
                                @endif
                            </div>
                            @if($product->selling_price)
                                <div class="text-sm font-semibold text-gray-700 dark:text-gray-300">{{ number_format($product->selling_price, 2) }}</div>
                            @endif
                        </label>
                    @empty
                        <p class="text-gray-400 text-sm py-8 text-center">{{ __('barcode::barcode.no_templates') }}</p>
                    @endforelse
                </div>

                @error('selectedProductIds') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
            </div>

            @if(count($selectedProductIds) > 0)
                <div class="bg-white dark:bg-gray-900 rounded-xl shadow-sm border border-gray-100 dark:border-gray-800 p-6">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-sm font-bold text-gray-400 uppercase">{{ __('barcode::barcode.selected_products') }}</h3>
                        <span class="text-sm font-semibold text-blue-600 dark:text-blue-400">{{ count($selectedProductIds) }} {{ __('barcode::barcode.products') }}</span>
                    </div>
                    <div class="flex flex-wrap gap-4 items-end">
                        <div class="w-full sm:w-48">
                            <label class="block text-xs font-bold text-gray-400 mb-1.5 uppercase">{{ __('barcode::barcode.quantity_per_label') }}</label>
                            <input type="number" wire:model="quantityPerLabel" min="1" max="100"
                                class="w-full py-2.5 px-3 border border-gray-200 dark:border-gray-700 rounded-lg bg-gray-50 dark:bg-gray-800 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 dark:text-white">
                            @error('quantityPerLabel') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                        </div>
                        <button wire:click="generate" wire:loading.attr="disabled"
                            class="inline-flex items-center gap-2 px-6 py-2.5 bg-blue-600 hover:bg-blue-700 active:bg-blue-800 disabled:opacity-50 disabled:cursor-not-allowed text-white rounded-lg text-sm font-semibold transition-colors shadow-sm">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" />
                            </svg>
                            <span wire:loading.remove>{{ __('barcode::barcode.generate_pdf') }}</span>
                            <span wire:loading>{{ __('barcode::barcode.generating') }}</span>
                        </button>
                    </div>
                </div>
            @endif

            @if($generatedFile)
                <div class="bg-green-50 dark:bg-green-950/30 border border-green-200 dark:border-green-800 rounded-xl p-6">
                    <div class="flex items-center justify-between">
                        <div>
                            <h3 class="font-bold text-green-700 dark:text-green-400">{{ __('barcode::barcode.completed') }}</h3>
                            <p class="text-sm text-green-600 dark:text-green-500 mt-1">
                                {{ $totalLabels }} {{ __('barcode::barcode.total_labels') }}
                            </p>
                        </div>
                        <a href="{{ asset('storage/barcode/' . $generatedFile) }}" target="_blank"
                            class="inline-flex items-center gap-2 px-4 py-2.5 bg-green-600 hover:bg-green-700 active:bg-green-800 text-white rounded-lg text-sm font-semibold transition-colors shadow-sm">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                            </svg>
                            {{ __('barcode::barcode.download_pdf') }}
                        </a>
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>
