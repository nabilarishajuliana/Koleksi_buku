<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Menu extends Model
{
    protected $table      = 'menu';
    protected $primaryKey = 'id_menu';
    protected $fillable   = ['id_vendor', 'nama_menu', 'harga'];

    public function vendor()
    {
        return $this->belongsTo(Vendor::class, 'id_vendor');
    }
}