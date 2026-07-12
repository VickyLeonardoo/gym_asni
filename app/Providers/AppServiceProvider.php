<?php

namespace App\Providers;

use App\Enums\UserRole;
use App\Models\Asset;
use App\Models\AssetMaintenance;
use App\Models\Member;
use App\Models\Membership;
use App\Models\MembershipPayment;
use App\Models\MembershipPlan;
use App\Models\MembershipTransaction;
use App\Models\ServiceContact;
use App\Models\User;
use App\Policies\AssetMaintenancePolicy;
use App\Policies\AssetPolicy;
use App\Policies\MemberPolicy;
use App\Policies\MembershipPaymentPolicy;
use App\Policies\MembershipPlanPolicy;
use App\Policies\MembershipPolicy;
use App\Policies\MembershipTransactionPolicy;
use App\Policies\ServiceContactPolicy;
use App\Policies\UserPolicy;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Gate::policy(User::class, UserPolicy::class);
        Gate::policy(Member::class, MemberPolicy::class);
        Gate::policy(Membership::class, MembershipPolicy::class);
        Gate::policy(MembershipPayment::class, MembershipPaymentPolicy::class);
        Gate::policy(MembershipPlan::class, MembershipPlanPolicy::class);
        Gate::policy(MembershipTransaction::class, MembershipTransactionPolicy::class);
        Gate::policy(Asset::class, AssetPolicy::class);
        Gate::policy(AssetMaintenance::class, AssetMaintenancePolicy::class);
        Gate::policy(ServiceContact::class, ServiceContactPolicy::class);

        Gate::before(function (User $user): ?bool {
            return $user->role === UserRole::Owner ? true : null;
        });
    }
}
