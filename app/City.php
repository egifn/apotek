<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class City extends Model
{
    protected $table = 'cities';
	protected $fillable = ['province_id','name','type','postal_code'];
}
