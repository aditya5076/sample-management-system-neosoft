<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCustomerManagementTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('customer_management', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('customer_name',100)->nullable();
            $table->string('country',100)->nullable();
            $table->string('contact_person',100)->nullable();
            $table->string('email',100)->unique();
            $table->string('contact_number',100)->nullable();
            $table->text('payment_terms')->nullable();
            $table->integer('created_by')->default(0);
            $table->integer('last_modified_by')->default(0);
            $table->integer('is_active')->default('1')->comment('0 states inactive customer and 1 states active customer');
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
        Schema::dropIfExists('customer_management');
    }
}
