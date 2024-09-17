<?php

namespace App\Http\Controllers;

use App\Models\Guru;
use App\Models\GuruKelasMapel;
use App\Models\Jawaban;
use App\Models\Kelas;
use App\Models\LembarJawab;
use App\Models\LembarSoal;
use App\Models\Mapel;
use App\Models\TahunAjaran;
use App\Models\Siswa;
use App\Models\Soal;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class LembarJawabController extends Controller
{
    public function getLembarJawabBySiswa(Request $request)
    {
        $user = auth()->user();
        $siswa = Siswa::where('user_id', $user->id)->with('kelas')->first();

        $lembarJawabs = LembarJawab::with(['siswa', 'lembar_soal.guru', 'lembar_soal.mapel', 'lembar_soal.kelas', 'lembar_soal.soal', 'jawaban'])
            ->where('siswa_id', $siswa->id)
            ->paginate(10);

        return view('siswa.lembarSoal', compact('lembarJawabs'));
    }

    public function getAllKelasByGuru(Request $request)
    {
        $user = auth()->user();
        $guru = Guru::where('user_id', $user->id)->first();
        $tahunAjaranId = session('tahun_ajaran');
        $search = $request->input('search');

        if (!$guru) {
            return redirect()->back()->with('error', 'Guru tidak ditemukan.');
        }

        if (!$tahunAjaranId) {
            return redirect()->route('pilih-tahun-ajaran')->with('error', 'Tahun ajaran belum dipilih.');
        }
        $kelasMapels = GuruKelasMapel::with(['kelas', 'mapel'])
                        ->where('guru_id', $guru->id)
                        ->where('id_tahun_ajaran', $tahunAjaranId) // Pastikan nama kolom yang benar
                        ->where(function ($query) use ($search) {
                            $query->whereHas('kelas', function ($query) use ($search) {
                                $query->where('kelas', 'LIKE', "%{$search}%");
                            })
                            ->orWhereHas('mapel', function ($query) use ($search) {
                                $query->where('nama', 'LIKE', "%{$search}%");
                            });
                        })
                        ->paginate(10);

        return view('guru.lembar-jawab.kelas', compact('kelasMapels'));
    }

    public function getAllLembarJawabById(Request $request,LembarSoal $lembarSoal){

        $lembarJawabs = LembarJawab::with(['siswa', 'lembar_soal.guru', 'lembar_soal.mapel', 'lembar_soal.kelas', 'lembar_soal.soal', 'jawaban'])
        ->where('lembar_soal_id',$lembarSoal->id)
        ->paginate(10);

        return view('guru.lembar-jawab.index',compact('lembarJawabs'));
    }

    public function pilihTahunAjaran()
    {
        // Ambil semua tahun ajaran
        $tahunAjarans = TahunAjaran::all();
        return view('guru.lembar-jawab.pilih_tahun', compact('tahunAjarans'));
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
        return redirect()->route('get-kelas-lembar-jawab');
    }

    public function AdminGetAllLembarJawabById(Request $request,LembarSoal $lembarSoal){

        $lembarJawabs = LembarJawab::with(['siswa', 'lembar_soal.guru', 'lembar_soal.mapel', 'lembar_soal.kelas', 'lembar_soal.soal', 'jawaban'])
        ->where('lembar_soal_id',$lembarSoal->id)
        ->paginate(10);

        // dd($lembarJawabs);
        return view('admin.lembar-soal.lembar-jawab',compact('lembarJawabs'));
    }




    public function SimpanLembarJawab(Request $request)
    {
        $validated = $request->validate([
            'lembar_soal_id' => 'required|exists:lembar_soals,id',
            'jawaban' => 'array',
            'jawaban.*' => 'string|nullable', // Allow null answers
        ]);

        DB::beginTransaction();
        $user = auth()->user();
        $siswa = Siswa::where('user_id', $user->id)->with('kelas')->first();

        try {
            $lembarJawab = LembarJawab::create([
                'siswa_id' => $siswa->id,
                'lembar_soal_id' => $validated['lembar_soal_id'],
                'tanggal' => now(),
                'nilai' => 0, // nilai default
                'feedback' => null,
            ]);

            $totalPoin = 0;

            foreach ($validated['jawaban'] as $soalId => $jawabanText) {
                $jawabanText = strtolower($jawabanText); // mengubah ke lowercase(bukan kapital)
                $soal = Soal::findOrFail($soalId); // memastikan soal ada
                $poin = $soal->kunci_jawaban === $jawabanText ? $soal->poin : 0;
                $totalPoin += $poin;

                Jawaban::create([
                    'lembar_jawab_id' => $lembarJawab->id,
                    'soal_id' => $soalId,
                    'jawaban' => $jawabanText ?: null, // Set to null if empty
                    'poin' => $poin,
                ]);
            }

            $lembarJawab->update(['nilai' => $totalPoin]);

            DB::commit();

            return redirect('/siswa/tugas')->with('success', 'Jawaban berhasil disimpan!');
        } catch (\Exception $e) {
            DB::rollBack();

            return redirect()->back()->with('error', 'Terjadi kesalahan saat menyimpan jawaban: ' . $e->getMessage());
        }
    }

    public function updateNilaiJawaban(Request $request, LembarJawab $lembarJawab) {
        try {
            $request->validate([
                'jawaban.*.id' => 'required|exists:jawabans,id',
                'jawaban.*.poin' => 'required|numeric|min:0',
                'feedback' => 'nullable',
            ]);

            DB::beginTransaction();

            foreach ($request->jawaban as $jawabanInput) {
                $jawaban = Jawaban::findOrFail($jawabanInput['id']);
                $jawaban->poin = $jawabanInput['poin'];
                $jawaban->save();
            }

            // Hitung ulang total nilai untuk LembarJawab terkait
            $totalNilai = $lembarJawab->jawaban->sum('poin');
            $lembarJawab->nilai = $totalNilai;
            $lembarJawab->feedback = $request->input('feedback');
            $lembarJawab->save();

            DB::commit();

            return redirect()->back()->with('success', 'Nilai jawaban berhasil diperbarui.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }


}
