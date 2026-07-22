<?php

use Illuminate\Support\Facades\Route;

Route::middleware(['web', 'auth', 'role:super-admin|developer|admin|employee', 'license'])
    ->prefix('admin')
    ->group(function () {
        Route::get('/barcode/templates', \Dev3bdulrahman\Barcode\Http\Controllers\Web\Admin\Templates\Index::class)->name('admin.barcode.templates');
        Route::get('/barcode/print', \Dev3bdulrahman\Barcode\Http\Controllers\Web\Admin\PrintLabels\Index::class)->name('admin.barcode.print');
    });
