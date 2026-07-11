<?php

use App\Enums\AssetCondition;
use App\Enums\AssetStatus;
use App\Enums\MaintenanceStatus;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('purchase_stores', function (Blueprint $table): void {
            $table->id();
            $table->string('name')->unique();
            $table->string('phone')->nullable();
            $table->string('email')->nullable();
            $table->text('address')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('suppliers', function (Blueprint $table): void {
            $table->id();
            $table->string('name')->unique();
            $table->string('phone')->nullable();
            $table->string('email')->nullable();
            $table->text('address')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('service_contacts', function (Blueprint $table): void {
            $table->id();
            $table->string('name')->unique();
            $table->string('phone')->nullable();
            $table->string('email')->nullable();
            $table->text('address')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('assets', function (Blueprint $table): void {
            $table->id();
            $table->string('asset_code')->unique();
            $table->string('name')->index();
            $table->string('category')->index();
            $table->string('brand')->nullable();
            $table->string('model')->nullable();
            $table->string('serial_number')->nullable()->unique();
            $table->foreignId('purchase_store_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('supplier_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('service_contact_id')->nullable()->constrained()->nullOnDelete();
            $table->date('purchase_date')->nullable()->index();
            $table->decimal('purchase_price', 12, 2)->default(0);
            $table->date('warranty_expires_at')->nullable()->index();
            $table->string('status')->default(AssetStatus::Available->value)->index();
            $table->string('condition')->default(AssetCondition::Good->value)->index();
            $table->string('location')->nullable()->index();
            $table->text('notes')->nullable();
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('asset_maintenances', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('asset_id')->constrained()->cascadeOnDelete();
            $table->foreignId('service_contact_id')->nullable()->constrained()->nullOnDelete();
            $table->date('scheduled_at')->index();
            $table->date('completed_at')->nullable()->index();
            $table->string('status')->default(MaintenanceStatus::Scheduled->value)->index();
            $table->decimal('cost', 12, 2)->default(0);
            $table->text('description');
            $table->text('resolution')->nullable();
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('asset_condition_logs', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('asset_id')->constrained()->cascadeOnDelete();
            $table->string('condition')->index();
            $table->text('notes')->nullable();
            $table->foreignId('reported_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('asset_condition_logs');
        Schema::dropIfExists('asset_maintenances');
        Schema::dropIfExists('assets');
        Schema::dropIfExists('service_contacts');
        Schema::dropIfExists('suppliers');
        Schema::dropIfExists('purchase_stores');
    }
};
