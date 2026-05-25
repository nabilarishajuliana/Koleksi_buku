<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Antrian extends Model
{
    protected $table    = 'antrian';
    protected $fillable = [
        'nomor_antrian',
        'nama',
        'status',
        'waktu_daftar',
        'waktu_panggil',
    ];

    protected $casts = [
        'waktu_daftar'  => 'datetime',
        'waktu_panggil' => 'datetime',
    ];
}