<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('supply_batches', function (Blueprint $table) {
            $table->id();
            $table->foreignId('shop_id')->constrained()->cascadeOnDelete();
            $table->foreignId('supplier_id')->constrained()->cascadeOnDelete();
            $table->string('batch_code')->unique();
            $table->string('batch_label');
            $table->date('batch_date');
            $table->enum('status', ['draft','receiving','active','closed'])
                  ->default('draft');
            $table->decimal('total_cost', 15, 2)->default(0);
            $table->decimal('amount_paid', 15, 2)->default(0);
            $table->decimal('balance_due', 15, 2)->default(0);
            $table->enum('payment_status', ['unpaid','partial','paid'])
                  ->default('unpaid');
            $table->date('payment_due_date')->nullable();
            $table->text('notes')->nullable();
            $table->foreignId('received_by')->nullable()
                  ->constrained('users')->nullOnDelete();
            $table->timestamp('activated_at')->nullable();
            $table->timestamps();
            $table->softDeletes();
            $table->index(['shop_id', 'status']);
            $table->index(['shop_id', 'supplier_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('supply_batches');
    }
};
