<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->boolean('notif_mendekati')->default(false);
            $table->boolean('notif_melebihi')->default(false);
            $table->boolean('notif_survei_baru')->default(false);
            $table->boolean('notif_email')->default(false);
            $table->decimal('batas_honor', 15, 2)->default(3000000);
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'notif_mendekati',
                'notif_melebihi',
                'notif_survei_baru',
                'notif_email',
                'batas_honor',
            ]);
        });
    }
};