<?php

namespace Database\Seeders;

use App\Enums\AssetCondition;
use App\Enums\AssetStatus;
use App\Enums\MembershipTransactionType;
use App\Enums\PaymentStatus;
use App\Enums\UserRole;
use App\Models\Asset;
use App\Models\MembershipPlan;
use App\Models\MembershipTransaction;
use App\Models\PurchaseStore;
use App\Models\ServiceContact;
use App\Models\Supplier;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        User::query()->updateOrCreate(
            ['email' => 'owner@asni.test'],
            [
                'name' => 'Owner Asni Gym',
                'password' => Hash::make('password'),
                'role' => UserRole::Owner,
                'is_active' => true,
                'email_verified_at' => now(),
            ]
        );

        User::query()->updateOrCreate(
            ['email' => 'admin@asni.test'],
            [
                'name' => 'Admin Asni Gym',
                'password' => Hash::make('password'),
                'role' => UserRole::Admin,
                'is_active' => true,
                'email_verified_at' => now(),
            ]
        );

        collect([
            ['name' => 'Bulanan', 'duration_days' => 30, 'price' => 250000, 'description' => 'Paket membership gym standar untuk 30 hari.'],
            ['name' => 'Triwulan', 'duration_days' => 90, 'price' => 675000, 'description' => 'Paket membership gym untuk tiga bulan.'],
            ['name' => 'Tahunan', 'duration_days' => 365, 'price' => 2400000, 'description' => 'Paket membership tahunan dengan harga terbaik.'],
        ])->each(fn (array $plan) => MembershipPlan::query()->updateOrCreate(['name' => $plan['name']], $plan));

        $purchaseStores = collect([
            ['name' => 'FitPro Equipment Store', 'phone' => '021-7788-1001', 'email' => 'sales@fitpro.test', 'address' => 'Jl. Fitness Raya No. 12, Jakarta'],
            ['name' => 'Gym Supply Center', 'phone' => '021-7788-1002', 'email' => 'orders@gymsupply.test', 'address' => 'Jl. Atletik No. 7, Bandung'],
            ['name' => 'Cardio World Indonesia', 'phone' => '021-7788-1003', 'email' => 'hello@cardioworld.test', 'address' => 'Jl. Sehat No. 21, Tangerang'],
        ])->mapWithKeys(fn (array $store) => [
            $store['name'] => PurchaseStore::query()->updateOrCreate(['name' => $store['name']], $store),
        ]);

        $suppliers = collect([
            ['name' => 'IronForce Distributor', 'phone' => '021-8899-2001', 'email' => 'support@ironforce.test', 'address' => 'Kawasan Industri Cakung Blok C1'],
            ['name' => 'ActiveLine Fitness', 'phone' => '021-8899-2002', 'email' => 'team@activeline.test', 'address' => 'Jl. Kebugaran No. 88, Bekasi'],
            ['name' => 'Nusantara Sport Tech', 'phone' => '021-8899-2003', 'email' => 'service@nst.test', 'address' => 'Jl. Olahraga No. 5, Depok'],
        ])->mapWithKeys(fn (array $supplier) => [
            $supplier['name'] => Supplier::query()->updateOrCreate(['name' => $supplier['name']], $supplier),
        ]);

        $serviceContacts = collect([
            ['name' => 'Bima Gym Maintenance', 'phone' => '0812-7000-1001', 'email' => 'bima@gymmaint.test', 'address' => 'Jl. Servis Alat No. 9, Jakarta'],
            ['name' => 'Prima Fitness Repair', 'phone' => '0812-7000-1002', 'email' => 'prima@repair.test', 'address' => 'Jl. Teknisi No. 14, Bekasi'],
            ['name' => 'CardioCare Service', 'phone' => '0812-7000-1003', 'email' => 'care@cardio.test', 'address' => 'Jl. Mesin No. 2, Tangerang'],
        ])->mapWithKeys(fn (array $contact) => [
            $contact['name'] => ServiceContact::query()->updateOrCreate(['name' => $contact['name']], $contact),
        ]);

        collect([
            ['asset_code' => 'AST-TRD-001', 'name' => 'Treadmill Pro Runner 9000', 'category' => 'Cardio', 'brand' => 'LifeFit', 'model' => 'PR-9000', 'serial_number' => 'LF-PR9000-001', 'purchase_store' => 'Cardio World Indonesia', 'supplier' => 'Nusantara Sport Tech', 'service_contact' => 'CardioCare Service', 'purchase_date' => '2025-01-12', 'purchase_price' => 28500000, 'warranty_expires_at' => '2028-01-12', 'status' => AssetStatus::Available, 'condition' => AssetCondition::Excellent, 'location' => 'Cardio Area A', 'notes' => 'Main treadmill near front mirror.'],
            ['asset_code' => 'AST-TRD-002', 'name' => 'Treadmill Endurance X5', 'category' => 'Cardio', 'brand' => 'RunMax', 'model' => 'X5', 'serial_number' => 'RM-X5-002', 'purchase_store' => 'Cardio World Indonesia', 'supplier' => 'Nusantara Sport Tech', 'service_contact' => 'CardioCare Service', 'purchase_date' => '2025-02-01', 'purchase_price' => 24000000, 'warranty_expires_at' => '2028-02-01', 'status' => AssetStatus::InUse, 'condition' => AssetCondition::Good, 'location' => 'Cardio Area A', 'notes' => 'Daily use unit.'],
            ['asset_code' => 'AST-BIK-001', 'name' => 'Upright Bike Velocity 7', 'category' => 'Cardio', 'brand' => 'CycleFit', 'model' => 'V7', 'serial_number' => 'CF-V7-101', 'purchase_store' => 'FitPro Equipment Store', 'supplier' => 'ActiveLine Fitness', 'service_contact' => 'CardioCare Service', 'purchase_date' => '2024-11-15', 'purchase_price' => 11200000, 'warranty_expires_at' => '2027-11-15', 'status' => AssetStatus::Available, 'condition' => AssetCondition::Good, 'location' => 'Cardio Area B', 'notes' => 'Seat adjusted monthly.'],
            ['asset_code' => 'AST-ELL-001', 'name' => 'Elliptical Cross Trainer E8', 'category' => 'Cardio', 'brand' => 'MotionCore', 'model' => 'E8', 'serial_number' => 'MC-E8-201', 'purchase_store' => 'Gym Supply Center', 'supplier' => 'Nusantara Sport Tech', 'service_contact' => 'CardioCare Service', 'purchase_date' => '2024-09-20', 'purchase_price' => 17500000, 'warranty_expires_at' => '2027-09-20', 'status' => AssetStatus::Available, 'condition' => AssetCondition::Good, 'location' => 'Cardio Area B', 'notes' => 'Low impact cardio machine.'],
            ['asset_code' => 'AST-ROW-001', 'name' => 'Rowing Machine Rower 500', 'category' => 'Cardio', 'brand' => 'HydroPull', 'model' => 'R500', 'serial_number' => 'HP-R500-001', 'purchase_store' => 'FitPro Equipment Store', 'supplier' => 'ActiveLine Fitness', 'service_contact' => 'Bima Gym Maintenance', 'purchase_date' => '2024-08-05', 'purchase_price' => 13800000, 'warranty_expires_at' => '2027-08-05', 'status' => AssetStatus::Available, 'condition' => AssetCondition::Excellent, 'location' => 'Functional Zone', 'notes' => 'Used for HIIT sessions.'],
            ['asset_code' => 'AST-RCK-001', 'name' => 'Power Rack Heavy Duty', 'category' => 'Strength', 'brand' => 'IronForge', 'model' => 'HD-01', 'serial_number' => 'IF-HD01-001', 'purchase_store' => 'Gym Supply Center', 'supplier' => 'IronForce Distributor', 'service_contact' => 'Bima Gym Maintenance', 'purchase_date' => '2023-12-10', 'purchase_price' => 19500000, 'warranty_expires_at' => '2028-12-10', 'status' => AssetStatus::Available, 'condition' => AssetCondition::Good, 'location' => 'Strength Zone A', 'notes' => 'Includes safety arms and pull-up bar.'],
            ['asset_code' => 'AST-BNC-001', 'name' => 'Adjustable Bench Elite', 'category' => 'Strength', 'brand' => 'IronForge', 'model' => 'AB-300', 'serial_number' => 'IF-AB300-001', 'purchase_store' => 'Gym Supply Center', 'supplier' => 'IronForce Distributor', 'service_contact' => 'Bima Gym Maintenance', 'purchase_date' => '2024-01-18', 'purchase_price' => 5200000, 'warranty_expires_at' => '2027-01-18', 'status' => AssetStatus::InUse, 'condition' => AssetCondition::Good, 'location' => 'Strength Zone A', 'notes' => 'Primary bench for dumbbell area.'],
            ['asset_code' => 'AST-LGP-001', 'name' => 'Leg Press 45 Degree', 'category' => 'Strength', 'brand' => 'TitanGym', 'model' => 'LP45', 'serial_number' => 'TG-LP45-001', 'purchase_store' => 'FitPro Equipment Store', 'supplier' => 'IronForce Distributor', 'service_contact' => 'Prima Fitness Repair', 'purchase_date' => '2024-03-22', 'purchase_price' => 31500000, 'warranty_expires_at' => '2028-03-22', 'status' => AssetStatus::Available, 'condition' => AssetCondition::Good, 'location' => 'Leg Zone', 'notes' => 'Plate loaded machine.'],
            ['asset_code' => 'AST-CBL-001', 'name' => 'Cable Crossover Dual Pulley', 'category' => 'Strength', 'brand' => 'TitanGym', 'model' => 'CC-200', 'serial_number' => 'TG-CC200-001', 'purchase_store' => 'FitPro Equipment Store', 'supplier' => 'IronForce Distributor', 'service_contact' => 'Prima Fitness Repair', 'purchase_date' => '2024-04-12', 'purchase_price' => 33800000, 'warranty_expires_at' => '2028-04-12', 'status' => AssetStatus::Maintenance, 'condition' => AssetCondition::Fair, 'location' => 'Strength Zone B', 'notes' => 'Left pulley cable scheduled for inspection.'],
            ['asset_code' => 'AST-LPD-001', 'name' => 'Lat Pulldown Machine', 'category' => 'Strength', 'brand' => 'ForceLine', 'model' => 'LD-10', 'serial_number' => 'FL-LD10-001', 'purchase_store' => 'Gym Supply Center', 'supplier' => 'ActiveLine Fitness', 'service_contact' => 'Prima Fitness Repair', 'purchase_date' => '2024-05-03', 'purchase_price' => 18200000, 'warranty_expires_at' => '2027-05-03', 'status' => AssetStatus::Available, 'condition' => AssetCondition::Good, 'location' => 'Back Zone', 'notes' => 'Cable replaced in 2025.'],
            ['asset_code' => 'AST-SMT-001', 'name' => 'Smith Machine Pro Track', 'category' => 'Strength', 'brand' => 'ForceLine', 'model' => 'SM-PRO', 'serial_number' => 'FL-SMPRO-001', 'purchase_store' => 'FitPro Equipment Store', 'supplier' => 'IronForce Distributor', 'service_contact' => 'Bima Gym Maintenance', 'purchase_date' => '2023-10-08', 'purchase_price' => 26000000, 'warranty_expires_at' => '2027-10-08', 'status' => AssetStatus::Available, 'condition' => AssetCondition::Good, 'location' => 'Strength Zone B', 'notes' => 'Guide rail lubricated quarterly.'],
            ['asset_code' => 'AST-DBS-001', 'name' => 'Dumbbell Set 2.5-30kg', 'category' => 'Free Weight', 'brand' => 'IronForge', 'model' => 'DB-RUBBER', 'serial_number' => 'IF-DBSET-001', 'purchase_store' => 'Gym Supply Center', 'supplier' => 'IronForce Distributor', 'service_contact' => 'Bima Gym Maintenance', 'purchase_date' => '2023-09-11', 'purchase_price' => 22500000, 'warranty_expires_at' => '2026-09-11', 'status' => AssetStatus::Available, 'condition' => AssetCondition::Good, 'location' => 'Free Weight Area', 'notes' => 'Rubber coated dumbbell set.'],
            ['asset_code' => 'AST-BBL-001', 'name' => 'Olympic Barbell 20kg', 'category' => 'Free Weight', 'brand' => 'IronForge', 'model' => 'OB-20', 'serial_number' => 'IF-OB20-001', 'purchase_store' => 'Gym Supply Center', 'supplier' => 'IronForce Distributor', 'service_contact' => 'Bima Gym Maintenance', 'purchase_date' => '2023-09-11', 'purchase_price' => 3200000, 'warranty_expires_at' => '2026-09-11', 'status' => AssetStatus::InUse, 'condition' => AssetCondition::Good, 'location' => 'Free Weight Area', 'notes' => 'Main Olympic bar.'],
            ['asset_code' => 'AST-PLT-001', 'name' => 'Bumper Plate Set 150kg', 'category' => 'Free Weight', 'brand' => 'IronForge', 'model' => 'BP-150', 'serial_number' => 'IF-BP150-001', 'purchase_store' => 'Gym Supply Center', 'supplier' => 'IronForce Distributor', 'service_contact' => 'Bima Gym Maintenance', 'purchase_date' => '2023-09-12', 'purchase_price' => 11800000, 'warranty_expires_at' => '2026-09-12', 'status' => AssetStatus::Available, 'condition' => AssetCondition::Good, 'location' => 'Free Weight Area', 'notes' => 'Includes 5kg to 25kg pairs.'],
            ['asset_code' => 'AST-KTB-001', 'name' => 'Kettlebell Set 4-24kg', 'category' => 'Functional', 'brand' => 'ActiveLine', 'model' => 'KB-SET', 'serial_number' => 'AL-KBSET-001', 'purchase_store' => 'FitPro Equipment Store', 'supplier' => 'ActiveLine Fitness', 'service_contact' => 'Bima Gym Maintenance', 'purchase_date' => '2024-02-14', 'purchase_price' => 6800000, 'warranty_expires_at' => '2026-02-14', 'status' => AssetStatus::Available, 'condition' => AssetCondition::Excellent, 'location' => 'Functional Zone', 'notes' => 'Color coded kettlebells.'],
            ['asset_code' => 'AST-TRX-001', 'name' => 'Suspension Trainer Station', 'category' => 'Functional', 'brand' => 'ActiveLine', 'model' => 'ST-2', 'serial_number' => 'AL-ST2-001', 'purchase_store' => 'FitPro Equipment Store', 'supplier' => 'ActiveLine Fitness', 'service_contact' => 'Bima Gym Maintenance', 'purchase_date' => '2024-06-10', 'purchase_price' => 4500000, 'warranty_expires_at' => '2026-06-10', 'status' => AssetStatus::Available, 'condition' => AssetCondition::Good, 'location' => 'Functional Zone', 'notes' => 'Wall mounted suspension system.'],
            ['asset_code' => 'AST-MAT-001', 'name' => 'Yoga Mat Rack Set', 'category' => 'Accessory', 'brand' => 'FlexiFit', 'model' => 'YM-20', 'serial_number' => 'FF-YM20-001', 'purchase_store' => 'Gym Supply Center', 'supplier' => 'ActiveLine Fitness', 'service_contact' => 'Bima Gym Maintenance', 'purchase_date' => '2024-07-01', 'purchase_price' => 2800000, 'warranty_expires_at' => '2026-07-01', 'status' => AssetStatus::Available, 'condition' => AssetCondition::Fair, 'location' => 'Studio Room', 'notes' => '20 mats and storage rack.'],
            ['asset_code' => 'AST-ROL-001', 'name' => 'Foam Roller Set', 'category' => 'Recovery', 'brand' => 'FlexiFit', 'model' => 'FR-12', 'serial_number' => 'FF-FR12-001', 'purchase_store' => 'FitPro Equipment Store', 'supplier' => 'ActiveLine Fitness', 'service_contact' => 'Bima Gym Maintenance', 'purchase_date' => '2024-07-05', 'purchase_price' => 1800000, 'warranty_expires_at' => '2026-07-05', 'status' => AssetStatus::Available, 'condition' => AssetCondition::Good, 'location' => 'Recovery Corner', 'notes' => '12 foam rollers.'],
        ])->each(function (array $asset) use ($purchaseStores, $suppliers, $serviceContacts): void {
            $created = Asset::query()->updateOrCreate(
                ['asset_code' => $asset['asset_code']],
                [
                    'name' => $asset['name'],
                    'category' => $asset['category'],
                    'brand' => $asset['brand'],
                    'model' => $asset['model'],
                    'serial_number' => $asset['serial_number'],
                    'purchase_store_id' => $purchaseStores[$asset['purchase_store']]->id,
                    'supplier_id' => $suppliers[$asset['supplier']]->id,
                    'service_contact_id' => $serviceContacts[$asset['service_contact']]->id,
                    'purchase_date' => $asset['purchase_date'],
                    'purchase_price' => $asset['purchase_price'],
                    'warranty_expires_at' => $asset['warranty_expires_at'],
                    'maintenance_interval_months' => match ($asset['category']) {
                        'Cardio' => 2,
                        'Accessory', 'Recovery' => 6,
                        default => 3,
                    },
                    'status' => $asset['status']->value,
                    'condition' => $asset['condition']->value,
                    'location' => $asset['location'],
                    'notes' => $asset['notes'],
                    'created_by' => User::query()->where('email', 'admin@asni.test')->value('id'),
                ]
            );

            $created->conditionLogs()->updateOrCreate(
                ['notes' => 'Initial condition seeded.'],
                [
                    'condition' => $asset['condition']->value,
                    'reported_by' => User::query()->where('email', 'admin@asni.test')->value('id'),
                ]
            );
        });

        $plans = MembershipPlan::query()->get()->keyBy('name');
        $adminId = User::query()->where('email', 'admin@asni.test')->value('id');

        collect([
            ['name' => 'Raka Pratama', 'email' => 'raka.pratama@example.test', 'phone' => '0812-3100-1001', 'date_of_birth' => '1996-04-12', 'gender' => 'male', 'address' => 'Jl. Melati No. 18, Jakarta', 'emergency_contact' => 'Dina - 0812-9000-1101', 'plan' => 'Bulanan', 'starts_at' => '2026-07-10', 'paid_at' => '2026-07-09', 'method' => 'bank_transfer', 'payment_notes' => 'Transfer BCA atas nama Raka Pratama.'],
            ['name' => 'Nadia Lestari', 'email' => 'nadia.lestari@example.test', 'phone' => '0812-3100-1002', 'date_of_birth' => '1998-09-24', 'gender' => 'female', 'address' => 'Jl. Kenanga No. 5, Tangerang', 'emergency_contact' => 'Mira - 0812-9000-1102', 'plan' => 'Triwulan', 'starts_at' => '2026-07-11', 'paid_at' => '2026-07-09', 'method' => 'qris', 'payment_notes' => 'QRIS menunggu verifikasi.'],
            ['name' => 'Bima Saputra', 'email' => 'bima.saputra@example.test', 'phone' => '0812-3100-1003', 'date_of_birth' => '1993-12-03', 'gender' => 'male', 'address' => 'Jl. Anggrek No. 42, Bekasi', 'emergency_contact' => 'Reno - 0812-9000-1103', 'plan' => 'Bulanan', 'starts_at' => '2026-07-12', 'paid_at' => '2026-07-09', 'method' => 'cash', 'payment_notes' => 'Tunai diterima front desk.'],
            ['name' => 'Salsa Maharani', 'email' => 'salsa.maharani@example.test', 'phone' => '0812-3100-1004', 'date_of_birth' => '2000-02-18', 'gender' => 'female', 'address' => 'Jl. Cempaka No. 9, Depok', 'emergency_contact' => 'Ayu - 0812-9000-1104', 'plan' => 'Tahunan', 'starts_at' => '2026-07-15', 'paid_at' => '2026-07-09', 'method' => 'bank_transfer', 'payment_notes' => 'Transfer Mandiri paket tahunan.'],
            ['name' => 'Dimas Nugroho', 'email' => 'dimas.nugroho@example.test', 'phone' => '0812-3100-1005', 'date_of_birth' => '1991-06-30', 'gender' => 'male', 'address' => 'Jl. Mawar No. 77, Jakarta', 'emergency_contact' => 'Putri - 0812-9000-1105', 'plan' => 'Triwulan', 'starts_at' => '2026-07-16', 'paid_at' => '2026-07-09', 'method' => 'debit_card', 'payment_notes' => 'Struk kartu debit disimpan offline.'],
            ['name' => 'Clara Wijaya', 'email' => 'clara.wijaya@example.test', 'phone' => '0812-3100-1006', 'date_of_birth' => '1997-08-07', 'gender' => 'female', 'address' => 'Jl. Flamboyan No. 14, Bandung', 'emergency_contact' => 'Hendra - 0812-9000-1106', 'plan' => 'Bulanan', 'starts_at' => '2026-07-17', 'paid_at' => '2026-07-09', 'method' => 'bank_transfer', 'payment_notes' => 'Transfer BNI atas nama Clara.'],
            ['name' => 'Arman Hakim', 'email' => 'arman.hakim@example.test', 'phone' => '0812-3100-1007', 'date_of_birth' => '1989-11-21', 'gender' => 'male', 'address' => 'Jl. Teratai No. 3, Bogor', 'emergency_contact' => 'Sari - 0812-9000-1107', 'plan' => 'Tahunan', 'starts_at' => '2026-07-20', 'paid_at' => '2026-07-09', 'method' => 'bank_transfer', 'payment_notes' => 'Kandidat member paket tahunan.'],
            ['name' => 'Maya Putri', 'email' => 'maya.putri@example.test', 'phone' => '0812-3100-1008', 'date_of_birth' => '1995-03-05', 'gender' => 'female', 'address' => 'Jl. Sakura No. 26, Jakarta', 'emergency_contact' => 'Rani - 0812-9000-1108', 'plan' => 'Bulanan', 'starts_at' => '2026-07-21', 'paid_at' => '2026-07-09', 'method' => 'qris', 'payment_notes' => 'Screenshot QRIS belum diunggah.'],
        ])->each(function (array $transaction) use ($plans, $adminId): void {
            $plan = $plans[$transaction['plan']];

            MembershipTransaction::query()->updateOrCreate(
                [
                    'type' => MembershipTransactionType::Registration->value,
                    'phone' => $transaction['phone'],
                ],
                [
                    'membership_plan_id' => $plan->id,
                    'member_code' => null,
                    'name' => $transaction['name'],
                    'email' => $transaction['email'],
                    'date_of_birth' => $transaction['date_of_birth'],
                    'gender' => $transaction['gender'],
                    'address' => $transaction['address'],
                    'emergency_contact' => $transaction['emergency_contact'],
                    'starts_at' => $transaction['starts_at'],
                    'price' => $plan->price,
                    'amount' => $plan->price,
                    'paid_at' => $transaction['paid_at'],
                    'method' => $transaction['method'],
                    'proof_path' => null,
                    'status' => PaymentStatus::Pending->value,
                    'notes' => 'Seeder transaksi member baru yang masih menunggu verifikasi.',
                    'payment_notes' => $transaction['payment_notes'],
                    'created_by' => $adminId,
                    'verified_by' => null,
                    'verified_at' => null,
                ]
            );
        });
    }
}
