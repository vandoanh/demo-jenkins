<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateNoticesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('notices', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('user_id');
            $table->string('title');
            $table->text('content');
            $table->text('content_chatwork')->nullable();
            $table->boolean('push_notification')->comment('1: yes; 2: no')->default(2);
            $table->boolean('push_chatwork')->comment('1: yes; 2: no')->default(2);
            $table->boolean('status')->comment('1: publish; 2: unpublish')->default(1);
            $table->timestamp('published_at');
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
        Schema::dropIfExists('notices');
    }
}
