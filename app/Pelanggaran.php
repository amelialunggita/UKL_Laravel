<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Pelanggaran extends Model
{
    protected $table = 'pelanggaran';

    protected $fillable = [
      'nama_pelanggaran',
      'kategori',
      'poin',
    ];

    public function poin_siswas(){
    return $this->hasMany('App\Poin_siswa', 'id_pelanggaran', 'id');
    }
}
