<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\Pivot;

class GuruKelasMapel extends Pivot
{
    use HasFactory;
    protected $table = 'guru_kelas_mapels';

    public function guru(){
        return $this->belongsTo(Guru::class);
    }

    public function kelas(){
        return $this->belongsTo(Kelas::class);
    }

    public function mapel(){
        return $this->belongsTo(Mapel::class);
    }

    public function tahunAjaran()
{
    return $this->belongsTo(TahunAjaran::class, 'id_tahun_ajaran');
}

}
