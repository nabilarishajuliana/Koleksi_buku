<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Hash;

class Vendor extends Model
{
    protected $table      = 'vendor';
    protected $primaryKey = 'id_vendor';
    protected $fillable   = ['nama_vendor', 'alamat', 'email', 'password'];

    // Otomatis hash password saat diset
    public function setPasswordAttribute($value)
    {
        $this->attributes['password'] = Hash::make($value);
    }

    public function menu()
    {
        return $this->hasMany(Menu::class, 'id_vendor');
    }
}