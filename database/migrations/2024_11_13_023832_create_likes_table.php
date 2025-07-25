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
        Schema::create('likes', function (Blueprint $table) {
            $table->uuid('id')->primary();
            // $table->bigInteger('post_id');
            $table->foreignSnowflake('post_id')->constrained()->cascadeOnDelete(); 
            $table->uuid('user_id');
            $table->timestamps();
            $table->timestamp('deleted_at')->nullable();
            $table->unique(['post_id', 'user_id']);

            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('likes');
    }
};
