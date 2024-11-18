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
        Schema::table('users', function (Blueprint $table) {
            //
            $table->string('address')->nullable();
            $table->string('bio')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $this->dropColumnIfExists('users', 'address');
            $this->dropColumnIfExists('users', 'bio');
        });
    }

    public function dropColumnIfExists($table, $column)
    {
        if (Schema::hasColumn($table, $column)) 
        {
            Schema::table($table, function (Blueprint $table) use ($column)
            {
                $table->dropColumn($column); 
            });
        }
    
    }
    
};
