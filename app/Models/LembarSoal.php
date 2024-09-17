<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LembarSoal extends Model
{
    use HasFactory;
    protected $fillable= [
        'guru_id',
        'mapel_id',
        'kelas_id',
        'id_tahun_ajaran',
        'tanggal_mulai',
        'tanggal_selesai',
        'tipe_soal',
    ];

    public function guru(){
        return $this->belongsTo(Guru::class,'guru_id');
    }
    public function mapel(){
        return $this->belongsTo(Mapel::class,'mapel_id');
    }
    public function kelas(){
        return $this->belongsTo(Kelas::class,'kelas_id');
    }

    public function tahun_ajaran(){
        return $this->belongsTo(TahunAjaran::class,'id_tahun_ajaran');
    }
    public function soal(){
        return $this->hasMany(Soal::class);
    }
    public function lembar_jawab(){
        return $this->hasMany(LembarJawab::class);
    }


}
