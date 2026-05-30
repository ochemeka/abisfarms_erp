<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('batch_product_sales', function (Blueprint $table) {
            $table->id();
            $table->foreignId('batch_product_id')
                  ->constrained()->cascadeOnDelete();
            $table->foreignId('batch_id')
                  ->constrained('supply_batches');
            $table->foreignId('shop_id')->constrained();
            // Polymorphic: Sale or Invoice
            $table->string('saleable_type');
            $table->unsignedBigInteger('saleable_id');
            $table->decimal('quantity', 12, 3);
            $table->decimal('unit_price', 12, 2);
            $table->decimal('line_total', 12, 2);
            $table->decimal('cost_allocated', 12, 4)->default(0);
            $table->decimal('gross_profit', 12, 4)->default(0);
            $table->foreignId('sold_by')->constrained('users');
            $table->timestamp('sold_at');
            $table->timestamps();
            $table->index(['batch_id']);
            $table->index(['batch_product_id']);
            $table->index(['saleable_type', 'saleable_id']);
            $table->index(['shop_id', 'sold_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('batch_product_sales');
    }
};
