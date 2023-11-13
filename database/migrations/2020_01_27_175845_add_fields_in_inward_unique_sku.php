<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddFieldsInInwardUniqueSku extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('inward', function (Blueprint $table) {
            $table->string('unique_sku_id',255)->after('request_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('inward', function (Blueprint $table) {
            $table->dropColumn(['unique_sku_id']);
        });
    }
}
