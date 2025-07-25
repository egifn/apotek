<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Tr_Jasa_D_Obat extends Model
{
    protected $table = 'tr_pelayanan_d_obat';
    protected $fillable = ['no_antrian','kode_produk','qty','harga','total'];
}
