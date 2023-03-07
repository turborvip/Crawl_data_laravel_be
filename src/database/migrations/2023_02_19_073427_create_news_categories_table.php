<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        if (!Schema::hasTable('news_categories')) {
            Schema::create('news_categories', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('news_id');
                $table->unsignedBigInteger('category_id');
<<<<<<< HEAD
                $table->timestamps();

                $table->foreign('news_id')->references('id')->on('news')->onDelete('cascade');
                $table->foreign('category_id')->references('id')->on('categories')->onDelete('cascade');
=======
        
                $table->timestamps();

                $table->foreign('category_id')->references('id')->on('categories')->onDelete('cascade');
                $table->foreign('news_id')->references('id')->on('news')->onDelete('cascade');

                $table->unique(['category_id', 'news_id']);
>>>>>>> ac5b240 (update)
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('news_categories');
    }
};
