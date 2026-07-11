<?php

namespace Tests\Feature;

use App\Enums\AssetCondition;
use App\Enums\AssetStatus;
use App\Enums\MaintenanceStatus;
use App\Enums\MembershipStatus;
use App\Enums\PaymentStatus;
use App\Enums\UserRole;
use App\Models\Asset;
use App\Models\AssetMaintenance;
use App\Models\Member;
use App\Models\Membership;
use App\Models\MembershipPlan;
use App\Models\MembershipTransaction;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class GymManagementWorkflowTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_create_member_renew_membership_and_upload_payment_proof(): void
    {
        Storage::fake('public');
        $admin = User::factory()->create(['role' => UserRole::Admin]);
        $plan = MembershipPlan::query()->create([
            'name' => 'Monthly',
            'duration_days' => 30,
            'price' => 250000,
            'is_active' => true,
        ]);

        $memberResponse = $this->actingAs($admin)->post(route('members.store'), [
            'name' => 'Ayu Member',
            'email' => 'ayu@example.test',
            'phone' => '08123456789',
            'gender' => 'female',
            'membership_plan_id' => $plan->id,
            'starts_at' => '2026-07-03',
            'method' => 'bank_transfer',
            'proof' => UploadedFile::fake()->create('proof.pdf', 32, 'application/pdf'),
        ]);

        $transaction = MembershipTransaction::query()->firstOrFail();
        $memberResponse->assertRedirect(route('transactions.index'));
        $this->assertDatabaseCount('members', 0);

        $this->actingAs($admin)
            ->patch(route('transactions.verify', $transaction))
            ->assertRedirect();

        $member = Member::query()->firstOrFail();
        $membership = Membership::query()->with('payments')->firstOrFail();
        $this->assertSame($member->id, $membership->member_id);
        $this->assertSame(PaymentStatus::Verified, $membership->payments->first()->status);

        $renewResponse = $this->actingAs($admin)->post(route('memberships.store', $member), [
            'membership_plan_id' => $plan->id,
            'starts_at' => '2026-08-02',
            'method' => 'bank_transfer',
            'proof' => UploadedFile::fake()->create('proof.pdf', 32, 'application/pdf'),
        ]);

        $renewal = MembershipTransaction::query()->latest()->firstOrFail();
        $renewResponse->assertRedirect(route('transactions.index'));
        $this->assertSame(1, Membership::query()->count());

        $this->actingAs($admin)
            ->patch(route('transactions.verify', $renewal))
            ->assertRedirect();

        $latestMembership = Membership::query()->with('payments')->latest('expires_at')->firstOrFail();
        $this->assertSame(MembershipStatus::Active, $latestMembership->status);
        $this->assertSame(PaymentStatus::Verified, $latestMembership->payments->first()->status);
        Storage::disk('public')->assertExists($latestMembership->payments->first()->proof_path);
    }

    public function test_admin_can_create_asset_and_complete_maintenance(): void
    {
        $admin = User::factory()->create(['role' => UserRole::Admin]);

        $assetResponse = $this->actingAs($admin)->post(route('assets.store'), [
            'name' => 'Treadmill A1',
            'category' => 'Cardio',
            'purchase_price' => 15000000,
            'maintenance_interval_months' => 2,
            'status' => AssetStatus::Available->value,
            'condition' => AssetCondition::Good->value,
            'location' => 'Main Floor',
        ]);

        $asset = Asset::query()->firstOrFail();
        $assetResponse->assertRedirect(route('assets.show', $asset));
        $this->assertCount(1, $asset->conditionLogs);

        $maintenanceResponse = $this->actingAs($admin)->post(route('maintenances.store', $asset), [
            'scheduled_at' => '2026-07-04',
            'cost' => 125000,
            'description' => 'Monthly belt inspection.',
        ]);

        $maintenance = AssetMaintenance::query()->firstOrFail();
        $maintenanceResponse->assertRedirect(route('assets.show', $asset));
        $this->assertSame(AssetStatus::Maintenance, $asset->refresh()->status);

        $this->actingAs($admin)->put(route('maintenances.update', $maintenance), [
            'scheduled_at' => '2026-07-04',
            'completed_at' => '2026-07-04',
            'status' => MaintenanceStatus::Completed->value,
            'cost' => 125000,
            'description' => 'Monthly belt inspection.',
            'resolution' => 'Belt tension adjusted.',
        ])->assertRedirect(route('maintenances.index'));

        $this->assertSame(AssetStatus::Available, $asset->refresh()->status);
    }
}
