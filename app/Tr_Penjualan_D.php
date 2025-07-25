<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Tr_Penjualan_D extends Model
{
    protected $table = 'tr_penjualan_d';
    protected $fillable = ['kode_penjualan','kode_produk','qty','harga','diskon','ppn','total'];
}
