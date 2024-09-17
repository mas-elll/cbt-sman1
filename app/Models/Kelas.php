<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Kelas extends Model
{
    use HasFactory;

    protected $fillable = [
        'kelas',
    ];

    /**
     * Definisikan relasi many-to-many dengan model Guru.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function gurus()
    {
        return $this->belongsToMany(Guru::class, 'guru_kelas_mapels', 'kelas_id', 'guru_id')
                    ->withPivot('mapel_id')
                    ->withTimestamps();
    }
    public function mapel()
    {
        return $this->belongsToMany(Mapel::class, 'guru_kelas_mapels', 'kelas_id', 'mapel_id');
    }
    
    /**
     * Definisikan relasi one-to-many dengan model Siswa.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function siswa()
    {
        return $this->hasMany(Siswa::class);
    }
}
