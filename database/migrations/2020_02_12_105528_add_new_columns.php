<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddNewColumns extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('cart', function (Blueprint $table) {
            $table->decimal('productprice', 8, 2);
            $table->string('productnote',256);
            $table->string('ordernote',256);
            $table->integer('commitment');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('cart', function (Blueprint $table) {
            $table->dropColumn(['productprice']);
            $table->dropColumn(['productnote']);
            $table->dropColumn(['ordernote']);
            $table->dropColumn(['commitment']);
        });
    }
}
