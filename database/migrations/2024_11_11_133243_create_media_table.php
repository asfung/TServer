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
        Schema::create('media', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->unsignedInteger('index')->nullable();
            $table->string('post_id')->nullable();
            $table->string('user_id')->nullable();
            $table->string('key');
            $table->string('uuid_supabase');
            $table->string('mimetypes');
            $table->string('original_name');
            $table->string('generated_name');
            $table->timestamps();
            $table->timestamp('deleted_at')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('media');
    }
};
