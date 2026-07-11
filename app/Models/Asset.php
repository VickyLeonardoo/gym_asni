<?php

namespace App\Models;

use App\Enums\AssetCondition;
use App\Enums\AssetStatus;
use App\Enums\MaintenanceStatus;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Carbon;

#[Fillable([
    'asset_code',
    'name',
    'category',
    'brand',
    'model',
    'serial_number',
    'purchase_store_id',
    'supplier_id',
    'service_contact_id',
    'purchase_date',
    'purchase_price',
    'warranty_expires_at',
    'maintenance_interval_months',
    'status',
    'condition',
    'location',
    'notes',
    'created_by',
])]
class Asset extends Model
{
    use HasFactory, SoftDeletes;

    protected function casts(): array
    {
        return [
            'purchase_date' => 'date',
            'purchase_price' => 'decimal:2',
            'warranty_expires_at' => 'date',
            'maintenance_interval_months' => 'integer',
            'status' => AssetStatus::class,
            'condition' => AssetCondition::class,
        ];
    }

    public function purchaseStore(): BelongsTo
    {
        return $this->belongsTo(PurchaseStore::class);
    }

    public function supplier(): BelongsTo
    {
        return $this->belongsTo(Supplier::class);
    }

    public function serviceContact(): BelongsTo
    {
        return $this->belongsTo(ServiceContact::class);
    }

    public function maintenances(): HasMany
    {
        return $this->hasMany(AssetMaintenance::class);
    }

    public function lastCompletedMaintenance(): HasMany
    {
        return $this->maintenances()
            ->where('status', MaintenanceStatus::Completed->value)
            ->whereNotNull('completed_at')
            ->latest('completed_at');
    }

    public function conditionLogs(): HasMany
    {
        return $this->hasMany(AssetConditionLog::class);
    }

    public function scopeSearch(Builder $query, ?string $search): Builder
    {
        return $query->when($search, function (Builder $query, string $search): void {
            $query->where(function (Builder $query) use ($search): void {
                $query->where('asset_code', 'like', "%{$search}%")
                    ->orWhere('name', 'like', "%{$search}%")
                    ->orWhere('category', 'like', "%{$search}%")
                    ->orWhere('brand', 'like', "%{$search}%")
                    ->orWhere('serial_number', 'like', "%{$search}%");
            });
        });
    }

    public function lastMaintenanceDate(): ?Carbon
    {
        if ($this->relationLoaded('maintenances')) {
            return $this->maintenances
                ->where('status', MaintenanceStatus::Completed)
                ->whereNotNull('completed_at')
                ->sortByDesc('completed_at')
                ->first()?->completed_at;
        }

        $date = $this->maintenances()
            ->where('status', MaintenanceStatus::Completed->value)
            ->whereNotNull('completed_at')
            ->latest('completed_at')
            ->value('completed_at');

        return $date ? Carbon::parse($date) : null;
    }

    public function nextMaintenanceDueDate(): ?Carbon
    {
        if (! $this->maintenance_interval_months) {
            return null;
        }

        $baseDate = $this->lastMaintenanceDate() ?? $this->purchase_date;

        return $baseDate?->copy()->addMonths($this->maintenance_interval_months);
    }
}
