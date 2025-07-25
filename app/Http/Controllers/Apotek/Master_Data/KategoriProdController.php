<?php

namespace App\Http\Controllers\Apotek\Master_Data;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class KategoriProdController extends Controller
{
    public function index()
    {
        return view('apotek.master_data.kategori_produk.index');
    }
}
