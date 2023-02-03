<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTableInventoryProducts extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('inventory_products', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger("inventory_id");
            $table->foreign('inventory_id')->references('id')->on('inventory');
            $table->unsignedInteger("product_id");
            $table->foreign('product_id')->references('id')->on('products');
            $table->unsignedBigInteger("amount_after_inventory")->default(0);
            $table->integer("Amount_difference")->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('inventory_products');
    }
}
