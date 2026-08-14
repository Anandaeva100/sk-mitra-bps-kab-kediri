<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pml extends Model
{
    use HasFactory;

    protected $table = 'pmls';

    protected $fillable = [
        'nama_pml',
    ];
}