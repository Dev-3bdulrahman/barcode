<?php

namespace Dev3bdulrahman\Barcode;

use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;
use Dev3bdulrahman\Barcode\Models\BarcodeTemplate;
use Dev3bdulrahman\Barcode\Models\BarcodeJob;
use Dev3bdulrahman\Barcode\Policies\BarcodeTemplatePolicy;
use Dev3bdulrahman\Barcode\Policies\BarcodeJobPolicy;

class BarcodeServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        $this->loadMigrationsFrom(__DIR__ . '/../database/migrations');

        $this->loadRoutesFrom(__DIR__ . '/Routes/web.php');
        $this->loadRoutesFrom(__DIR__ . '/Routes/api.php');

        $this->loadViewsFrom(__DIR__ . '/Views', 'barcode');

        $this->loadTranslationsFrom(__DIR__ . '/Translations', 'barcode');

        Gate::policy(BarcodeTemplate::class, BarcodeTemplatePolicy::class);
        Gate::policy(BarcodeJob::class, BarcodeJobPolicy::class);

        if (class_exists(\Livewire\Livewire::class)) {
            \Livewire\Livewire::component('barcode-templates-index', \Dev3bdulrahman\Barcode\Http\Controllers\Web\Admin\Templates\Index::class);
            \Livewire\Livewire::component('barcode-print-index', \Dev3bdulrahman\Barcode\Http\Controllers\Web\Admin\PrintLabels\Index::class);
        }
    }
}
