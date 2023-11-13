<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddNewFieldsToProductsMaster extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('products_master', function (Blueprint $table) {
            $table->string('FabricType',100)->after('product_type')->nullable();
            $table->string('WeaveType',100)->after('FabricType')->nullable();
            $table->dropColumn(['cost']);
            $table->dropColumn(['price']);
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
            $table->dropColumn(['FabricType']);
            $table->dropColumn(['WeaveType']);
        });
    }
}
