<?php

use Illuminate\Support\Facades\Route;
use Dev3bdulrahman\Barcode\Http\Controllers\Api\BarcodeApiController;

Route::prefix('api/v1/barcode')->middleware(['auth:sanctum', 'throttle:60,1', 'api.tenant'])->group(function () {
    Route::get('templates', [BarcodeApiController::class, 'index'])->middleware('can:barcode.templates.view')->name('api.v1.barcode.templates.index');
    Route::post('templates', [BarcodeApiController::class, 'store'])->middleware('can:barcode.templates.create')->name('api.v1.barcode.templates.store');
    Route::get('templates/{barcodeTemplate}', [BarcodeApiController::class, 'show'])->middleware('can:barcode.templates.view')->name('api.v1.barcode.templates.show');
    Route::put('templates/{barcodeTemplate}', [BarcodeApiController::class, 'update'])->middleware('can:barcode.templates.edit')->name('api.v1.barcode.templates.update');
    Route::delete('templates/{barcodeTemplate}', [BarcodeApiController::class, 'destroy'])->middleware('can:barcode.templates.delete')->name('api.v1.barcode.templates.destroy');

    Route::post('generate', [BarcodeApiController::class, 'generateBarcode'])->name('api.v1.barcode.generate');
    Route::post('generate-qr', [BarcodeApiController::class, 'generateQR'])->name('api.v1.barcode.generate-qr');
    Route::post('preview', [BarcodeApiController::class, 'preview'])->name('api.v1.barcode.preview');

    Route::get('jobs', [BarcodeApiController::class, 'jobs'])->middleware('can:barcode.jobs.view')->name('api.v1.barcode.jobs');
});
