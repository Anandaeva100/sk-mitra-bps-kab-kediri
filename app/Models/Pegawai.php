<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pegawai extends Model
{
    use HasFactory;

    protected $fillable = [
        'nama',
        'nip',
        'jabatan',
        'golongan_ruang',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];
}