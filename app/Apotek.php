<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Apotek extends Model
{
    protected $table = 'm_cabang';
	protected $fillable = ['kode_cabang','nama_cabang','alamat','tlp','id_user_input'];
}
