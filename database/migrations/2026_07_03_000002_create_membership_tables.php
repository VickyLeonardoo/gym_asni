<?php

use App\Enums\MembershipStatus;
use App\Enums\PaymentStatus;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('membership_plans', function (Blueprint $table): void {
            $table->id();
            $table->string('name')->unique();
            $table->unsignedSmallInteger('duration_days');
            $table->decimal('price', 12, 2);
            $table->text('description')->nullable();
            $table->boolean('is_active')->default(true)->index();
            $table->timestamps();
        });

        Schema::create('members', function (Blueprint $table): void {
            $table->id();
            $table->string('member_code')->unique();
            $table->string('name')->index();
            $table->string('email')->nullable()->index();
            $table->string('phone')->index();
            $table->date('date_of_birth')->nullable();
            $table->enum('gender', ['male', 'female', 'other'])->nullable();
            $table->text('address')->nullable();
            $table->text('emergency_contact')->nullable();
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('updated_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('memberships', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('member_id')->constrained()->cascadeOnDelete();
            $table->foreignId('membership_plan_id')->constrained()->restrictOnDelete();
            $table->date('starts_at')->index();
            $table->date('expires_at')->index();
            $table->decimal('price', 12, 2);
            $table->string('status')->default(MembershipStatus::Active->value)->index();
            $table->text('notes')->nullable();
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
            $table->softDeletes();
            $table->index(['member_id', 'status', 'expires_at']);
        });

        Schema::create('membership_payments', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('membership_id')->constrained()->cascadeOnDelete();
            $table->decimal('amount', 12, 2);
            $table->date('paid_at')->nullable()->index();
            $table->string('method')->default('bank_transfer');
            $table->string('proof_path')->nullable();
            $table->string('status')->default(PaymentStatus::Pending->value)->index();
            $table->text('notes')->nullable();
            $table->foreignId('uploaded_by')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('verified_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('verified_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('membership_payments');
        Schema::dropIfExists('memberships');
        Schema::dropIfExists('members');
        Schema::dropIfExists('membership_plans');
    }
};
