<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pcl extends Model
{
    use HasFactory;

    protected $table = 'pcls';

    protected $primaryKey = 'id_pcl';

    public $incrementing = false;

    protected $keyType = 'string';

    protected $fillable = [
        'id_pcl',
        'nama_pcl',
    ];
}