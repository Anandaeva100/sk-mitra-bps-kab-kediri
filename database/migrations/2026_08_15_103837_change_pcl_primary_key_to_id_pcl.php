<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('pcls', function (Blueprint $table) {
            // Hapus primary key lama
            $table->dropPrimary();

            // Hapus kolom id lama
            $table->dropColumn('id');

            // Buat ID PCL sebagai primary key manual
            $table->string('id_pcl', 30)->primary()->first();
        });
    }


    public function down(): void
    {
        /*
        |--------------------------------------------------------------------------
        | Lepaskan primary key dari id_pcl
        |--------------------------------------------------------------------------
        */

        Schema::table('pcls', function (Blueprint $table) {
            $table->dropPrimary();

            $table->dropColumn('id_pcl');

            $table->id();
        });
    }
};