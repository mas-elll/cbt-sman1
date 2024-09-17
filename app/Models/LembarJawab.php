<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LembarJawab extends Model
{
    use HasFactory;
    protected $fillable= [
        'siswa_id',
        'lembar_soal_id',
        'tanggal',
        'nilai',
        'feedback',
    ];

    public function siswa(){
        return $this->belongsTo(Siswa::class,'siswa_id');
    }

    public function lembar_soal(){
        return $this->belongsTo(LembarSoal::class,'lembar_soal_id');
    }
    public function jawaban(){
        return $this->hasMany(Jawaban::class);
    }
}
