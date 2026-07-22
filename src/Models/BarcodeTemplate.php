<?php

namespace Dev3bdulrahman\Barcode\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Traits\BelongsToCompany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class BarcodeTemplate extends Model
{
    use BelongsToCompany, SoftDeletes;

    protected $table = 'barcode_templates';

    protected $fillable = [
        'company_id',
        'name',
        'name_ar',
        'width_mm',
        'height_mm',
        'margin_top',
        'margin_bottom',
        'margin_left',
        'margin_right',
        'barcode_type',
        'show_price',
        'show_name',
        'show_sku',
        'font_size',
        'label_fields',
        'is_default',
        'is_active',
    ];

    protected $casts = [
        'width_mm' => 'decimal:2',
        'height_mm' => 'decimal:2',
        'margin_top' => 'decimal:2',
        'margin_bottom' => 'decimal:2',
        'margin_left' => 'decimal:2',
        'margin_right' => 'decimal:2',
        'show_price' => 'boolean',
        'show_name' => 'boolean',
        'show_sku' => 'boolean',
        'is_default' => 'boolean',
        'is_active' => 'boolean',
        'label_fields' => 'array',
    ];

    public function jobs(): HasMany
    {
        return $this->hasMany(BarcodeJob::class, 'template_id');
    }
}
