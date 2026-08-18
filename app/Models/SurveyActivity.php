<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SurveyActivity extends Model
{
    use HasFactory;

    protected $table = 'survey_activities';

    protected $fillable = [
        'nama_kegiatan',
        'tahun',
        'status',
    ];

    /**
     * Relasi ke PCL via MonitoringSurvey
     * Sesuaikan 'id_kegiatan' di bawah ini jika nama kolom di tabel monitoring_surveys berbeda.
     */
    public function pcl()
    {
        return $this->hasOneThrough(
            Pcl::class,
            MonitoringSurvey::class,
            'id_kegiatan', // Foreign key di tabel monitoring_surveys yang menunjuk ke survey_activities
            'id_pcl',      // Foreign key di tabel pcls
            'id',          // Local key di tabel survey_activities
            'pcl_id'       // Local key di tabel monitoring_surveys
        );
    }
}