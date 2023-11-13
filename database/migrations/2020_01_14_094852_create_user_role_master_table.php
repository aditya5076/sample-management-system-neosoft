<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUserRoleMasterTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_role_master', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('role_name', 256);
            $table->string('short_code', 100);
            $table->integer('is_active')->default('1')->comment('0 states inactive role and 1 states active role');
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
        Schema::dropIfExists('user_role_master');
    }
}
