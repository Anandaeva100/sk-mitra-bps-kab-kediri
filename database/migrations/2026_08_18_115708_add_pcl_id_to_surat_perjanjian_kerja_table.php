<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('surat_perjanjian_kerja', function (Blueprint $table) {
            // Menambahkan kolom pcl_id setelah beban_anggaran (opsional nullable)
            $table->string('pcl_id')->nullable()->after('beban_anggaran');
        });
    }

    public function down(): void
    {
        Schema::table('surat_perjanjian_kerja', function (Blueprint $table) {
            $table->dropColumn('pcl_id');
        });
    }
};