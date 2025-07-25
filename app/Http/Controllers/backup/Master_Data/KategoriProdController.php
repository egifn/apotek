<?php

namespace App\Http\Controllers\Master_Data;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class KategoriProdController extends Controller
{
    public function index()
    {
        return view ('master_data.kategori_produk.index');
    }
}
