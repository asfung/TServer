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
        Schema::create('posts', function (Blueprint $table) {
            // $table->snowflake()->id();
            $table->snowflake()->primary();
            $table->uuid('user_id');
            $table->text('content')->nullable();
            $table->string('parent_id')->nullable();
            // $table->string('liked_count')->nullable();
            // $table->string('replied_count')->nullable();
            // $table->string('reposted_count')->nullable();
            $table->string('community_id')->nullable();
            $table->timestamp('deleted_at')->nullable();
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('posts');
    }
};
