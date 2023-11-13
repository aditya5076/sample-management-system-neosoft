<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProductPricingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('products_pricing', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('unique_sku_id',50);
            $table->decimal('P1', 8, 2)->nullable();
            $table->decimal('P2', 8, 2)->nullable();
            $table->decimal('P3', 8, 2)->nullable();
            $table->decimal('P4', 8, 2)->nullable();
            $table->decimal('P5', 8, 2)->nullable();
            $table->decimal('P6', 8, 2)->nullable();
            $table->decimal('P7', 8, 2)->nullable();
            $table->decimal('rupee_multiplier', 8, 2)->nullable();
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
        Schema::dropIfExists('products_pricing');
    }
}
