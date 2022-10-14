<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('email')->unique()->nullable();
            $table->string('username')->unique();
            $table->string('phone',15)->unique();
            $table->string('password');
            $table->string('avatar')->nullable();
            $table->boolean('active_tutor')->default(1);
            $table->bigInteger('ref_to')->nullable();
            $table->string('user_ref')->nullable();
            $table->bigInteger('user_role')->default(1);
            $table->boolean('status')->default(1);
            $table->rememberToken();
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
        Schema::dropIfExists('users');
    }
}
