<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pcl extends Model
{
    use HasFactory;

    protected $table = 'pcls';

    protected $fillable = [
        'id',
        'nama_pcl',
    ];
}