<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\LembarSoal;
use App\Models\Soal;
use App\Models\GuruKelasMapel;
use Carbon\Carbon;

class LembarSoalSeeder extends Seeder
{
    public function run()
    {
        // Dapatkan semua data guru, kelas, mapel dari pivot table
        $guruKelasMapels = GuruKelasMapel::all();
        
        // Array untuk opsi jawaban
        $opsiJawaban = ['A', 'B', 'C', 'D'];

        // Pertanyaan umum dan opsi jawabannya
        $questions = [
            [
                'question' => 'Apa ibu kota Indonesia?',
                'options' => [
                    'A' => 'Jakarta',
                    'B' => 'Surabaya',
                    'C' => 'Bandung',
                    'D' => 'Medan',
                ],
                'answer' => 'A'
            ],
            [
                'question' => 'Berapa jumlah provinsi di Indonesia?',
                'options' => [
                    'A' => '34',
                    'B' => '30',
                    'C' => '28',
                    'D' => '35',
                ],
                'answer' => 'A'
            ],
            [
                'question' => 'Siapa presiden pertama Indonesia?',
                'options' => [
                    'A' => 'Soekarno',
                    'B' => 'Soeharto',
                    'C' => 'BJ Habibie',
                    'D' => 'Gus Dur',
                ],
                'answer' => 'A'
            ],
            [
                'question' => 'Apa mata uang Indonesia?',
                'options' => [
                    'A' => 'Rupiah',
                    'B' => 'Ringgit',
                    'C' => 'Dollar',
                    'D' => 'Yen',
                ],
                'answer' => 'A'
            ],
            [
                'question' => 'Gunung tertinggi di Indonesia adalah?',
                'options' => [
                    'A' => 'Gunung Kerinci',
                    'B' => 'Gunung Rinjani',
                    'C' => 'Gunung Jaya Wijaya',
                    'D' => 'Gunung Semeru',
                ],
                'answer' => 'C'
            ],
        ];

        foreach ($guruKelasMapels as $guruKelasMapel) {
            // Buat LembarSoal
            $lembarSoal = LembarSoal::create([
                'guru_id' => $guruKelasMapel->guru_id,
                'mapel_id' => $guruKelasMapel->mapel_id,
                'kelas_id' => $guruKelasMapel->kelas_id,
                'tanggal_mulai' => Carbon::now()->format('Y-m-d H:i:s'),
                'tanggal_selesai' => Carbon::now()->addDays(2)->format('Y-m-d H:i:s'),
                'tipe_soal' => 'Pilihan Ganda',
            ]);

            // Buat beberapa Soal untuk setiap LembarSoal
            foreach ($questions as $key => $question) {
                // Gabungkan opsi jawaban dengan baris baru
                $options = implode("\n", [
                    "A. " . $question['options']['A'],
                    "B. " . $question['options']['B'],
                    "C. " . $question['options']['C'],
                    "D. " . $question['options']['D'],
                ]);

                $soal = Soal::create([
                    'lembar_soal_id' => $lembarSoal->id,
                    'tipe_soal' => 'Pilihan Ganda', // tipe soal Pilihan Ganda
                    'soal' => $question['question'] . "\n\n" . $options,
                    'kunci_jawaban' => strtolower($question['answer']), // Convert kunci jawaban to lowercase
                    'poin' => rand(1, 10),
                ]);
            }
        }
    }
}
