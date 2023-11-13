<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddFieldsInInward extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('inward', function (Blueprint $table) {
            $table->integer('created_by')->after('is_active');
            $table->integer('last_modified_by')->after('created_at');
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
            $table->dropColumn(['created_by']);
            $table->dropColumn(['last_modified_by']);
        });
    }
}
