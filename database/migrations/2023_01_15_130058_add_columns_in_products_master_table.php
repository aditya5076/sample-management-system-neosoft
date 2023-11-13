<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColumnsInProductsMasterTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('products_master', function (Blueprint $table) {
            $table->string('print_design',64)->after('Composition')->nullable();
            $table->string('print_colorway',16)->after('print_design')->nullable();
            $table->string('print_repeat_inch',64)->after('print_colorway')->nullable();
            $table->string('print_repeat_cm',64)->after('print_repeat_inch')->nullable();
            $table->string('print_category',64)->after('print_repeat_cm')->nullable();
            $table->string('print_type',64)->after('print_category')->nullable();
            $table->decimal('print_cost', 8, 2)->after('print_type')->nullable();
            $table->string('emb_design',64)->after('print_cost')->nullable();
            $table->string('emb_colorway',16)->after('emb_design')->nullable();
            $table->string('emb_repeat_inch',64)->after('emb_colorway')->nullable();
            $table->string('emb_repeat_cm',64)->after('emb_repeat_inch')->nullable();
            $table->string('emb_category',64)->after('emb_repeat_cm')->nullable();
            $table->string('emb_stitch_type',64)->after('emb_repeat_cm')->nullable();
            $table->string('emb_vendor',128)->after('emb_stitch_type')->nullable();
            $table->integer('emb_stitches')->after('emb_vendor')->nullable();
            $table->tinyInteger('emb_applique_work')->after('emb_stitches')->nullable();
            $table->decimal('emb_cost', 8, 2)->after('emb_applique_work')->nullable();
            $table->decimal('emb_gsm', 8, 2)->after('emb_cost')->nullable();
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
            $table->dropColumn('print_design');
            $table->dropColumn('print_colorway');
            $table->dropColumn('print_repeat_inch'); 
            $table->dropColumn('print_repeat_cm');
            $table->dropColumn('print_category');
            $table->dropColumn('print_type');
            $table->dropColumn('print_cost');
            $table->dropColumn('emb_design');
            $table->dropColumn('emb_colorway');
            $table->dropColumn('emb_repeat_inch');
            $table->dropColumn('emb_repeat_cm');
            $table->dropColumn('emb_category'); 
            $table->dropColumn('emb_stitch_type'); 
            $table->dropColumn('emb_vendor');
            $table->dropColumn('emb_stitches');
            $table->dropColumn('emb_applique_work'); 
            $table->dropColumn('emb_cost');
            $table->dropColumn('emb_gsm');
        });
    }
}
