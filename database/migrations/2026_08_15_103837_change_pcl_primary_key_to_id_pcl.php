<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // 1. Lepas sifat AUTO_INCREMENT pada kolom 'id' lama terlebih dahulu
        if (Schema::hasColumn('pcls', 'id')) {
            Schema::table('pcls', function (Blueprint $table) {
                $table->unsignedBigInteger('id')->change();
            });

            // 2. Hapus Primary Key & Kolom 'id' lama
            Schema::table('pcls', function (Blueprint $table) {
                $table->dropPrimary('id');
                $table->dropColumn('id');
            });
        }

        // 3. Ubah kolom 'id_pcl' yang SUDAH ADA menjadi Primary Key
        if (Schema::hasColumn('pcls', 'id_pcl')) {
            Schema::table('pcls', function (Blueprint $table) {
                $table->string('id_pcl', 30)->primary()->change();
            });
        }
    }

    public function down(): void
    {
        Schema::table('pcls', function (Blueprint $table) {
            $table->dropPrimary(['id_pcl']);
            if (!Schema::hasColumn('pcls', 'id')) {
                $table->bigIncrements('id')->first();
            }
        });
    }
};