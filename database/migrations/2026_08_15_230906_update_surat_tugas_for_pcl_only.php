<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('surat_tugas', function (Blueprint $table) {
            $table->dropColumn('jenis_mitra');
            $table->renameColumn('nama_mitra', 'nama_pcl');
        });
    }

    public function down(): void
    {
        Schema::table('surat_tugas', function (Blueprint $table) {
            $table->renameColumn('nama_pcl', 'nama_mitra');
            $table->string('jenis_mitra')->after('nama_survei');
        });
    }
};