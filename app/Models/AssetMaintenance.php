<?php

namespace App\Models;

use App\Enums\MaintenanceStatus;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

#[Fillable(['asset_id', 'service_contact_id', 'scheduled_at', 'completed_at', 'status', 'cost', 'description', 'resolution', 'created_by'])]
class AssetMaintenance extends Model
{
    use HasFactory, SoftDeletes;

    protected function casts(): array
    {
        return [
            'scheduled_at' => 'date',
            'completed_at' => 'date',
            'status' => MaintenanceStatus::class,
            'cost' => 'decimal:2',
        ];
    }

    public function asset(): BelongsTo
    {
        return $this->belongsTo(Asset::class);
    }

    public function serviceContact(): BelongsTo
    {
        return $this->belongsTo(ServiceContact::class);
    }
}
