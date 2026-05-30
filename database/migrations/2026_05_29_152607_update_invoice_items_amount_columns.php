<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('invoice_items', function (Blueprint $table) {

            $table->decimal('unit_price', 15, 2)->change();

            $table->decimal('line_total', 15, 2)->change();

            $table->decimal('discount', 15, 2)->change();
        });

        Schema::table('invoices', function (Blueprint $table) {

            $table->decimal('subtotal', 15, 2)->change();

            $table->decimal('discount_amount', 15, 2)->change();

            $table->decimal('tax_amount', 15, 2)->change();

            $table->decimal('total_amount', 15, 2)->change();

            $table->decimal('amount_paid', 15, 2)->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('invoice_items', function (Blueprint $table) {

            $table->decimal('unit_price', 8, 2)->change();

            $table->decimal('line_total', 8, 2)->change();

            $table->decimal('discount', 8, 2)->change();
        });

        Schema::table('invoices', function (Blueprint $table) {

            $table->decimal('subtotal', 8, 2)->change();

            $table->decimal('discount_amount', 8, 2)->change();

            $table->decimal('tax_amount', 8, 2)->change();

            $table->decimal('total_amount', 8, 2)->change();

            $table->decimal('amount_paid', 8, 2)->change();
        });
    }
};
