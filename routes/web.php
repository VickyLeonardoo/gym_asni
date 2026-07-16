<?php

use App\Http\Controllers\Admin\AssetController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\MaintenanceController;
use App\Http\Controllers\Admin\MemberController;
use App\Http\Controllers\Admin\MembershipController;
use App\Http\Controllers\Admin\MembershipPlanController;
use App\Http\Controllers\Admin\MembershipTransactionController;
use App\Http\Controllers\Admin\ReportController;
use App\Http\Controllers\Admin\ServiceContactController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\ProfileController;
use App\Models\MembershipPlan;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome', [
        'plans' => MembershipPlan::query()
            ->where('is_active', true)
            ->orderBy('duration_days')
            ->get(),
    ]);
});

Route::middleware(['auth', 'verified'])->group(function (): void {
    Route::get('/dashboard', DashboardController::class)->name('dashboard');
    Route::resource('users', UserController::class)->except(['show']);
    Route::get('members/archived', [MemberController::class, 'archived'])->name('members.archived');
    Route::patch('members/{member}/restore', [MemberController::class, 'restore'])->name('members.restore');
    Route::resource('members', MemberController::class);
    Route::get('members/{member}/renew', [MembershipController::class, 'create'])->name('memberships.create');
    Route::post('members/{member}/renew', [MembershipController::class, 'store'])->name('memberships.store');
    Route::get('memberships', [MembershipController::class, 'index'])->name('memberships.index');
    Route::get('memberships/{membership}', [MembershipController::class, 'show'])->name('memberships.show');
    Route::resource('packages', MembershipPlanController::class)->except(['show']);
    Route::get('transactions', [MembershipTransactionController::class, 'index'])->name('transactions.index');
    Route::get('transactions/create', [MembershipTransactionController::class, 'create'])->name('transactions.create');
    Route::post('transactions', [MembershipTransactionController::class, 'store'])->name('transactions.store');
    Route::patch('transactions/{transaction}/verify', [MembershipTransactionController::class, 'verify'])->name('transactions.verify');
    Route::resource('assets', AssetController::class);
    Route::get('assets/{asset}/maintenances/create', [MaintenanceController::class, 'create'])->name('maintenances.create');
    Route::post('assets/{asset}/maintenances', [MaintenanceController::class, 'store'])->name('maintenances.store');
    Route::get('maintenances', [MaintenanceController::class, 'index'])->name('maintenances.index');
    Route::get('maintenances/{maintenance}/edit', [MaintenanceController::class, 'edit'])->name('maintenances.edit');
    Route::patch('maintenances/{maintenance}/complete', [MaintenanceController::class, 'complete'])->name('maintenances.complete');
    Route::put('maintenances/{maintenance}', [MaintenanceController::class, 'update'])->name('maintenances.update');
    Route::delete('maintenances/{maintenance}', [MaintenanceController::class, 'destroy'])->name('maintenances.destroy');
    Route::resource('service-contacts', ServiceContactController::class)->except(['show']);
    Route::get('reports', [ReportController::class, 'index'])->name('reports.index');
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
