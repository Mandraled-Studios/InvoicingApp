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

        Schema::create('addresses', function (Blueprint $table) {
            $table->id();
            $table->string('building_number', 64);
            $table->string('street_address');
            $table->string('location');
            $table->foreignId('city_id')->nullable()->constrained();
            $table->foreignId('state_id')->constrained();
            $table->foreignId('country_id')->constrained();
            $table->string('zipcode', 20);
            //$table->foreignId('client_id');
            $table->timestamps();
        });

        Schema::enableForeignKeyConstraints();
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('addresses');
    }
};
