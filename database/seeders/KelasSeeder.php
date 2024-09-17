<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\Kelas;

class KelasSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $classes = [];

        // Loop untuk kelas X, XI, dan XII
        $grades = ['X', 'XI', 'XII'];
        // Loop untuk jurusan
        $majors = ['MIPA', 'IPS'];

        // ID counter
        $id = 1;

        foreach ($grades as $grade) {
            foreach ($majors as $major) {
                for ($i = 1; $i <= 6; $i++) {
                    $classes[] = [
                        'id' => $id,
                        'kelas' => "{$grade} {$major} {$i}",
                    ];
                    $id++;
                }
            }
        }

        // Insert data into the database
        Kelas::firstOrCreate($classes);
    }
}
