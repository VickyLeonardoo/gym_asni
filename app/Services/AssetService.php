<?php

namespace App\Services;

use App\Models\Asset;
use App\Models\PurchaseStore;
use App\Models\ServiceContact;
use App\Models\Supplier;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;

class AssetService
{
    public function paginate(array $filters): LengthAwarePaginator
    {
        return Asset::query()
            ->with(['purchaseStore', 'supplier', 'serviceContact', 'maintenances'])
            ->search($filters['search'] ?? null)
            ->when($filters['status'] ?? null, fn ($query, string $status) => $query->where('status', $status))
            ->when($filters['condition'] ?? null, fn ($query, string $condition) => $query->where('condition', $condition))
            ->latest()
            ->paginate(15)
            ->withQueryString();
    }

    public function paginateArchived(array $filters): LengthAwarePaginator
    {
        return Asset::onlyTrashed()
            ->with(['purchaseStore', 'supplier', 'serviceContact', 'maintenances'])
            ->search($filters['search'] ?? null)
            ->when($filters['status'] ?? null, fn ($query, string $status) => $query->where('status', $status))
            ->when($filters['condition'] ?? null, fn ($query, string $condition) => $query->where('condition', $condition))
            ->latest('deleted_at')
            ->paginate(15)
            ->withQueryString();
    }

    public function create(array $data): Asset
    {
        return DB::transaction(function () use ($data): Asset {
            $data = $this->syncReferences($data);
            $data['asset_code'] = $data['asset_code'] ?? $this->nextAssetCode();
            $data['created_by'] = auth()->id();

            $asset = Asset::query()->create($data);
            $asset->conditionLogs()->create([
                'condition' => $asset->condition->value,
                'notes' => 'Initial condition recorded.',
                'reported_by' => auth()->id(),
            ]);

            return $asset;
        });
    }

    public function update(Asset $asset, array $data): Asset
    {
        return DB::transaction(function () use ($asset, $data): Asset {
            $previousCondition = $asset->condition?->value;
            $asset->update($this->syncReferences($data));

            if ($previousCondition !== $asset->condition->value) {
                $asset->conditionLogs()->create([
                    'condition' => $asset->condition->value,
                    'notes' => 'Condition changed during asset update.',
                    'reported_by' => auth()->id(),
                ]);
            }

            return $asset->refresh();
        });
    }

    public function delete(Asset $asset): void
    {
        DB::transaction(fn () => $asset->delete());
    }

    public function restore(Asset $asset): Asset
    {
        return DB::transaction(function () use ($asset): Asset {
            $asset->restore();

            return $asset->refresh();
        });
    }

    private function syncReferences(array $data): array
    {
        if (array_key_exists('service_contact_id', $data)) {
            unset($data['service_contact_name']);
        }

        foreach ([
            'purchase_store_name' => [PurchaseStore::class, 'purchase_store_id'],
            'supplier_name' => [Supplier::class, 'supplier_id'],
            'service_contact_name' => [ServiceContact::class, 'service_contact_id'],
        ] as $field => [$model, $target]) {
            if (! empty($data[$field])) {
                $data[$target] = $model::query()->firstOrCreate(['name' => $data[$field]])->id;
            }

            unset($data[$field]);
        }

        return $data;
    }

    private function nextAssetCode(): string
    {
        $nextId = (int) (Asset::withTrashed()->max('id') ?? 0) + 1;

        return 'AST-'.str_pad((string) $nextId, 5, '0', STR_PAD_LEFT);
    }
}
