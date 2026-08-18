<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Penyesuaian ke nama tabel yang benar: surat_perjanjian_kerja
        Schema::table('surat_perjanjian_kerja', function (Blueprint $table) {
            $table->text('uraian_tugas')->nullable();
            $table->string('satuan')->nullable();
        });

        Schema::table('survey_activities', function (Blueprint $table) {
            $table->text('uraian_tugas')->nullable();
            $table->string('satuan')->nullable()->default('Segmen');
        });
    }

    public function down(): void
    {
        Schema::table('surat_perjanjian_kerja', function (Blueprint $table) {
            $table->dropColumn(['uraian_tugas', 'satuan']);
        });

        Schema::table('survey_activities', function (Blueprint $table) {
            $table->dropColumn(['uraian_tugas', 'satuan']);
        });
    }
};