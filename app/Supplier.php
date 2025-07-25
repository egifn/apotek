<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Supplier extends Model
{
    protected $table = 'm_supplier';
	protected $fillable = ['kode_supplier','nama_supplier','alamat','cp','tlp','email','status','id_user_input'];
}
