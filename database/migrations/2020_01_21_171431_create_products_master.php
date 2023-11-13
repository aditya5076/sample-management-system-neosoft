<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProductsMaster extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('products_master', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('mssql_products_id');
            $table->string('unique_sku_id',50);
            $table->string('quality',200)->nullable();
            $table->string('design_name',200)->nullable();
            $table->string('shade',200)->nullable();
            $table->integer('epi_on_loom')->nullable();
            $table->integer('ppi_on_loom')->nullable();
            $table->integer('epi_finish')->nullable();
            $table->integer('ppi_finish')->nullable();
            $table->decimal('gsm', 8, 2)->nullable();
            $table->decimal('glm', 8, 2)->nullable();
            $table->decimal('cost', 8, 2)->nullable();
            $table->decimal('price', 8, 2)->nullable();
            $table->string('designer',200)->nullable();
            $table->string('end_use',200)->nullable();
            $table->string('product_type',200)->nullable();
            $table->string('category',200)->nullable();
            $table->string('repeat_inch',200)->nullable();
            $table->string('repeat_cm',200)->nullable();
            $table->string('finish_width',200)->nullable();
            $table->integer('repeats_horizontal')->nullable();
            $table->integer('repeats_vertical')->nullable();
            $table->string('color',200)->nullable();
            $table->string('design',200)->nullable();
            $table->integer('is_active')->default('1')->comment('0 states inactive product and 1 states active product');
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
        Schema::dropIfExists('products_master');
    }
}
