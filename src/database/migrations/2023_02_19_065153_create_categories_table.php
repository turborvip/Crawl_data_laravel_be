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
        if (!Schema::hasTable('categories')) {
            Schema::create('categories', function (Blueprint $table) {
                $table->bigIncrements('id');
                $table->unsignedBigInteger('parent_id')->nullable();
                $table->string('title',500);
<<<<<<< HEAD
                $table->mediumText('description')->nullable();
                $table->boolean('status');
=======
                $table->string('url',500)->nullable();
                $table->mediumText('description')->nullable();
                $table->boolean('status')->default(true);
                $table->string('createBy')->nullable();
                $table->string('updateBy')->nullable();

>>>>>>> ac5b240 (update)
                $table->timestamps();
            });

            Schema::table('categories', function (Blueprint $table) {
                $table->foreign('parent_id')->references('id')->on('categories')->onDelete('cascade');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('categories');
    }
};
