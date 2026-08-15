<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('surat_tugas', function (Blueprint $table) {
            $table->id();

            $table->string('nomor_surat');

            $table->string('nama_survei');

            $table->string('jenis_mitra');

            $table->string('nama_mitra');

            $table->text('wilayah_tugas');

            $table->string('waktu_tugas');

            $table->date('tanggal_surat');

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('surat_tugas');
    }
};