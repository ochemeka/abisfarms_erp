<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('batch_products', function (Blueprint $table) {
            $table->id();
            $table->foreignId('batch_id')
                  ->constrained('supply_batches')->cascadeOnDelete();
            $table->foreignId('batch_item_id')
                  ->constrained('supply_batch_items')->cascadeOnDelete();
            $table->foreignId('shop_id')->constrained()->cascadeOnDelete();
            $table->string('animal_type');
            $table->string('part_name');
            // "Cow Head [Abdul-A]" — shown to staff in POS/Invoice
            $table->string('display_name');
            // "Cow Head" — shown on customer receipt/invoice
            $table->string('receipt_name');
            $table->enum('pricing_type', ['fixed', 'weight'])->default('fixed');
            $table->string('unit')->default('piece');
            // Stock tracking
            $table->decimal('quantity_available', 12, 3)->default(0);
            $table->decimal('quantity_sold', 12, 3)->default(0);
            $table->decimal('quantity_wasted', 12, 3)->default(0);
            // Pricing
            $table->decimal('target_price', 12, 2)->default(0);
            $table->decimal('min_price', 12, 2)->nullable();
            // Cost allocation per unit sold
            $table->decimal('cost_allocation_per_unit', 12, 4)->default(0);
            // Running revenue total (updated on each sale)
            $table->decimal('total_revenue', 15, 2)->default(0);
            $table->boolean('is_active')->default(true);
            $table->text('notes')->nullable();
            $table->timestamps();
            $table->softDeletes();
            $table->index(['shop_id', 'is_active']);
            $table->index(['batch_id', 'animal_type']);
            $table->index(['shop_id', 'batch_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('batch_products');
    }
};
