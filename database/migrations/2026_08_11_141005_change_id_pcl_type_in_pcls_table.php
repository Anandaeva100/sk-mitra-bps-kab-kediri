<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('pcls', function (Blueprint $table) {

            $table->string('id_pcl', 30)->change(); 
        });
    }

    public function down(): void
    {
        Schema::table('pcls', function (Blueprint $table) {
            $table->integer('id_pcl')->change();
        });
    }
};