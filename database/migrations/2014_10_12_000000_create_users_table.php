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
        Schema::create('users', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('profile_image')->nullable();
            $table->string('display_name');
            // $table->string('username')->unique();
            $table->string('username')->unique()->check("username NOT LIKE '% %'");
            $table->string('email')->unique();
            $table->string('address')->nullable();
            $table->string('bio')->nullable();
            $table->string('badge')->nullable();
            $table->boolean('banned')->nullable();
            $table->string('password');
            $table->timestamp('email_verified_at')->nullable();
            $table->rememberToken();
            $table->timestamps();
            // $table->foreignId('role_id')->constrained()->onDelete('cascade'); 
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
