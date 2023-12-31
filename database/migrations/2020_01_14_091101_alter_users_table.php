<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('company_id', 256)->after('password');
            $table->string('designation', 256)->after('company_id');
            $table->dropColumn(['email_verified_at']);
            $table->integer('role_id')->after('designation');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['company_id']);
            $table->dropColumn(['designation']);
            $table->dropColumn(['role_id']);
            $table->string('email_verified_at');
        });
    }
}
