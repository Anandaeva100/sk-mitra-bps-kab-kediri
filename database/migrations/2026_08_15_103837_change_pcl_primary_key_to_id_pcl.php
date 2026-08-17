<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        /*
        |--------------------------------------------------------------------------
        | Lepaskan AUTO_INCREMENT dari kolom id
        |--------------------------------------------------------------------------
        */

        Schema::table('pcls', function (Blueprint $table) {
            $table->unsignedBigInteger('id')->change();
        });

        /*
        |--------------------------------------------------------------------------
        | Hapus primary key lama
        |--------------------------------------------------------------------------
        */

        Schema::table('pcls', function (Blueprint $table) {
            $table->dropPrimary();
        });

        /*
        |--------------------------------------------------------------------------
        | Jadikan id_pcl sebagai primary key
        |--------------------------------------------------------------------------
        |
        | Kolom id_pcl sudah ada pada tabel pcls,
        | sehingga tidak perlu dibuat ulang.
        |
        */

        Schema::table('pcls', function (Blueprint $table) {
            $table->primary('id_pcl');
        });

        /*
        |--------------------------------------------------------------------------
        | Hapus kolom id lama
        |--------------------------------------------------------------------------
        */

        Schema::table('pcls', function (Blueprint $table) {
            $table->dropColumn('id');
        });
    }


    public function down(): void
    {
        /*
        |--------------------------------------------------------------------------
        | Lepaskan primary key dari id_pcl
        |--------------------------------------------------------------------------
        */

        Schema::table('pcls', function (Blueprint $table) {
            $table->dropPrimary();
        });

        /*
        |--------------------------------------------------------------------------
        | Tambahkan kembali id sebagai primary key auto increment
        |--------------------------------------------------------------------------
        */

        Schema::table('pcls', function (Blueprint $table) {
            $table->id();
        });
    }
};