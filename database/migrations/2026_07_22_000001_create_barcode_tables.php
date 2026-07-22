<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('barcode_templates', function (Blueprint $table) {
            $table->id();
            $table->foreignId('company_id')->constrained('companies')->onDelete('cascade');
            $table->string('name');
            $table->string('name_ar')->nullable();
            $table->decimal('width_mm', 8, 2)->default(50.00);
            $table->decimal('height_mm', 8, 2)->default(30.00);
            $table->decimal('margin_top', 5, 2)->default(2.00);
            $table->decimal('margin_bottom', 5, 2)->default(2.00);
            $table->decimal('margin_left', 5, 2)->default(2.00);
            $table->decimal('margin_right', 5, 2)->default(2.00);
            $table->string('barcode_type')->default('code128');
            $table->boolean('show_price')->default(true);
            $table->boolean('show_name')->default(true);
            $table->boolean('show_sku')->default(true);
            $table->integer('font_size')->default(10);
            $table->json('label_fields')->nullable();
            $table->boolean('is_default')->default(false);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            $table->softDeletes();

            $table->index('company_id');
            $table->index('is_default');
            $table->index('is_active');
        });

        Schema::create('barcode_jobs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('company_id')->constrained('companies')->onDelete('cascade');
            $table->foreignId('template_id')->constrained('barcode_templates')->onDelete('cascade');
            $table->json('product_ids')->nullable();
            $table->json('variant_ids')->nullable();
            $table->integer('quantity_per_label')->default(1);
            $table->integer('total_labels')->default(0);
            $table->string('status')->default('pending');
            $table->string('file_path')->nullable();
            $table->foreignId('created_by')->constrained('users')->onDelete('cascade');
            $table->timestamps();

            $table->index('company_id');
            $table->index('template_id');
            $table->index('status');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('barcode_jobs');
        Schema::dropIfExists('barcode_templates');
    }
};
