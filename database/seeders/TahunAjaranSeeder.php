<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TahunAjaranSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        DB::table('tahun_ajaran')->insert([
            ['tahun_ajaran' => '2023/2024'],
            ['tahun_ajaran' => '2024/2025'],
            ['tahun_ajaran' => '2025/2026'],
        ]);
    }
}
