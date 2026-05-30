<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('sales', function (Blueprint $table) {
            $table->id();
            $table->foreignId('shop_id')->constrained()->cascadeOnDelete();
            $table->foreignId('till_session_id')->nullable()
                  ->constrained()->nullOnDelete();
            $table->foreignId('served_by')->constrained('users');
            $table->foreignId('collected_by')->nullable()
                  ->constrained('users')->nullOnDelete();
            $table->foreignId('customer_id')->nullable()
                  ->constrained('users')->nullOnDelete();
            $table->string('receipt_number')->unique();
            $table->decimal('subtotal', 10, 2);
            $table->decimal('discount_amount', 10, 2)->default(0);
            $table->decimal('tax_amount', 10, 2)->default(0);
            $table->decimal('total_amount', 10, 2);
            $table->decimal('amount_paid', 10, 2)->default(0);
            $table->decimal('change_given', 10, 2)->default(0);
            $table->enum('payment_method', [
                'cash', 'card', 'transfer', 'split', 'credit'
            ])->default('cash');
            $table->enum('status', [
                'completed', 'pending', 'refunded', 'voided'
            ])->default('completed');
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('sales');
    }
};