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
       
{
    Schema::create('customer', function (Blueprint $table) {
        $table->integer('CUST_ID')->autoIncrement();
        $table->string('ADDRESS', 30)->nullable();
        $table->string('CITY', 20)->nullable();
        $table->string('CUST_TYPE_CD', 1);
        $table->string('FED_ID', 12);
        $table->string('POSTAL_CODE', 10)->nullable();
        $table->string('STATE', 20)->nullable();

        $table->primary('CUST_ID');
    });
}

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('customer');
    }
};
