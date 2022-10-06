<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePakanTernaksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pakan_ternaks', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('ternak_id');
            $table->integer('pakan');
            $table->foreign('ternak_id')
                    ->references('id')
                    ->on('ternaks')
                    ->onUpdate('cascade')
                    ->onDelete('cascade'); 
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
        Schema::dropIfExists('pakan_ternaks');
    }
}
