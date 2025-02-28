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
            $table->id();
            $table->string('source');
            $table->string('source_name');
            $table->string('author')->nullable();
            $table->string('title');
            $table->text('description')->nullable();
            $table->string('url')->unique();
            $table->text('image_url')->nullable();
            $table->timestamp('published_at')->nullable();
            $table->text('content')->nullable();
            $table->string('category')->nullable();
            $table->string('external_id')->nullable();
            $table->timestamps();

            // Indexes for faster searching
            $table->index('source');
            $table->index('category');
            $table->index('published_at');
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
