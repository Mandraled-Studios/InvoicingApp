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
        Schema::disableForeignKeyConstraints();

        Schema::create('invoices', function (Blueprint $table) {
            $table->id();
            $table->string('invoice_number', 64);
            $table->timestamp('invoice_date');
            $table->timestamp('invoice_duedate')->nullable();
            $table->boolean('taxes_for_line_item')->default(false);
            $table->decimal('invoice_subtotal', 8, 2)->default(0);
            $table->string('tax1_label')->nullable();
            $table->decimal('tax1_value', 8, 2)->default(0)->nullable();
            $table->string('tax2_label')->nullable();
            $table->decimal('tax2_value', 8, 2)->default(0)->nullable();
            $table->decimal('round_off', 8, 2)->default(0);
            $table->decimal('discount_value', 8, 2)->default(0);
            $table->string('discount_type');
            $table->decimal('invoice_total', 8, 2)->default(0);
            $table->decimal('paid_to_date', 8, 2)->default(0);
            $table->decimal('balance_due', 8, 2)->default(0);
            $table->foreignId('client_id');
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::enableForeignKeyConstraints();
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('invoices');
    }
};
