<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('monitoring_surveys', function (Blueprint $table) {
            // Menyisipkan kolom bulan dan nama_kegiatan tepat di bawah kolom user_id awal Anda
            $table->string('bulan')->after('user_id')->nullable();
            $table->string('nama_kegiatan')->after('bulan')->nullable();
        });
    }

    public function down(): void
    {
        Schema::table('monitoring_surveys', function (Blueprint $table) {
            $table->dropColumn(['bulan', 'nama_kegiatan']);
        });
    }
};
