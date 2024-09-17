<?php

namespace App\Http\Controllers;

use App\Models\Guru;
use App\Models\Kelas;
use App\Models\LembarSoal;
use App\Models\GuruKelasMapel;
use App\Models\LembarJawab;
use App\Models\Mapel;
use App\Models\Siswa;
use App\Models\Soal;
use App\Models\TahunAjaran;
use App\Models\User;
use Carbon\Carbon;
use Carbon\CarbonInterval;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use RealRashid\SweetAlert\Facades\Alert;
use Spatie\FlareClient\Http\Response;

class LembarSoalController extends Controller
{
    public function getAllLembarSoal(){
        $lembarSoals = LembarSoal::with('guru','kelas','mapel')->paginate(10);
        $gurus = Guru::with('kelasMapel')->get();
        return view('admin.lembarSoal',compact('lembarSoals','gurus'));
    }

    public function pilihTahunAjaran()
    {
        // Ambil semua tahun ajaran
        $tahunAjarans = TahunAjaran::all();
        return view('siswa.pilih_tahun_ajaran', compact('tahunAjarans'));
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
        return redirect()->route('lembar-soal-siswa');
    }
    public function upload(Request $request)
    {
            if ($request->hasFile('upload')) {


                $originName = $request->file('upload')->getClientOriginalName();

                $fileName = pathinfo($originName, PATHINFO_FILENAME);
                $extension = $request->file('upload')->getClientOriginalExtension();
                $fileName = $fileName . '_' . time() . '.' . $extension;

                // Move the uploaded file to the public/uploads directory
                $request->file('upload')->move(public_path('uploads'), $fileName);

                // Prepare response for CKEditor

                $url = asset('uploads/' . $fileName);
                return response()->json(['filename' => $fileName, 'uploaded'=> 1,
                'url' => $url]);
            }

    }
    public function getLembarSoalByGuru(Request $request){
        $UserId = auth()->user()->id;
        $GuruId = Guru::where('user_id', $UserId)->with('kelasMapel.kelas', 'kelasMapel.mapel')->first();
        $lembarSoals = LembarSoal::where('guru_id', $GuruId->id)-> orderBy('tanggal_mulai', 'desc')->with('kelas','mapel')->paginate(10);


    $kelasMapel = DB::table('guru_kelas_mapels')
        ->select('guru_kelas_mapels.id', 'guru_kelas_mapels.guru_id', 'guru_kelas_mapels.kelas_id', 'guru_kelas_mapels.mapel_id', 'kelas.kelas')
        ->join('gurus', 'guru_kelas_mapels.guru_id', '=', 'gurus.id')
        ->join('kelas', 'guru_kelas_mapels.kelas_id', '=', 'kelas.id')
        ->join('mapels', 'guru_kelas_mapels.mapel_id', '=', 'mapels.id')
        ->where('guru_kelas_mapels.guru_id', $GuruId->id)
        ->get()
        ->unique('kelas_id');

        return view('guru.lembarSoal', compact('lembarSoals', 'GuruId', 'kelasMapel'));
    }

    public function getMapelByGuru($kelasId) {

        $user = auth()->user()->id;
        $guru = Guru::where('user_id',$user)->first();

        $kelasMapel = GuruKelasMapel::where('kelas_id', $kelasId)->where('guru_id',$guru->id)->with('mapel')->get();

        return response()->json($kelasMapel);

    }

    public function getLembarSoalBySiswa(Request $request)
    {
        $user = auth()->user();
    $siswa = Siswa::where('user_id', $user->id)->with('kelas')->first();

    if (!$siswa) {
        return redirect()->back()->with('error', 'Siswa tidak ditemukan.');
    }

    // Get id_tahun_ajaran from the guru table related to the lembar soal
    $lembarSoals = LembarSoal::with([
        'guru',
        'mapel',
        'kelas',
        'soal',
        'lembar_jawab' => function($query) use ($siswa) {
            $query->where('siswa_id', $siswa->id);
        },
        'lembar_jawab.jawaban'
    ])
    ->where('kelas_id', $siswa->kelas->id)
    ->whereHas('guru', function($query) use ($request) {
        $tahunAjaranId = session('tahun_ajaran'); // Make sure this session value is correct
        $query->where('id_tahun_ajaran', $tahunAjaranId);
    })
    -> orderBy('tanggal_mulai', 'desc')->paginate(10);


        return view('siswa.lembarSoal', compact('lembarSoals'));
    }

