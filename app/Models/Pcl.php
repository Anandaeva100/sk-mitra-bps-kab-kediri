<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pcl extends Model
{
    use HasFactory;

    protected $fillable = [
        'id_pcl',
        'nama_pcl',
    ];
}