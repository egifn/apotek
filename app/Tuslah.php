<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Tuslah extends Model
{
    protected $table = 'm_tuslah';
	protected $fillable = ['nama_tuslah','harga_tuslah','id_user_input'];
}
