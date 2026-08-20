<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SuratTugas extends Model
{
    use HasFactory;

    protected $table = 'surat_tugas';

    protected $fillable = [
        'nomor_surat',
        'nama_survei',
        'nama_pcl',
        'mengingat',
        'untuk',
        'wilayah_tugas',
        'waktu_tugas',
        'tanggal_surat',
    ];

    protected $casts = [
        'tanggal_surat' => 'date',
        'mengingat' => 'array',
        'untuk'         => 'array',
    ];
}