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
        Schema::table('clients', function (Blueprint $table) {
            $table->string('client_number', 64)->after('id')->nullable();
            $table->decimal('paid_to_date', 8, 2)->after('firm_type')->nullable();
            $table->decimal('balance', 8, 2)->after('firm_type')->nullable();
            $table->decimal('credit_balance', 8, 2)->after('firm_type')->nullable();
            $table->string('default_currency', 5)->after('firm_type')->default('INR')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('clients', function (Blueprint $table) {
            $table->dropColumn('client_number');
            $table->dropColumn('paid_to_date');
            $table->dropColumn('balance');
            $table->dropColumn('credit_balance');
            $table->dropColumn('default_currency');
        });
    }
};
