<?php

namespace Database\Seeders;

use App\Models\Mapel;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class MapelSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $mapels = [
            ['nama' => 'Matematika', 'deskripsi' => 'ini deskripsi mapel MTK','kode_mapel' => 'MTK'],
            ['nama' => 'Bahasa Indonesia', 'deskripsi' => 'ini deskripsi mapel BIN','kode_mapel' => 'BIN'],
            ['nama' => 'Bahasa Inggris', 'deskripsi' => 'ini deskripsi mapel ENG','kode_mapel' => 'ENG'],
            ['nama' => 'Ilmu Pengetahuan Alam', 'deskripsi' => 'ini deskripsi mapel IPA','kode_mapel' => 'IPA'],
            ['nama' => 'Ilmu Pengetahuan Sosial', 'deskripsi' => 'ini deskripsi mapel IPS','kode_mapel' => 'IPS']
        ];

        foreach ($mapels as $mapel) {
            Mapel::create($mapel);
        }
    }
}
