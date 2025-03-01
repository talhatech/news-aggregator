<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('articles', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('source_id')->nullable();
            $table->uuid('platform_id')->nullable();
            $table->uuid('category_id')->nullable();
            $table->string('external_id')->nullable();
            $table->string('author')->nullable();
            $table->string('title');
            $table->text('description')->nullable();
            $table->string('url')->unique();
            $table->text('image_url')->nullable();
            $table->longText('content')->nullable();
            $table->timestamp('published_at')->nullable();
            $table->timestamps();
            $table->softDeletes();

            // Foreign keys
            $table->foreign('source_id')->references('id')->on('sources');
            $table->foreign('platform_id')->references('id')->on('platforms');
            $table->foreign('category_id')->references('id')->on('categories');

            // Indexes for faster searching
            $table->index('published_at');
            $table->index(['title']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::dropIfExists('articles');
    }
};
