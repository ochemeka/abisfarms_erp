<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('kitchen_orders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('sale_id')->constrained()->cascadeOnDelete();
            $table->foreignId('shop_id')->constrained()->cascadeOnDelete();
            $table->foreignId('taken_by')->constrained('users');
            $table->integer('table_number')->nullable();
            $table->string('customer_name')->nullable();
            $table->enum('status', [
                'pending',
                'cooking',
                'ready',
                'dispatched',
                'cancelled'
            ])->default('pending');
            $table->text('notes')->nullable();
            $table->timestamp('fired_at')->useCurrent();
            $table->timestamp('ready_at')->nullable();
            $table->timestamp('dispatched_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('kitchen_orders');
    }
};