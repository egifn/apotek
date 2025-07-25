<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Tr_Jasa_D extends Model
{
    protected $table = 'tr_pelayanan_d';
    protected $fillable = ['no_antrian','kode_jasa_p','harga_jasa_p'];
}
