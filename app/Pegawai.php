<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Pegawai extends Model
{
    protected $table = 'm_pegawai';
	protected $fillable = ['kode_pegawai','nama_pegawai', 'jk','alamat','tlp','email','jabatan','status_pegawai','id_user_input'];
}