    public function getLembarSoalByKelasMapel(Request $request,Kelas $kelas,Mapel $mapel)
    {
        // $user = auth()->user();
        // $guru = Guru::where('user_id',$user->id);
        $lembarSoals = LembarSoal::with(['guru', 'mapel', 'kelas', 'soal', 'lembar_jawab'])
        // ->where('guru_id',$guru->id)
        ->whereHas('lembar_jawab', function ($query) use ($kelas, $mapel) {
            $query->where('kelas_id', $kelas->id)
                  ->where('mapel_id', $mapel->id);
        })
        -> orderBy('tanggal_mulai', 'desc')->paginate(10);

        return view('guru.lembar-jawab.lembar-soal', compact('lembarSoals'));
    }

    public function getLembarSoalByKelasMapelAdmin(Request $request,Guru $guru, Kelas $kelas, Mapel $mapel)
    {
        $lembarSoals = LembarSoal::with(['guru', 'mapel', 'kelas', 'soal', 'lembar_jawab'])
        ->where('guru_id',$guru->id)
        ->whereHas('lembar_jawab', function ($query) use ($kelas, $mapel) {
            $query->where('kelas_id', $kelas->id)
                  ->where('mapel_id', $mapel->id);
        })
        -> orderBy('tanggal_mulai', 'desc')->paginate(10);

        return view('admin.lembar-soal.lembar-soal', compact('lembarSoals'));
    }


    public function getTugasSoalBySiswa()
    {
        $user = auth()->user();
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

        return view('siswa.penugasan', compact('lembarSoals'));
    }

    public function getLembarSoalById(Request $request, LembarSoal $lembarSoal)
    {
        $currentDate = Carbon::now();

        // siswa
        $user = auth()->user();
        $siswa = Siswa::where('user_id', $user->id)->first();

        // Ambil lembar soal berdasarkan ID dengan relasi guru, mapel, dan kelas
        $lembarSoals = LembarSoal::with('guru', 'mapel', 'kelas')->find($lembarSoal->id);

        // Pastikan lembar soal ditemukan
        if (!$lembarSoals) {
            return redirect()->back()->with('error', 'Lembar soal tidak ditemukan.');
        }

        // Periksa apakah siswa sudah mengerjakan lembar soal ini
        $lembarJawab = LembarJawab::where('siswa_id', $siswa->id)
            ->where('lembar_soal_id', $lembarSoal->id)
            ->first();

        if ($lembarJawab) {
            return redirect()->route('penugasan-tersedia-siswa')->with('error', 'Anda sudah mengerjakan lembar soal ini.');
        }

        // Pastikan tanggal_mulai dan tanggal_selesai ada dalam format Carbon
        $tanggalMulai = Carbon::parse($lembarSoals->tanggal_mulai);
        $tanggalSelesai = Carbon::parse($lembarSoals->tanggal_selesai);

        // Periksa apakah tanggal saat ini berada di antara tanggal_mulai dan tanggal_selesai
        if ($currentDate->lt($tanggalMulai) || $currentDate->gt($tanggalSelesai)) {
            return redirect()->back()->with('error', 'Lembar soal tidak dapat diakses saat ini. Harap mengerjakan sesuai waktu yang telah ditentukan.');
        }

        // Lanjutkan dengan menampilkan lembar soal jika rentang tanggal sudah sesuai
        $soals = $lembarSoals->soal()->paginate(10); // Paginasi dengan 10 data per halaman

        // Format tanggal mulai dan selesai jika diperlukan
        $formattedTanggalMulai = $tanggalMulai->translatedFormat('l, d F Y H:i');
        $formattedTanggalSelesai = $tanggalSelesai->translatedFormat('l, d F Y H:i');

        // Hitung durasi yang tersisa
        $now = Carbon::now();
        $remainingTime = $tanggalSelesai->diff($now);
        $durasi = $remainingTime->format('%d hari, %h jam, %i menit, %s detik');

        return view('siswa.soal.index', compact('lembarSoals', 'soals', 'formattedTanggalMulai', 'formattedTanggalSelesai', 'durasi'));
    }








