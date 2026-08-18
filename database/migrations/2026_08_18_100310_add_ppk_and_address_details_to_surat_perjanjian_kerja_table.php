<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('surat_perjanjian_kerja', function (Blueprint $table) {
            $table->string('nama_ppk')->nullable()->after('nomor_spk');
            // 1 kolom teks untuk alamat lengkap PCL
            $table->text('alamat_pcl')->nullable()->after('survey_activity_id');
        });
    }

    public function down(): void
    {
        Schema::table('surat_perjanjian_kerja', function (Blueprint $table) {
            $table->dropColumn(['nama_ppk', 'alamat_pcl']);
        });
    }
};