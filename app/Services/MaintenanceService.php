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
            unset($data['cost'], $data['completed_at'], $data['resolution']);
            $data['created_by'] = auth()->id();
            $maintenance = $asset->maintenances()->create($data);

            if ($maintenance->scheduled_at->isToday() || $maintenance->scheduled_at->isPast()) {
                $asset->update(['status' => AssetStatus::Maintenance->value]);
            }

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

    public function complete(AssetMaintenance $maintenance, array $data): AssetMaintenance
    {
        return DB::transaction(function () use ($maintenance, $data): AssetMaintenance {
            $maintenance->update([
                'status' => MaintenanceStatus::Completed->value,
                'completed_at' => $data['completed_at'],
                'cost' => $data['cost'],
                'resolution' => $data['resolution'],
            ]);

            $maintenance->asset->update(['status' => AssetStatus::Available->value]);
            $this->scheduleNextRoutineMaintenance($maintenance->refresh());

            return $maintenance->refresh();
        });
    }

    private function scheduleNextRoutineMaintenance(AssetMaintenance $maintenance): void
    {
        $asset = $maintenance->asset;

        if (! $asset->maintenance_interval_months) {
            return;
        }

        $nextDate = $maintenance->completed_at->copy()->addMonths($asset->maintenance_interval_months);
        $alreadyScheduled = $asset->maintenances()
            ->whereIn('status', [MaintenanceStatus::Scheduled->value, MaintenanceStatus::InProgress->value])
            ->whereDate('scheduled_at', $nextDate->toDateString())
            ->exists();

        if (! $alreadyScheduled) {
            $asset->maintenances()->create([
                'service_contact_id' => $maintenance->service_contact_id ?? $asset->service_contact_id,
                'scheduled_at' => $nextDate,
                'description' => "Maintenance rutin setiap {$asset->maintenance_interval_months} bulan.",
                'created_by' => auth()->id(),
            ]);
        }
    }

    public function delete(AssetMaintenance $maintenance): void
    {
        DB::transaction(fn () => $maintenance->delete());
    }
}
