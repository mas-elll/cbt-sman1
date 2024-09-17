<?php

namespace Database\Seeders;

use App\Models\Admin;
use App\Models\Guru;
use App\Models\GuruKelasMapel;
use App\Models\Kelas;
use App\Models\Mapel;
use App\Models\Siswa;
use App\Models\User;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Roles
        $admin_role = Role::create(['name' => 'admin']);
        $guru_role = Role::create(['name' => 'guru']);
        $siswa_role = Role::create(['name' => 'siswa']);

        // Admin
        $admin = User::create([
            'name' => 'admin',
            'email' => 'admin@gmail.com',
            'password' => bcrypt('password'),
        ]);
        $admin->assignRole($admin_role);

        Admin::create([
            'user_id' => $admin->id,
        ]);

        // Retrieve all classes and subjects
        $classes = Kelas::all();
        $subjects = Mapel::all();

        // Guru
        for ($i = 1; $i <= 3; $i++) {
            $guru = User::create([
                'name' => "guru $i",
                'email' => "guru$i@gmail.com",
                'password' => bcrypt('password'),
            ]);
            $guru->assignRole($guru_role);

            $guru_model = Guru::create([
                'nama' => "Guru $i",
                'nomer_induk' => "G00$i",
                'user_id' => $guru->id,
            ]);

            // Assign guru to random classes and subjects
            foreach ($classes->random(3) as $kelas) {
                foreach ($subjects->random(3) as $mapel) {
                    GuruKelasMapel::create([
                        'guru_id' => $guru_model->id,
                        'kelas_id' => $kelas->id,
                        'mapel_id' => $mapel->id,
                    ]);
                }
            }
        }

        // Siswa
        for ($i = 1; $i <= 30; $i++) {
            $siswa = User::create([
                'name' => "siswa $i",
                'email' => "siswa$i@gmail.com",
                'password' => bcrypt('password'),
            ]);
            $siswa->assignRole($siswa_role);

            Siswa::create([
                'nama' => "Siswa $i",
                'nomer_induk' => "S00$i",
                'user_id' => $siswa->id,
                'kelas_id' => 1, // Asumsi kelas_id 1 sudah ada
            ]);
        }
    }
}
