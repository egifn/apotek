<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class StokOpname_D extends Model
{
    protected $table = 'stokopname_d';
    protected $fillable = ['kode_opname','kode_produk','jml_sistem','jml_fisik','selisih'];
}
