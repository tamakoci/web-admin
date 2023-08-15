<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTernaksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ternaks', function (Blueprint $table) {
            $table->id();
            $table->string('name',100);
            $table->integer('price');
            $table->integer('duration');
            $table->unsignedBigInteger('produk_id');
            $table->string('avatar')->nullable();
            $table->boolean('status')->default(true);
            $table->timestamps();
            $table->foreign('produk_id')
                    ->references('id')
                    ->on('products')
                    ->onUpdate('cascade')
                    ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('ternaks');
    }
}
