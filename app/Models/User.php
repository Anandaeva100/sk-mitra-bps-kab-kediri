<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    /**
     * Kolom tabel users yang diizinkan untuk di-update secara massal
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'notif_mendekati',
        'notif_melebihi',
        'notif_survei_baru',
        'notif_email',
        'batas_honor',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }
}