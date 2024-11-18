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
        Schema::table('media', function (Blueprint $table) {
            $table->string('walaweh')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('media', function (Blueprint $table) {
        });
    }
};

// php artisan make:migration add_to_media_table
/*

TEMPLATE FOR ADDING NEW FIELD IN LARAVEL 

$ php artisan make:migration XXXX_to_NAME_TABLE_table

XXXX mean is whatever
NAME_TABLE mean is depends on yours


*/