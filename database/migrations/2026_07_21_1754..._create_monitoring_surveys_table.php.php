<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('monitoring_surveys', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade'); 
            
            // Kolom Tambahan untuk Filter Navigasi
            $table->string('bulan');                // Menyimpan pilihan bulan
            $table->string('nama_kegiatan');         // Menyimpan pilihan kegiatan
            
            // Kolom Inti Data
            $table->string('nama_pml');             
            $table->string('nama_pcl');             
            $table->integer('beban_banyak');        
            $table->decimal('rate_honor', 15, 2);   
            
            // Hitung Otomatis Honor Total
            $table->decimal('honor_total', 15, 2)->storedAs('beban_banyak * rate_honor');
            
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('monitoring_surveys');
    }
};
