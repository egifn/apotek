<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Province extends Model
{
    protected $table = 'divisi';
	protected $fillable = ['name'];
}
