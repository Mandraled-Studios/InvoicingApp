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
        Schema::table('products', function (Blueprint $table) {
            $table->timestamp('subscription_ends_at')->nullable()->after('is_active');
            $table->string('payment_frequency')->nullable()->after('is_active');
            $table->boolean('subscription_product')->nullable()->default(false)->after('is_active');         
            $table->boolean('is_free')->nullable()->default(false)->after('is_active');
            $table->decimal('discount', 8, 2)->nullable()->default(0)->after('is_active');
            $table->boolean('is_tax_exempted')->nullable()->default(false)->after('is_active');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('product', function (Blueprint $table) {
            //
        });
    }
};
