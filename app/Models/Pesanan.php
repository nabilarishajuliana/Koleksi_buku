<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pesanan extends Model
{
    protected $table    = 'pesanan';
    protected $primaryKey = 'id_pesanan';
    protected $fillable = ['nama_customer', 'total', 'status_bayar', 'snap_token'];

    public function detail()
    {
        return $this->hasMany(PesananDetail::class, 'id_pesanan');
    }

    public function pembayaran()
    {
        return $this->hasOne(Pembayaran::class, 'id_pesanan');
    }
}