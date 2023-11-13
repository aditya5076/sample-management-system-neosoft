<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOutwardsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('outward', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('request_id');
            $table->string('issued_to',200);
            $table->decimal('issued_quantity', 8, 2);
            $table->dateTime('issued_date');
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
        Schema::dropIfExists('outward');
    }
}
