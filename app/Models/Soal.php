<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Soal extends Model
{
    use HasFactory;
    protected $fillable= [
        'lembar_soal_id',
        'tipe_soal',
        'soal',
        'kunci_jawaban',
        'poin',
        'pilihan_a',
        'pilihan_b',
        'pilihan_c',
        'pilihan_d',
        'pilihan_e'
    ];

    public function lembar_soal(){
        return $this->belongsTo(LembarSoal::class);
    }
}
