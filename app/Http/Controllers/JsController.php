<?php

namespace App\Http\Controllers;

class JsController extends Controller
{
    // Halaman tabel biasa
    public function tabelBiasa()
    {
        return view('js.tabel_biasa');
    }

    // Halaman datatables
    public function tabelDatatables()
    {
        return view('js.tabel_datatables');
    }

    public function sc4Select()
    {
        return view('js.sc4_select');
    }
}
