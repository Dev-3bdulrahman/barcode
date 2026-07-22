<div class="p-6">
    <div class="flex flex-col md:flex-row md:items-center justify-between mb-8 gap-4">
        <div>
            <h2 class="text-2xl font-bold text-gray-900 dark:text-white">{{ __('barcode::barcode.templates') }}</h2>
            <p class="text-gray-500 text-sm mt-1">{{ __('barcode::barcode.manage_templates') }}</p>
        </div>
        <button wire:click="openCreateModal"
            class="inline-flex items-center gap-2 px-4 py-2.5 bg-blue-600 hover:bg-blue-700 active:bg-blue-800 text-white rounded-lg text-sm font-semibold transition-colors shadow-sm">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" />
            </svg>
            <span>{{ __('barcode::barcode.add_template') }}</span>
        </button>
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

    <div class="bg-white dark:bg-gray-900 rounded-xl border border-gray-100 dark:border-gray-800 p-4 mb-6 shadow-sm">
        <div class="flex flex-wrap items-end gap-4">
            <div class="flex-1 min-w-[200px]">
                <label class="block text-xs font-bold text-gray-400 mb-1.5 uppercase">{{ __('barcode::barcode.search') }}</label>
                <div class="relative">
                    <input type="text" wire:model.live.debounce.300ms="search" placeholder="{{ __('barcode::barcode.search_placeholder') }}"
                        class="w-full pr-3 pl-10 py-2 border border-gray-200 dark:border-gray-700 rounded-lg bg-gray-50 dark:bg-gray-800 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent dark:text-white">
                    <span class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none text-gray-400">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                        </svg>
                    </span>
                </div>
            </div>
        </div>
    </div>

    <div class="bg-white dark:bg-gray-900 rounded-xl shadow-sm border border-gray-100 dark:border-gray-800 overflow-hidden mb-6">
        <div class="overflow-x-auto">
            <table class="w-full text-right border-collapse">
                <thead>
                    <tr class="bg-gray-50 dark:bg-gray-800/50 border-b border-gray-100 dark:border-gray-800">
                        <th class="px-6 py-4 text-xs font-bold text-gray-400 uppercase">{{ __('barcode::barcode.name') }}</th>
                        <th class="px-6 py-4 text-xs font-bold text-gray-400 uppercase">{{ __('barcode::barcode.barcode_type') }}</th>
                        <th class="px-6 py-4 text-xs font-bold text-gray-400 uppercase">{{ __('barcode::barcode.label_width') }}</th>
                        <th class="px-6 py-4 text-xs font-bold text-gray-400 uppercase">{{ __('barcode::barcode.label_height') }}</th>
                        <th class="px-6 py-4 text-xs font-bold text-gray-400 uppercase">{{ __('barcode::barcode.is_default') }}</th>
                        <th class="px-6 py-4 text-xs font-bold text-gray-400 uppercase">{{ __('barcode::barcode.is_active') }}</th>
                        <th class="px-6 py-4 text-xs font-bold text-gray-400 uppercase text-center">{{ __('barcode::barcode.actions') }}</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50 dark:divide-gray-800">
                    @forelse($templates as $template)
                        <tr class="hover:bg-gray-50/50 dark:hover:bg-gray-800/30 transition-colors">
                            <td class="px-6 py-4 font-semibold text-gray-900 dark:text-white">
                                {{ $template->name }}
                                @if($template->name_ar)
                                    <span class="text-xs text-gray-400 block">{{ $template->name_ar }}</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-sm">
                                <span class="px-2.5 py-1 rounded-full text-xs font-medium bg-blue-50 text-blue-700 dark:bg-blue-950/30 dark:text-blue-400">
                                    {{ __("barcode::barcode.{$template->barcode_type}") }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-600 dark:text-gray-300">{{ $template->width_mm }} mm</td>
                            <td class="px-6 py-4 text-sm text-gray-600 dark:text-gray-300">{{ $template->height_mm }} mm</td>
                            <td class="px-6 py-4">
                                @if($template->is_default)
                                    <span class="px-2.5 py-1 rounded-full text-xs font-medium bg-green-50 text-green-700 dark:bg-green-950/30 dark:text-green-400">{{ __('barcode::barcode.yes') }}</span>
                                @else
                                    <span class="px-2.5 py-1 rounded-full text-xs font-medium bg-gray-100 text-gray-500 dark:bg-gray-800 dark:text-gray-400">{{ __('barcode::barcode.no') }}</span>
                                @endif
                            </td>
                            <td class="px-6 py-4">
                                @if($template->is_active)
                                    <span class="px-2.5 py-1 rounded-full text-xs font-medium bg-green-50 text-green-700 dark:bg-green-950/30 dark:text-green-400">{{ __('barcode::barcode.yes') }}</span>
                                @else
                                    <span class="px-2.5 py-1 rounded-full text-xs font-medium bg-red-50 text-red-700 dark:bg-red-950/30 dark:text-red-400">{{ __('barcode::barcode.no') }}</span>
                                @endif
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex items-center justify-center gap-2">
                                    <button wire:click="openPreview({{ $template->id }})"
                                        class="p-2 text-gray-400 hover:text-blue-600 dark:hover:text-blue-400 transition-colors rounded-lg hover:bg-gray-100 dark:hover:bg-gray-800"
                                        title="{{ __('barcode::barcode.preview') }}">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                        </svg>
                                    </button>
                                    <button wire:click="openEditModal({{ $template->id }})"
                                        class="p-2 text-gray-400 hover:text-amber-600 dark:hover:text-amber-400 transition-colors rounded-lg hover:bg-gray-100 dark:hover:bg-gray-800"
                                        title="{{ __('barcode::barcode.edit') }}">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                        </svg>
                                    </button>
                                    <button wire:click="$dispatch('delete', { id: {{ $template->id }} })"
                                        wire:confirm="{{ __('barcode::barcode.confirm_delete') }}"
                                        class="p-2 text-gray-400 hover:text-red-600 dark:hover:text-red-400 transition-colors rounded-lg hover:bg-gray-100 dark:hover:bg-gray-800"
                                        title="{{ __('barcode::barcode.delete') }}">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                        </svg>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-6 py-12 text-center text-gray-400 dark:text-gray-500">
                                {{ __('barcode::barcode.no_templates') }}
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="px-6 py-4 border-t border-gray-100 dark:border-gray-800">
            {{ $templates->links() }}
        </div>
    </div>

    @if($showFormModal)
        <div class="fixed inset-0 z-50 flex items-center justify-center bg-black/50 backdrop-blur-sm">
            <div class="bg-white dark:bg-gray-900 rounded-2xl shadow-2xl border border-gray-100 dark:border-gray-800 w-full max-w-3xl max-h-[90vh] overflow-y-auto">
                <div class="flex items-center justify-between p-6 border-b border-gray-100 dark:border-gray-800">
                    <h3 class="text-lg font-bold text-gray-900 dark:text-white">
                        {{ $templateId ? __('barcode::barcode.edit_template') : __('barcode::barcode.add_template') }}
                    </h3>
                    <button wire:click="$set('showFormModal', false)" class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-300 transition-colors">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>
                <div class="p-6 space-y-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-bold text-gray-400 mb-1.5 uppercase">{{ __('barcode::barcode.name') }}</label>
                            <input type="text" wire:model="name" class="w-full py-2 px-3 border border-gray-200 dark:border-gray-700 rounded-lg bg-gray-50 dark:bg-gray-800 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 dark:text-white">
                            @error('name') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-gray-400 mb-1.5 uppercase">{{ __('barcode::barcode.name_ar') }}</label>
                            <input type="text" wire:model="name_ar" class="w-full py-2 px-3 border border-gray-200 dark:border-gray-700 rounded-lg bg-gray-50 dark:bg-gray-800 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 dark:text-white">
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-gray-400 mb-1.5 uppercase">{{ __('barcode::barcode.barcode_type') }}</label>
                            <select wire:model="barcode_type" class="w-full py-2 px-3 border border-gray-200 dark:border-gray-700 rounded-lg bg-gray-50 dark:bg-gray-800 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 dark:text-white">
                                <option value="code128">{{ __('barcode::barcode.code128') }}</option>
                                <option value="ean13">{{ __('barcode::barcode.ean13') }}</option>
                                <option value="ean8">{{ __('barcode::barcode.ean8') }}</option>
                                <option value="upca">{{ __('barcode::barcode.upca') }}</option>
                                <option value="qr">{{ __('barcode::barcode.qr') }}</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-gray-400 mb-1.5 uppercase">{{ __('barcode::barcode.font_size') }}</label>
                            <input type="number" wire:model="font_size" class="w-full py-2 px-3 border border-gray-200 dark:border-gray-700 rounded-lg bg-gray-50 dark:bg-gray-800 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 dark:text-white">
                            @error('font_size') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-gray-400 mb-1.5 uppercase">{{ __('barcode::barcode.label_width') }}</label>
                            <input type="number" step="0.01" wire:model="width_mm" class="w-full py-2 px-3 border border-gray-200 dark:border-gray-700 rounded-lg bg-gray-50 dark:bg-gray-800 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 dark:text-white">
                            @error('width_mm') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-gray-400 mb-1.5 uppercase">{{ __('barcode::barcode.label_height') }}</label>
                            <input type="number" step="0.01" wire:model="height_mm" class="w-full py-2 px-3 border border-gray-200 dark:border-gray-700 rounded-lg bg-gray-50 dark:bg-gray-800 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 dark:text-white">
                            @error('height_mm') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-gray-400 mb-1.5 uppercase">{{ __('barcode::barcode.margin_top') }}</label>
                            <input type="number" step="0.01" wire:model="margin_top" class="w-full py-2 px-3 border border-gray-200 dark:border-gray-700 rounded-lg bg-gray-50 dark:bg-gray-800 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 dark:text-white">
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-gray-400 mb-1.5 uppercase">{{ __('barcode::barcode.margin_bottom') }}</label>
                            <input type="number" step="0.01" wire:model="margin_bottom" class="w-full py-2 px-3 border border-gray-200 dark:border-gray-700 rounded-lg bg-gray-50 dark:bg-gray-800 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 dark:text-white">
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-gray-400 mb-1.5 uppercase">{{ __('barcode::barcode.margin_left') }}</label>
                            <input type="number" step="0.01" wire:model="margin_left" class="w-full py-2 px-3 border border-gray-200 dark:border-gray-700 rounded-lg bg-gray-50 dark:bg-gray-800 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 dark:text-white">
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-gray-400 mb-1.5 uppercase">{{ __('barcode::barcode.margin_right') }}</label>
                            <input type="number" step="0.01" wire:model="margin_right" class="w-full py-2 px-3 border border-gray-200 dark:border-gray-700 rounded-lg bg-gray-50 dark:bg-gray-800 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 dark:text-white">
                        </div>
                    </div>

                    <div class="flex flex-wrap gap-6 pt-4 border-t border-gray-100 dark:border-gray-800">
                        <label class="flex items-center gap-2 cursor-pointer">
                            <input type="checkbox" wire:model="show_price" class="rounded border-gray-300 dark:border-gray-600 text-blue-600 focus:ring-blue-500">
                            <span class="text-sm text-gray-700 dark:text-gray-300">{{ __('barcode::barcode.show_price') }}</span>
                        </label>
                        <label class="flex items-center gap-2 cursor-pointer">
                            <input type="checkbox" wire:model="show_name" class="rounded border-gray-300 dark:border-gray-600 text-blue-600 focus:ring-blue-500">
                            <span class="text-sm text-gray-700 dark:text-gray-300">{{ __('barcode::barcode.show_name') }}</span>
                        </label>
                        <label class="flex items-center gap-2 cursor-pointer">
                            <input type="checkbox" wire:model="show_sku" class="rounded border-gray-300 dark:border-gray-600 text-blue-600 focus:ring-blue-500">
                            <span class="text-sm text-gray-700 dark:text-gray-300">{{ __('barcode::barcode.show_sku') }}</span>
                        </label>
                        <label class="flex items-center gap-2 cursor-pointer">
                            <input type="checkbox" wire:model="is_default" class="rounded border-gray-300 dark:border-gray-600 text-blue-600 focus:ring-blue-500">
                            <span class="text-sm text-gray-700 dark:text-gray-300">{{ __('barcode::barcode.is_default') }}</span>
                        </label>
                        <label class="flex items-center gap-2 cursor-pointer">
                            <input type="checkbox" wire:model="is_active" class="rounded border-gray-300 dark:border-gray-600 text-blue-600 focus:ring-blue-500">
                            <span class="text-sm text-gray-700 dark:text-gray-300">{{ __('barcode::barcode.is_active') }}</span>
                        </label>
                    </div>
                </div>
                <div class="flex items-center justify-end gap-3 p-6 border-t border-gray-100 dark:border-gray-800">
                    <button wire:click="$set('showFormModal', false)"
                        class="px-4 py-2.5 text-sm font-medium text-gray-600 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-800 rounded-lg transition-colors">
                        {{ __('barcode::barcode.cancel') }}
                    </button>
                    <button wire:click="save"
                        class="px-4 py-2.5 bg-blue-600 hover:bg-blue-700 active:bg-blue-800 text-white rounded-lg text-sm font-semibold transition-colors shadow-sm">
                        {{ __('barcode::barcode.save') }}
                    </button>
                </div>
            </div>
        </div>
    @endif

    @if($showPreviewModal)
        <div class="fixed inset-0 z-50 flex items-center justify-center bg-black/50 backdrop-blur-sm">
            <div class="bg-white dark:bg-gray-900 rounded-2xl shadow-2xl border border-gray-100 dark:border-gray-800 w-full max-w-lg">
                <div class="flex items-center justify-between p-6 border-b border-gray-100 dark:border-gray-800">
                    <h3 class="text-lg font-bold text-gray-900 dark:text-white">{{ __('barcode::barcode.preview') }}</h3>
                    <button wire:click="$set('showPreviewModal', false)" class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-300 transition-colors">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>
                <div class="p-6">
                    <div class="border border-gray-200 dark:border-gray-700 rounded-lg p-4 bg-white" style="width: {{ $previewData['width_mm'] * 3.78 ?? 200 }}px;">
                        {!! $previewData['html'] ?? '' !!}
                    </div>
                </div>
                <div class="flex items-center justify-end p-6 border-t border-gray-100 dark:border-gray-800">
                    <button wire:click="$set('showPreviewModal', false)"
                        class="px-4 py-2.5 text-sm font-medium text-gray-600 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-800 rounded-lg transition-colors">
                        {{ __('barcode::barcode.close') }}
                    </button>
                </div>
            </div>
        </div>
    @endif
</div>
