<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('online_orders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('shop_id')->constrained()->cascadeOnDelete();
            $table->foreignId('customer_id')->nullable()
                  ->constrained()->nullOnDelete();
            $table->string('order_number')->unique();
            $table->string('customer_name');
            $table->string('customer_phone');
            $table->string('customer_email')->nullable();
            $table->string('delivery_address')->nullable();
            $table->decimal('subtotal', 12, 2)->default(0);
            $table->decimal('delivery_fee', 10, 2)->default(0);
            $table->decimal('total_amount', 12, 2)->default(0);
            $table->enum('payment_method', [
                'cash_on_delivery',
                'bank_transfer',
                'card',
                'wallet'
            ])->default('cash_on_delivery');
            $table->enum('payment_status', [
                'pending',
                'paid',
                'failed'
            ])->default('pending');
            $table->enum('status', [
                'new',
                'confirmed',
                'preparing',
                'ready',
                'dispatched',
                'delivered',
                'cancelled'
            ])->default('new');
            $table->text('notes')->nullable();
            $table->json('items');
            $table->timestamp('confirmed_at')->nullable();
            $table->timestamp('delivered_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('online_orders');
    }
};