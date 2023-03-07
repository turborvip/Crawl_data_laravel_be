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
        if (!Schema::hasTable('news')) {
            Schema::create('news', function (Blueprint $table) {
                $table->bigIncrements('id');
                $table->string('caption',500);
                $table->string('image',255);
<<<<<<< HEAD
                $table->mediumText('description');
                $table->longText('content');
                $table->string('author',255);
                $table->bigInteger('viewDaily');
                $table->bigInteger('viewWeekly');
                $table->boolean('status')->default(true);;
=======
                $table->mediumText('description')->nullable();;
                $table->longText('content');
                $table->string('author',255)->nullable();;
                $table->bigInteger('viewHour')->default(0);
                $table->bigInteger('viewDaily')->default(0);
                $table->boolean('status')->default(true);
                $table->string('createBy')->nullable();
                $table->string('updateBy')->nullable();
>>>>>>> ac5b240 (update)
                $table->timestamps();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('news');
    }
};
