<?php

namespace App\Http\Controllers\Admin;

use App\Enums\MaintenanceStatus;
use App\Http\Controllers\Controller;
use App\Http\Requests\Maintenance\StoreMaintenanceRequest;
use App\Http\Requests\Maintenance\UpdateMaintenanceRequest;
use App\Models\Asset;
use App\Models\AssetMaintenance;
use App\Models\ServiceContact;
use App\Services\MaintenanceService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class MaintenanceController extends Controller
{
    public function index(Request $request, MaintenanceService $service): View
    {
        $this->authorize('viewAny', AssetMaintenance::class);

        return view('maintenances.index', [
            'maintenances' => $service->paginate($request->only('status')),
            'statuses' => MaintenanceStatus::cases(),
        ]);
    }

    public function create(Asset $asset): View
    {
        $this->authorize('create', AssetMaintenance::class);

        return view('maintenances.create', [
            'asset' => $asset,
            'contacts' => ServiceContact::query()->orderBy('name')->get(),
        ]);
    }

    public function store(StoreMaintenanceRequest $request, Asset $asset, MaintenanceService $service): RedirectResponse
    {
        $service->create($asset, $request->validated());

        return redirect()->route('assets.show', $asset)->with('status', 'Maintenance scheduled successfully.');
    }

    public function edit(AssetMaintenance $maintenance): View
    {
        $this->authorize('update', $maintenance);

        return view('maintenances.edit', [
            'maintenance' => $maintenance->load('asset'),
            'contacts' => ServiceContact::query()->orderBy('name')->get(),
            'statuses' => MaintenanceStatus::cases(),
        ]);
    }

    public function update(UpdateMaintenanceRequest $request, AssetMaintenance $maintenance, MaintenanceService $service): RedirectResponse
    {
        $service->update($maintenance, $request->validated());

        return redirect()->route('maintenances.index')->with('status', 'Maintenance updated successfully.');
    }

    public function complete(AssetMaintenance $maintenance, MaintenanceService $service): RedirectResponse
    {
        $this->authorize('update', $maintenance);
        $service->complete($maintenance);

        return back()->with('status', 'Maintenance completed successfully.');
    }

    public function destroy(AssetMaintenance $maintenance, MaintenanceService $service): RedirectResponse
    {
        $this->authorize('delete', $maintenance);
        $service->delete($maintenance);

        return back()->with('status', 'Maintenance deleted successfully.');
    }
}
