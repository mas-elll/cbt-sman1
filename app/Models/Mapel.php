<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Mapel extends Model
{
    use HasFactory;
    protected $fillable= [
        'nama',
        'kode_mapel',
        'deskripsi',
    ];

    public function guru()
    {
        return $this->belongsToMany(Guru::class, 'guru_kelas_mapels', 'mapel_id', 'guru_id')
                    ->withPivot('kelas_id')
                    ->withTimestamps();
    }

}
