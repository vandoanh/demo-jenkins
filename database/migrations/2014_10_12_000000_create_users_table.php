<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

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
            $table->increments('id');
            $table->string('email');
            $table->string('password')->nullable();
            $table->string('fullname', 100);
            $table->string('description', 1000)->nullable();
            $table->string('avatar');
            $table->boolean('gender')->comment('0: no select; 1: male; 2: female')->nullable()->default(0);
            $table->dateTime('birthday')->nullable();
            $table->boolean('receive_notification')->default(0);
            $table->string('timezone', 100)->default('Asia/Ho_Chi_Minh');
            $table->boolean('user_type')->default(2)->comment('1: admin: 2: member');
            $table->boolean('status')->default(2)->comment('1: active; 2: inactive; 3: banned');
            $table->rememberToken();
            $table->timestamps();
            $table->softDeletes();
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
