<?php

namespace App\Http\Controllers;

use App\Models\Guru;
use App\Models\GuruKelasMapel;
use App\Models\Kelas;
use App\Models\LembarJawab;
use App\Models\LembarSoal;
use App\Models\Mapel;
use App\Models\Siswa;
use App\Models\TahunAjaran;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LaporanController extends Controller
{
    public function getAllMapelByKelasSiswa(Request $request)
    {
        $user_id = auth()->user()->id;
        $siswa = Siswa::with('kelas')->where('user_id', $user_id)->first();
        $tahunAjaranId = session('tahun_ajaran');
        if (!$siswa) {
            return redirect()->back()->with('error', 'Siswa tidak ditemukan.');
        }

        $search = $request->input('search');
        $mapels = GuruKelasMapel::with(['guru', 'mapel'])
            ->where('kelas_id', $siswa->kelas_id)
            ->whereHas('mapel', function($query) use ($search) {
                $query->where('nama', 'LIKE', "%{$search}%")
                      ->orWhere('kode_mapel', 'LIKE', "%{$search}%");
            })
            ->where('id_tahun_ajaran', $tahunAjaranId)
            ->paginate(10);

        return view('siswa.laporan.index', compact('mapels'));
    }

    public function pilihTahunAjaran()
    {
        // Ambil semua tahun ajaran
        $tahunAjarans = TahunAjaran::all();
        return view('siswa.laporan.pilih_tahun_ajaran', compact('tahunAjarans'));
    }

    public function setTahunAjaran(Request $request)
    {
        // Validasi input
        $request->validate([
            'tahun_ajaran' => 'required|exists:tahun_ajaran,id',
        ]);

        // Simpan tahun ajaran ke dalam sesi
        session(['tahun_ajaran' => $request->input('tahun_ajaran')]);

        // Redirect ke halaman utama
        return redirect()->route('mapel-by-kelas-siswa');
    }

    public function getAllLembarJawabByMapel(Request $request, Kelas $kelas, Mapel $mapel)
    {
        $user = auth()->user();
        $siswa = Siswa::where('user_id', $user->id)->with('kelas')->first();

        if (!$siswa) {
            return redirect()->back()->with('error', 'Siswa tidak ditemukan.');
        }

        $currentKelas = $kelas;
        $currentMapel = $mapel;
        $search = $request->input('search');

        $lembarJawabs = LembarJawab::with([
            'siswa',
            'lembar_soal.guru',
            'lembar_soal.mapel',
            'lembar_soal.kelas',
            'lembar_soal.soal',
            'jawaban'
        ])
        ->where('siswa_id', $siswa->id)
        ->whereHas('lembar_soal', function ($query) use ($kelas, $mapel,$search) {
            $query->where('kelas_id', $kelas->id)
                  ->where('mapel_id', $mapel->id)
                  ->where('tanggal_mulai', 'LIKE', "%{$search}%") // Search by tanggal_mulai
                  ->orderBy('tanggal_mulai', 'desc');
        })
        ->paginate(10);

        return view('siswa.laporan.lembarJawab', compact('lembarJawabs','currentKelas','currentMapel'));
    }

    public function showPDFSiswa(Kelas $kelas,Mapel $mapel,LembarJawab $lembarJawab)
    {
        // dd($lembarJawab);
        if ($lembarJawab->id) {
            $lembarJawabs = LembarJawab::where('id', $lembarJawab->id)
                ->with([
                    'siswa',
                    'lembar_soal.guru',
                    'lembar_soal.mapel',
                    'lembar_soal.kelas',
                    'lembar_soal.soal',
                    'jawaban'
                ])
                ->get();

                $pdf = app('dompdf.wrapper')->loadView('siswa.laporan.pdf', ['lembarJawabs' => $lembarJawabs]);
            return $pdf->stream('siswa.laporan.pdf');
        } else {
            $user = auth()->user();
            $siswa = Siswa::where('user_id', $user->id)->with('kelas')->first();

            if (!$siswa) {
                return redirect()->back()->with('error', 'Siswa tidak ditemukan.');
            }

            $lembarJawabMapel = LembarJawab::with([
                    'siswa',
                    'lembar_soal.guru',
                    'lembar_soal.mapel',
                    'lembar_soal.kelas',
                    'lembar_soal.soal',
                    'jawaban'
                ])
                ->where('siswa_id', $siswa->id)
                ->whereHas('lembar_soal', function ($query) use ($kelas, $mapel) {
                    $query->where('kelas_id', $kelas->id)
                          ->where('mapel_id', $mapel->id);
                })
                ->get();

                $pdf = app('dompdf.wrapper')->loadView('siswa.laporan.pdf', ['lembarJawabs' => $lembarJawabMapel]);

                return $pdf->stream('siswa.laporan.pdf');
        }
    }
    public function showPDFGuru(Kelas $kelas,Mapel $mapel,LembarSoal $lembarSoal)
    {
        // dd($lembarJawab);
        if ($lembarSoal->id) {
            $lembarJawabs = LembarJawab::where('lembar_soal_id', $lembarSoal->id)
                ->with([
                    'siswa',
                    'lembar_soal.guru',
                    'lembar_soal.mapel',
                    'lembar_soal.kelas',
                    'lembar_soal.soal',
                    'jawaban'
                ])
                ->get();

                $pdf = app('dompdf.wrapper')->loadView('guru.laporan.pdf', ['lembarJawabs' => $lembarJawabs]);
            return $pdf->stream('guru.laporan.pdf');
        } else {
            $user = auth()->user();
            $guru = Guru::where('user_id', $user->id)->first();

            if (!$guru) {
                return redirect()->back()->with('error', 'Guru tidak ditemukan.');
            }

            $lembarJawabMapel = LembarJawab::with([
                    'siswa',
                    'lembar_soal.guru',
                    'lembar_soal.mapel',
                    'lembar_soal.kelas',
                    'lembar_soal.soal',
                    'jawaban'
                ])
                ->whereHas('lembar_soal', function ($query) use ($kelas, $mapel,$guru) {
                    $query->where('kelas_id', $kelas->id)
                          ->where('mapel_id', $mapel->id)
                          ->where('guru_id',$guru->id);
                })
                ->get();

                $pdf = app('dompdf.wrapper')->loadView('guru.laporan.pdf', ['lembarJawabs' => $lembarJawabMapel]);

                return $pdf->stream('guru.laporan.pdf');
        }
    }
    public function showPDFAdmin(Kelas $kelas,Mapel $mapel,LembarSoal $lembarSoal)
    {
        // dd($lembarJawab);
        if ($lembarSoal->id) {
            $lembarJawabs = LembarJawab::where('lembar_soal_id', $lembarSoal->id)
                ->with([
                    'siswa',
                    'lembar_soal.guru',
                    'lembar_soal.mapel',
                    'lembar_soal.kelas',
                    'lembar_soal.soal',
                    'jawaban'
                ])
                ->get();

                $pdf = app('dompdf.wrapper')->loadView('admin.lembar-soal.pdf', ['lembarJawabs' => $lembarJawabs]);
            return $pdf->stream('guru.laporan.pdf');
        } else {
            $user = auth()->user();
            $guru = Guru::where('user_id', $user->id)->first();

            if (!$guru) {
                return redirect()->back()->with('error', 'Guru tidak ditemukan.');
            }

            $lembarJawabMapel = LembarJawab::with([
                    'siswa',
                    'lembar_soal.guru',
                    'lembar_soal.mapel',
                    'lembar_soal.kelas',
                    'lembar_soal.soal',
                    'jawaban'
                ])
                ->whereHas('lembar_soal', function ($query) use ($kelas, $mapel,$guru) {
                    $query->where('kelas_id', $kelas->id)
                          ->where('mapel_id', $mapel->id)
                          ->where('guru_id',$guru->id);
                })
                ->get();

                $pdf = app('dompdf.wrapper')->loadView('admin.lembar-soal.pdf', ['lembarJawabs' => $lembarJawabMapel]);

                return $pdf->stream('admin.lembar-soal.pdf');
        }
    }
    // public function getAllKelasLaporanByGuru(Request $request)
    // {
    //     $user = auth()->user();
    //     $guru = Guru::where('user_id', $user->id)->first();
    //     $search = $request->input('search');

    //     if (!$guru) {
    //         return redirect()->back()->with('error', 'Guru tidak ditemukan.');
    //     }

    //     $kelasMapels = GuruKelasMapel::with(['kelas', 'mapel'])
    //                     ->where('guru_id', $guru->id)
    //                     ->whereHas('kelas', function ($query) use ($search) {
    //                         $query->where('kelas', 'LIKE', "%{$search}%");
    //                     })
    //                     ->orWhereHas('mapel', function ($query) use ($search) {
    //                         $query->where('nama', 'LIKE', "%{$search}%");
    //                     })
    //                     ->paginate(10);

    //     return view('guru.laporan.index', compact('kelasMapels'));
    // }

    // // public function getLembarSoalByKelasMapel(Request $request,Kelas $kelas,Mapel $mapel)
    // // {
    // //     $lembarSoals = LembarSoal::with(['guru', 'mapel', 'kelas', 'soal', 'lembar_jawab'])
    // //     ->whereHas('lembar_jawab', function ($query) use ($kelas, $mapel) {
    // //         $query->where('kelas_id', $kelas->id)
    // //               ->where('mapel_id', $mapel->id);
    // //     })
    // //     ->paginate(10);

    // //     return view('guru.laporan.lembar-soal', compact('lembarSoals'));
    // // }


}
