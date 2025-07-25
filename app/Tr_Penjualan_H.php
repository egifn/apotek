<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Tr_Penjualan_H extends Model
{
    protected $table = 'tr_penjualan_h';
    protected $primaryKey = 'kode_penjualan';
    protected $keyType = 'string';
    protected $fillable = ['kode_penjualan','tgl_penjualan','waktu_penjualan','jenis_penjualan','id_user_input','kode_cabang'];
}
