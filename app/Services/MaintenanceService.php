<?php

namespace App\Services;

use App\Enums\AssetStatus;
use App\Enums\MaintenanceStatus;
use App\Models\Asset;
use App\Models\AssetMaintenance;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;

class MaintenanceService
{
    public function paginate(array $filters): LengthAwarePaginator
    {
        return AssetMaintenance::query()
            ->with(['asset', 'serviceContact'])
            ->when($filters['status'] ?? null, fn ($query, string $status) => $query->where('status', $status))
            ->latest('scheduled_at')
            ->paginate(15)
            ->withQueryString();
    }

    public function create(Asset $asset, array $data): AssetMaintenance
    {
        return DB::transaction(function () use ($asset, $data): AssetMaintenance {
            $data['created_by'] = auth()->id();
            $maintenance = $asset->maintenances()->create($data);
            $asset->update(['status' => AssetStatus::Maintenance->value]);

            return $maintenance;
        });
    }

    public function update(AssetMaintenance $maintenance, array $data): AssetMaintenance
    {
        return DB::transaction(function () use ($maintenance, $data): AssetMaintenance {
            $maintenance->update($data);

            if ($maintenance->status === MaintenanceStatus::Completed) {
                $maintenance->asset->update(['status' => AssetStatus::Available->value]);
            }

            return $maintenance->refresh();
        });
    }

    public function complete(AssetMaintenance $maintenance): AssetMaintenance
    {
        return DB::transaction(function () use ($maintenance): AssetMaintenance {
            $maintenance->update([
                'status' => MaintenanceStatus::Completed->value,
                'completed_at' => $maintenance->completed_at ?? now()->toDateString(),
            ]);

            $maintenance->asset->update(['status' => AssetStatus::Available->value]);

            return $maintenance->refresh();
        });
    }

    public function delete(AssetMaintenance $maintenance): void
    {
        DB::transaction(fn () => $maintenance->delete());
    }
}