    public function addLembarSoal(Request $request){
        // Validasi input
        $validator = Validator::make($request->all(), [
            'kelas_id' => ['required', 'exists:kelas,id'],
            'mapel_id' => ['required', 'exists:mapels,id'],
            'tanggal_mulai' => ['required', 'date'],
            'tanggal_selesai' => ['required', 'date', 'after_or_equal:tanggal_mulai'],
            'tipe_soal' => ['required'],
            'soal.*.tipe_soal' => ['required', 'string'],
            'soal.*.soal' => ['required', 'string'],
            'soal.*.kunci_jawaban' => ['required', 'string'],
            'soal.*.poin' => ['required', 'integer'],
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        // Mulai transaksi database
        DB::beginTransaction();

        try {
            $user = auth()->user();
            $guru = Guru::where('user_id', $user->id)->first();

            if (!$guru) {
                throw new \Exception('Guru not found for the authenticated user.');
            }

            // Membuat lembar soal baru
            $lembarSoal = LembarSoal::create([
                'guru_id' => $guru->id,
                'kelas_id' => $request->input('kelas_id'),
                'mapel_id' => $request->input('mapel_id'),
                'tanggal_mulai' => $request->input('tanggal_mulai'),
                'tanggal_selesai' => $request->input('tanggal_selesai'),
                'tipe_soal' => $request->input('tipe_soal'),
            ]);

            // Membuat soal-soal untuk lembar soal tersebut
            $soalData = $request->input('soal', []);
            foreach ($soalData as $soal) {
                Soal::create([
                    'lembar_soal_id' => $lembarSoal->id,
                    'tipe_soal' => $soal['tipe_soal'],
                    'soal' => $soal['soal'],
                    'kunci_jawaban' => $soal['kunci_jawaban'],
                    'poin' => $soal['poin'],
                    'pilihan_a' => $soal['pilihan_a'] ?? null,
                    'pilihan_b' => $soal['pilihan_b'] ?? null,
                    'pilihan_c' => $soal['pilihan_c'] ?? null,
                    'pilihan_d' => $soal['pilihan_d'] ?? null,
                    'pilihan_e' => $soal['pilihan_e'] ?? null,
                ]);


            }

            // Commit transaksi
            DB::commit();
            Alert::success('Berhasil', 'Lembar soal dan soal berhasil ditambahkan');
            return back();
        } catch (\Exception $e) {
            // Rollback transaksi jika terjadi kesalahan
            DB::rollBack();
            Alert::error('Terjadi kesalahan', $e->getMessage());
            return back();
        }
    }



    public function updateLembarSoal(Request $request, LembarSoal $lembarSoal)
    {
        // Validasi input
        $validator = Validator::make($request->all(), [
            'kelas_id' => ['required', 'exists:kelas,id'],
            'mapel_id' => ['required', 'exists:mapels,id'],
            'tanggal_mulai' => ['required', 'date'],
            'tanggal_selesai' => ['required', 'date'],
            'tipe_soal' => ['required'],
            'soal.*.tipe_soal' => ['required', 'string'],
            'soal.*.soal' => ['required', 'string'],
            'soal.*.kunci_jawaban' => ['required', 'string'],
            'soal.*.poin' => ['required', 'integer'],
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        // Mulai transaksi database
        DB::beginTransaction();

        try {
            $user = auth()->user();
            $guru = Guru::where('user_id', $user->id)->first();
            // Update lembar soal
            $lembarSoal->update([
                'guru_id' => $guru->id,
                'kelas_id' => $request->input('kelas_id'),
                'mapel_id' => $request->input('mapel_id'),
                'tipe_soal' =>$request->input('tipe_soal'),
                'tanggal_mulai' => $request->input('tanggal_mulai'),
                'tanggal_selesai' => $request->input('tanggal_selesai'),
            ]);

            // Hapus soal-soal lama
            $lembarSoal->soal()->delete();

            // Buat soal-soal baru
            $soalData = $request->input('soal', []);

            foreach ($soalData as $data) {
                $lembarSoal->soal()->create([
                    'tipe_soal' => $data['tipe_soal'],
                    'soal' => $data['soal'],
                    'kunci_jawaban' => $data['kunci_jawaban'],
                    'poin' => $data['poin'],
                ]);
            }

            // Commit transaksi
            DB::commit();

            Alert::success('Berhasil', 'Lembar soal dan soal berhasil diupdate');
            return back();
        } catch (\Exception $e) {
            // Rollback transaksi jika terjadi kesalahan
            DB::rollBack();

            Alert::error('Terjadi kesalahan', $e->getMessage());
            return back();
        }
    }

    public function deleteLembarSoal(LembarSoal $lembarSoal){
        // Mulai transaksi database
        DB::beginTransaction();

        try {
            // Hapus soal-soal terkait
            $lembarSoal->soal()->delete();

            // Hapus lembar soal
            $lembarSoal->delete();

            // Commit transaksi
            DB::commit();
            Alert::success('Berhasil', 'Lembar soal dan soal berhasil dihapus');
            return back();
        } catch (\Exception $e) {
            // Rollback transaksi jika terjadi kesalahan
            DB::rollBack();
            Alert::error('Terjadi kesalahan', $e->getMessage());
            return back();
        }
    }
}
