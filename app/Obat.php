<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Obat extends Model
{
    protected $table = 'm_produk';
	protected $fillable = ['kode_produk','kode_cabang','barcode','no_batch','nama_produk','id_kategori','id_jenis','id_unit','tipe','tgl_kadaluarsa','qty','qty_min','harga_beli','margin_rp','margin_persen','harga_jual','id_user_input'];
}
