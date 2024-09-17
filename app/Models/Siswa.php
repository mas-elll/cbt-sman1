<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Siswa extends Model
{
    use HasFactory;
    protected $fillable= [
        'nama',
        'nomer_induk',
        'user_id',
        'kelas_id'
    ];

    public function user(){
        return $this->belongsTo(User::class,'user_id');
    }
    public function kelas(){
        return $this->belongsTo(Kelas::class,'kelas_id');
    }
    public function laporan(){
        return $this->hasOne(Laporan::class);
    }
    public function lembar_jawab(){
        return $this->hasMany(LembarJawab::class);
    }
}
