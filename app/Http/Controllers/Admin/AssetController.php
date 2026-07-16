<?php

namespace App\Http\Controllers\Admin;

use App\Enums\AssetCondition;
use App\Enums\AssetStatus;
use App\Http\Controllers\Controller;
use App\Http\Requests\Asset\StoreAssetRequest;
use App\Http\Requests\Asset\UpdateAssetRequest;
use App\Models\Asset;
use App\Models\ServiceContact;
use App\Services\AssetService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AssetController extends Controller
{
    public function index(Request $request, AssetService $service): View
    {
        $this->authorize('viewAny', Asset::class);

        return view('assets.index', [
            'assets' => $service->paginate($request->only(['search', 'status', 'condition'])),
            'statuses' => AssetStatus::cases(),
            'conditions' => AssetCondition::cases(),
        ]);
    }

    public function archived(Request $request, AssetService $service): View
    {
        $this->authorize('viewAny', Asset::class);

        return view('assets.archived', [
            'assets' => $service->paginateArchived($request->only(['search', 'status', 'condition'])),
            'statuses' => AssetStatus::cases(),
            'conditions' => AssetCondition::cases(),
        ]);
    }

    public function create(): View
    {
        $this->authorize('create', Asset::class);

        return view('assets.create', [
            'statuses' => AssetStatus::cases(),
            'conditions' => AssetCondition::cases(),
            'serviceContacts' => ServiceContact::query()->orderBy('name')->get(),
        ]);
    }

    public function store(StoreAssetRequest $request, AssetService $service): RedirectResponse
    {
        $asset = $service->create($request->validated());

        return redirect()->route('assets.show', $asset)->with('status', 'Asset created successfully.');
    }

    public function show(Asset $asset): View
    {
        $this->authorize('view', $asset);

        return view('assets.show', [
            'asset' => $asset->load(['purchaseStore', 'supplier', 'serviceContact', 'maintenances.serviceContact', 'conditionLogs']),
        ]);
    }

    public function edit(Asset $asset): View
    {
        $this->authorize('update', $asset);

        return view('assets.edit', [
            'asset' => $asset,
            'statuses' => AssetStatus::cases(),
            'conditions' => AssetCondition::cases(),
            'serviceContacts' => ServiceContact::query()->orderBy('name')->get(),
        ]);
    }

    public function update(UpdateAssetRequest $request, Asset $asset, AssetService $service): RedirectResponse
    {
        $service->update($asset, $request->validated());

        return redirect()->route('assets.show', $asset)->with('status', 'Asset updated successfully.');
    }

    public function destroy(Asset $asset, AssetService $service): RedirectResponse
    {
        $this->authorize('delete', $asset);
        $service->delete($asset);

        return redirect()->route('assets.index')->with('status', 'Asset archived successfully.');
    }

    public function restore(string $asset, AssetService $service): RedirectResponse
    {
        $asset = Asset::withTrashed()->findOrFail($asset);
        $this->authorize('restore', $asset);
        $service->restore($asset);

        return redirect()->route('assets.archived')->with('status', 'Asset restored successfully.');
    }
}
