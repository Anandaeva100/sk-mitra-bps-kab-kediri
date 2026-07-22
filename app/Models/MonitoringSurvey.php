<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MonitoringSurvey extends Model
{
    // Mengizinkan semua kolom diisi melalui form web
    protected $guarded = [];

    // Mendaftarkan hubungan agar sistem tahu siapa akun yang menginput data
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
