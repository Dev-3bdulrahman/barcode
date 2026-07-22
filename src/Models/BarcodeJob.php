<?php

namespace Dev3bdulrahman\Barcode\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\BelongsToCompany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Models\User;

class BarcodeJob extends Model
{
    use BelongsToCompany;

    protected $table = 'barcode_jobs';

    protected $fillable = [
        'company_id',
        'template_id',
        'product_ids',
        'variant_ids',
        'quantity_per_label',
        'total_labels',
        'status',
        'file_path',
        'created_by',
    ];

    protected $casts = [
        'product_ids' => 'array',
        'variant_ids' => 'array',
        'quantity_per_label' => 'integer',
        'total_labels' => 'integer',
    ];

    public function template(): BelongsTo
    {
        return $this->belongsTo(BarcodeTemplate::class, 'template_id');
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
