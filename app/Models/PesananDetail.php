<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PesananDetail extends Model
{
    protected $table      = 'pesanan_detail';
    protected $primaryKey = 'id_pesanan_detail';
    protected $fillable   = ['id_pesanan', 'id_menu', 'jumlah', 'subtotal'];

    public function menu()
    {
        return $this->belongsTo(Menu::class, 'id_menu');
    }
}