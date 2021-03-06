<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Alamat extends Model
{
    protected $table = 'alamat';
    protected $primaryKey = 'id_alamat';
    protected $fillable = ['nama', 'nomor_telepon', 'provinsi_id', 'city_id', 'kecamatan_id', 'kode_pos', 'alamat_lengkap', 'user_id', 'user_type', 'nama_provinsi', 'nama_kota'];
    public $timestamps = false;

    public function user()
    {
        return $this->morphTo();
    }
}
