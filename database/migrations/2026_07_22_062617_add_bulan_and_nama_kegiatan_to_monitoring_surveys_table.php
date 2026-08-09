<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('monitoring_surveys', function (Blueprint $table) {
            if (!Schema::hasColumn('monitoring_surveys', 'bulan')) {
                $table->string('bulan')->nullable();
            }
            if (!Schema::hasColumn('monitoring_surveys', 'nama_kegiatan')) {
                $table->string('nama_kegiatan')->nullable();
            }
        });
    }

    public function down(): void
    {
        Schema::table('monitoring_surveys', function (Blueprint $table) {
            if (Schema::hasColumn('monitoring_surveys', 'bulan')) {
                $table->dropColumn('bulan');
            }
            if (Schema::hasColumn('monitoring_surveys', 'nama_kegiatan')) {
                $table->dropColumn('nama_kegiatan');
            }
        });
    }
};