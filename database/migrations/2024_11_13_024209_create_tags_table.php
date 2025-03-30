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
        Schema::create('tags', function (Blueprint $table) {
            $table->uuid('id')->primary();
            // $table->bigInteger('post_id');
            $table->foreignSnowflake('post_id')->constrained()->cascadeOnDelete(); 
            $table->string('tag_name');
            $table->string('tag_formatted');
            // $table->string('type');
            $table->enum('type', ['mention', 'hashtag']);
            $table->timestamps();
            $table->timestamp('deleted_at')->nullable();


        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tags');
    }
};
