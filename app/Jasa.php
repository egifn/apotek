<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Jasa extends Model
{
    protected $table = 'm_jasa_pelayanan';
	protected $fillable = ['kode_jasa_p','nama_jasa_p','harga','id_user_input'];
}
