<?php

use App\Enums\PaymentStatus;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('membership_transactions', function (Blueprint $table): void {
            $table->id();
            $table->string('type')->index();
            $table->foreignId('member_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('membership_plan_id')->constrained()->restrictOnDelete();
            $table->foreignId('finalized_membership_id')->nullable()->constrained('memberships')->nullOnDelete();
            $table->string('member_code')->nullable();
            $table->string('name')->nullable()->index();
            $table->string('email')->nullable()->index();
            $table->string('phone')->nullable()->index();
            $table->date('date_of_birth')->nullable();
            $table->string('gender')->nullable();
            $table->text('address')->nullable();
            $table->text('emergency_contact')->nullable();
            $table->date('starts_at');
            $table->decimal('price', 12, 2);
            $table->decimal('amount', 12, 2);
            $table->date('paid_at')->nullable()->index();
            $table->string('method')->default('bank_transfer');
            $table->string('proof_path')->nullable();
            $table->string('status')->default(PaymentStatus::Pending->value)->index();
            $table->text('notes')->nullable();
            $table->text('payment_notes')->nullable();
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('verified_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('verified_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('membership_transactions');
    }
};
