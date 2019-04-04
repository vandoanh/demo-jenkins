<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCommentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('comments', function (Blueprint $table) {
            $table->increments('id');
            $table->text('content');
            $table->unsignedInteger('parent_id')->default(0);
            $table->unsignedInteger('user_id');
            $table->unsignedInteger('post_id');
            $table->integer('total_like')->default(0);
            $table->integer('total_dislike')->default(0);
            $table->boolean('status')->comment('1: publish; 2: unpublish')->default(1);
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
        Schema::dropIfExists('comments');
    }
}
