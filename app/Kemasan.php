<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Kemasan extends Model
{
    protected $table = 'm_produk_unit';
	protected $fillable = ['nama_unit','id_user_input'];
}
