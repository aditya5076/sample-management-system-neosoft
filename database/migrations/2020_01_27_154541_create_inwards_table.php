<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateInwardsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('inward', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('request_id');
            $table->integer('location_id');
            $table->decimal('quantity', 8, 2);
            $table->text('remarks');
            $table->integer('is_active')->default('1')->comment('0 states inactive and 1 states active');
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
        Schema::dropIfExists('inward');
    }
}
