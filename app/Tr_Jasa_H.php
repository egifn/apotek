<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Tr_Jasa_H extends Model
{
    protected $table = 'tr_pelayanan_h';
    protected $primaryKey = 'no_antrian';
    protected $keyType = 'string';
    protected $fillable = ['no_antrian','no_rm','tgl_pelayanan','waktu_pelayanan','id_dokter','id_user_input','status_pelayanan','status_kasir','kode_cabang'];
}
