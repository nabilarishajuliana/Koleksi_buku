<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LokasiToko extends Model
{
    protected $table      = 'lokasi_toko';
    protected $primaryKey = 'barcode';
    protected $keyType    = 'string'; // primary key bukan integer
    public $incrementing  = false;    // tidak auto increment

    protected $fillable = [
        'barcode',
        'nama_toko',
        'latitude',
        'longitude',
        'accuracy',
    ];
}