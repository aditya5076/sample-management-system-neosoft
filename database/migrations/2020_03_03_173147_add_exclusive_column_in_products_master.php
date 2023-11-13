<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddExclusiveColumnInProductsMaster extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('products_master', function (Blueprint $table) {
            $table->integer('is_exclusive')->after('is_Reviewed')->default('0')->comment('0 states not exclusive and 1 states as exclusive');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('products_master', function (Blueprint $table) {
            $table->dropColumn(['is_exclusive']);
        });
    }
}
