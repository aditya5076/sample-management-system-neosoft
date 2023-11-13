<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateReportsMastersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('reports_master', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('report_name',100);
            $table->text('report_description')->nullable();
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
        Schema::dropIfExists('reports_master');
    }
}
