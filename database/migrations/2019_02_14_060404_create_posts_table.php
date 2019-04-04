<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePostsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('posts', function (Blueprint $table) {
            $table->increments('id');
            $table->string('title');
            $table->string('code');
            $table->string('thumbnail_url')->nullable();
            $table->string('description', 1000);
            $table->longText('content');
            $table->string('score', 20)->nullable();
            $table->string('priority')->enum(['5', '6', '7', '8', '9'])->comment('5: low, 6: normal, 7: medium, 8: high, 9: very high')->default(6);
            $table->string('tags', 1000)->nullable();
            $table->boolean('status')->comment('1: publish; 2: unpublish')->default(1);
            $table->boolean('show_comment')->comment('1: yes; 2: no')->default(1);
            $table->string('seo_title')->nullable();
            $table->string('seo_keywords', 1000)->nullable();
            $table->string('seo_description', 1000)->nullable();
            $table->unsignedInteger('category_id');
            $table->string('category_liston', 1000)->nullable();
            $table->string('source_name')->nullable();
            $table->string('source_link')->nullable();
            $table->unsignedInteger('user_id');
            $table->timestamp('published_at')->nullable();
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
        Schema::dropIfExists('posts');
    }
}
