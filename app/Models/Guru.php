<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Guru extends Model
{
    use HasFactory;
    protected $fillable= [
        'nama',
        'nomer_induk',
        'user_id',
        'id_tahun_ajaran',
    ];

    public function user(){
        return $this->belongsTo(User::class,'user_id');
    }

    public function lembar_soal(){
        return $this->hasMany(LembarSoal::class);
    }

    public function kelasMapel()
    {
        // return $this->belongsToMany(Kelas::class, 'guru_kelas_mapels', 'guru_id', 'kelas_id')
        //             ->withPivot('mapel_id')
        //             ->withTimestamps();
        return $this->hasMany(GuruKelasMapel::class);
    }


    public function guru_kelas_mapel()
    {
        return $this->belongsToMany(Kelas::class, 'guru_kelas_mapels', 'guru_id', 'kelas_id')
                    ->withPivot('mapel_id')
                    ->withTimestamps();
    }
}

