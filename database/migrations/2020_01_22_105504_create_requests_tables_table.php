<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRequestsTablesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('requests', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('mssql_requests_id');
            $table->string('request_no',128)->nullable();
            $table->string('unique_sku_id',50)->nullable();
            $table->string('quality_name',128)->nullable();
            $table->string('design_name',128)->nullable();
            $table->string('shade_name',128)->nullable();
            $table->string('requirement',128)->nullable();
            $table->string('barcode',128)->nullable();
            $table->date('delivery_date')->nullable();
            $table->string('designer_name',128)->nullable();
            $table->decimal('sample_length', 8, 2)->nullable();
            $table->date('request_date')->nullable();
            $table->string('request_type',128)->nullable();
            $table->date('due_date')->nullable();
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
        Schema::dropIfExists('requests');
    }
}
