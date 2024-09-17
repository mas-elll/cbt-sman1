<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Jawaban extends Model
{
    use HasFactory;
    protected $fillable= [
        'lembar_jawab_id',
        'soal_id',
        'jawaban',
        'poin',
    ];

    public function lembar_jawab(){
        return $this->belongsTo(LembarJawab::class,'lembar_jawab_id');
    }
}
