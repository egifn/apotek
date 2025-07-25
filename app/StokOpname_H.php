<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class StokOpname_H extends Model
{
    protected $table = 'stokopname_h';
    protected $primaryKey = 'kode_opname';
    protected $keyType = 'string';
    protected $fillable = ['kode_opname','tgl_opname','waktu_opname','keterangan','id_user_input','kode_cabang'];
}

