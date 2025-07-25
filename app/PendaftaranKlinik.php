<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PendaftaranKlinik extends Model
{
    protected $table = 'm_pendaftaran';
    protected $primaryKey = 'no_rm';
    protected $keyType = 'string';
    protected $fillable = ['no_rm','tgl_daftar','waktu','nama_pasien','tempat_lahir','tgl_lahir','umur','jk','alamat','id_provinsi','id_kab_kota','id_kecamatan','status_perkawinan','pekerjaan','tlp','jenis_pasien','nama_asuransi','no_asuransi','id_user_input','kode_cabang'];
}
