<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SurveyActivity extends Model
{
    use HasFactory;

    protected $table = 'survey_activities'; // Sesuaikan jika nama tabel beda (misal: kegiatan)

    protected $fillable = [
        'nama_kegiatan',
        'tahun',
        'status',
    ];
}