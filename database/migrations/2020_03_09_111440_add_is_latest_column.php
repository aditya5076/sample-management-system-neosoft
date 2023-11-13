<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddIsLatestColumn extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('products_pricing', function (Blueprint $table) {
            $table->integer('is_latest')->after('rupee_multiplier')->default('0')->comment('1 indicates as latest column');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('products_pricing', function (Blueprint $table) {
            $table->dropColumn(['is_latest']);
        });
    }
}
