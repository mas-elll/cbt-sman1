<?php

namespace App\Providers;

use App\Models\LembarSoal;
use App\Models\Siswa;
use Carbon\Carbon;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class ViewServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        // Menggunakan view composer
        View::composer('layout.header', function ($view) {
            // Pastikan user terautentikasi dan memiliki peran siswa
            $user = auth()->user();
            if ($user && $user->hasRole('siswa')) {
                $siswa = Siswa::where('user_id', $user->id)->with('kelas')->first();
                $currentDate = Carbon::now();

                // Ambil lembar soal yang masih dalam rentang tanggal waktu pengerjaan dan belum dijawab oleh siswa
                $lembarSoals = LembarSoal::where('kelas_id', $siswa->kelas->id)
                    ->where('tanggal_mulai', '<=', $currentDate)
                    ->where('tanggal_selesai', '>=', $currentDate)
                    ->whereDoesntHave('lembar_jawab', function ($query) use ($siswa) {
                        $query->where('siswa_id', $siswa->id);
                    })
                    ->with('kelas', 'mapel', 'guru')
                    ->paginate(10);

                $view->with('lembarSoals', $lembarSoals);
            }
        });
    }
}
