<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class RenameExistingTwoColumns extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('products_master', function (Blueprint $table) {
            $table->renameColumn('FabricType', 'fabric_type');
            $table->renameColumn('WeaveType', 'weave_type');
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
            //
        });
    }
}
