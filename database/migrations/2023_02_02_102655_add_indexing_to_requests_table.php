<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddIndexingToRequestsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('requests', function (Blueprint $table) {
            $table->index('print_design');
            $table->index('print_colorway');
            $table->index('emb_design');
            $table->index('emb_colorway');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('requests', function (Blueprint $table) {
            $table->dropIndex(['print_design']);
            $table->dropIndex(['print_colorway']);
            $table->dropIndex(['emb_design']);
            $table->dropIndex(['emb_colorway']);
        });
    }
}
