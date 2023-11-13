<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddIndexingToProductsPricing extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('products_pricing', function (Blueprint $table) {
            $table->index('unique_sku_id');
            $table->index('is_latest');
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
            $table->dropIndex(['unique_sku_id']);
            $table->dropIndex(['is_latest']);
        });
    }
}
