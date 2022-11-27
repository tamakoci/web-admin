<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePaymentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('user_id');
            $table->string('order_no')->unique();
            $table->integer('amount');
            $table->string('desc')->nullable();
            $table->dateTime('expired');
            $table->string('checkout_url');
            $table->integer('status');
            $table->string('trx_no')->nullable();
            $table->dateTime('trx_date')->nullable();
            $table->string('pay_method')->nullable();
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
        Schema::dropIfExists('payments');
    }
}
