<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColumnsInRequestsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('requests', function (Blueprint $table) {
            $table->string('print_design',64)->after('request_type')->nullable();
            $table->string('print_colorway',16)->after('print_design')->nullable();
            $table->string('emb_design',64)->after('print_colorway')->nullable();
            $table->string('emb_colorway',16)->after('request_type')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('requests', function (Blueprint $table) {
            $table->dorpColumn('print_design');
            $table->dorpColumn('print_colorway');
            $table->dorpColumn('emb_design');
            $table->dorpColumn('emb_colorway');
        });
    }
}
