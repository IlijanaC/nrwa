<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCustomerProductTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('customer_product', function (Blueprint $table) {
            // Promijenjeno iz unsignedInteger u integer kako bi odgovaralo 'CUST_ID' u 'customer' tablici (koji je 'int')
            $table->integer('CUST_ID'); 
            
            // string(10) odgovara varchar(10) u 'product' tablici,
            // a dodana je eksplicitna definicija collationa kako bi se osigurala potpuna kompatibilnost
            $table->string('PRODUCT_CD', 10)->collation('utf8mb4_0900_ai_ci'); 
            $table->timestamps();

            // Definiranje primarnog ključa kao kompozitnog ključa
            $table->primary(['CUST_ID', 'PRODUCT_CD']);

            // Definiranje stranog ključa za CUST_ID
            // Referencira 'CUST_ID' u 'customer' tablici
            // onDelete('cascade') znači da će se redovi u ovoj tablici obrisati ako se obriše referencirani customer
            $table->foreign('CUST_ID')->references('CUST_ID')->on('customer')->onDelete('cascade');
            
            // Definiranje stranog ključa za PRODUCT_CD
            // Referencira 'PRODUCT_CD' u 'product' tablici
            // onDelete('cascade') znači da će se redovi u ovoj tablici obrisati ako se obriše referencirani proizvod
            $table->foreign('PRODUCT_CD')->references('PRODUCT_CD')->on('product')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('customer_product');
    }
}