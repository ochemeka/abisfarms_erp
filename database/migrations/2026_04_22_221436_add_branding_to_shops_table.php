<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('shops', function (Blueprint $table) {
            $table->string('logo_path')->nullable()->after('email');
            $table->string('tagline')->nullable()->after('logo_path');
            $table->string('address_full')->nullable()->after('tagline');
            $table->string('bank_name')->nullable()->after('address_full');
            $table->string('bank_account')->nullable()->after('bank_name');
            $table->string('bank_account_name')->nullable()->after('bank_account');
            $table->string('invoice_prefix')->default('INV')->after('bank_account_name');
            $table->text('invoice_footer')->nullable()->after('invoice_prefix');
            $table->decimal('default_tax_rate', 5, 2)->default(0)->after('invoice_footer');
        });
    }

    public function down(): void
    {
        Schema::table('shops', function (Blueprint $table) {
            $table->dropColumn([
                'logo_path', 'tagline', 'address_full',
                'bank_name', 'bank_account', 'bank_account_name',
                'invoice_prefix', 'invoice_footer', 'default_tax_rate',
            ]);
        });
    }
};