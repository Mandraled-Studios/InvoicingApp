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
        Schema::table('invoice_product', function (Blueprint $table) {
            $table->id();
            $table->text('description')->nullable();
            $table->decimal('price_override', 8, 2)->nullable();
            $table->integer('quantity_override')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('invoice_product', function (Blueprint $table) {
            //
        });
    }
};
