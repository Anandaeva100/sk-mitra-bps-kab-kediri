<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('monitoring_surveys', function (Blueprint $table) {
            // Menambahkan satuan setelah nama_pcl
            $table->string('satuan')->nullable()->after('nama_pcl');

            // Menambahkan wilayah_tugas setelah beban_banyak
            $table->string('wilayah_tugas')->nullable()->after('beban_banyak');
        });
    }

    public function down(): void
    {
        Schema::table('monitoring_surveys', function (Blueprint $table) {
            $table->dropColumn(['satuan', 'wilayah_tugas']);
        });
    }
};