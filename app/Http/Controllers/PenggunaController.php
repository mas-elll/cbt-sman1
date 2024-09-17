<?php

namespace App\Http\Controllers;

use App\Actions\Fortify\PasswordValidationRules;
use App\Models\Guru;
use App\Models\GuruKelasMapel;
use App\Models\Kelas;
use App\Models\LembarSoal;
use App\Models\Mapel;
use App\Models\Siswa;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use RealRashid\SweetAlert\Facades\Alert;
use App\Models\TahunAjaran;
class PenggunaController extends Controller
{
    use PasswordValidationRules;



    public function getAllGuru(Request $request)
    {
        $search = $request->input('search');
        $tahunAjaran = TahunAjaran::all();
        $gurus = Guru::with('kelasMapel', 'kelasMapel.mapel', 'kelasMapel.kelas','kelasMapel.tahunAjaran','user')->where('nama', 'LIKE', "%{$search}%")->paginate(10);
        $kelas = Kelas::all();
        $mapels = Mapel::all();
        return view('admin.guru', compact('gurus', 'kelas', 'mapels','tahunAjaran'));
    }

    public function AddGuru(Request $request){
        // Validasi input
        $validator = Validator::make($request->all(), [
            'name' => ['required', 'string', 'max:255'],
            'email' => [
                'required',
                'string',
                'email',
                'max:255',
                Rule::unique(User::class),
            ],
            'password' => $this->passwordRules(),
            'role' => ['required', 'string'],
            'nomer_induk' => ['required', 'string'],
            'id_tahun_ajaran' => ['required', 'exists:tahun_ajaran,id'],
            'kelas_id' => 'required|array',
            'mapel_id' => 'required|array',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        // Mulai transaksi database
        DB::beginTransaction();

        try {
            // Membuat user baru
            $user = User::create([
                'name' => $request->input('name'),
                'email' => $request->input('email'),
                'password' => Hash::make($request->input('password')),
            ]);

            // Assign role ke user
            $user->assignRole($request->input('role'));

            // Membuat guru baru
            $guru = Guru::create([
                'nama' => $request->input('name'),
                'nomer_induk' => $request->input('nomer_induk'),
                'id_tahun_ajaran' => $request->input('id_tahun_ajaran'),
                'user_id' => $user->id,
            ]);

            // Sinkronisasi kelas dan mata pelajaran
            $kelas_ids = $request->input('kelas_id', []);
            $mapel_ids = $request->input('mapel_id', []);
            $tahun_ajaran_id = $request->input('id_tahun_ajaran');
            // Buat array untuk menyimpan hubungan guru-kelas-mapel
            $data = [];
            foreach ($kelas_ids as $index => $kelas_id) {
                if (isset($mapel_ids[$index])) {
                    $data[] = ['kelas_id' => $kelas_id, 'mapel_id' => $mapel_ids[$index], 'id_tahun_ajaran' => $tahun_ajaran_id];
                }
            }

            // Sinkronisasi data pivot
            $guru->guru_kelas_mapel()->sync($data);

            // Commit transaksi
            DB::commit();
            toast('Berhasil menambah data guru','success');
            return back();
        } catch (\Exception $e) {
            // Rollback transaksi jika terjadi kesalahan
            DB::rollBack();
            toast('Terjadi kesalahan' . $e->getMessage(), 'error');
            return back();
        }
    }
    public function updateGuru(Request $request, Guru $guru){
        // Validasi input
        // dd($request->all());
        $validator = Validator::make($request->all(), [
            'nama' => ['required', 'string', 'max:255'],
            'nomer_induk' => ['required', 'string'],
            'kelas_id' => 'required|array',
            'mapel_id' => 'required|array',
        ]);

        if ($validator->fails()) {
            return back()->with('toast_error', $validator->messages()->all()[0])->withInput();
        }
        // dd($request->all());
        // Mulai transaksi database
        DB::beginTransaction();

        try {
            // Update data guru
            $guru->nama = $request->input('nama');
            $guru->nomer_induk = $request->input('nomer_induk');
            $guru->id_tahun_ajaran = $request->input('tahun_ajaran');
            $guru->save();

            // Sinkronisasi kelas dan mata pelajaran
            $kelas_ids = $request->input('kelas_id', []);
            $mapel_ids = $request->input('mapel_id', []);
            $tahun_ajaran = $request->input('tahun_ajaran');

            // Buat array untuk menyimpan hubungan guru-kelas-mapel
            $data = [];
            foreach ($kelas_ids as $index => $kelas_id) {
                if (isset($mapel_ids[$index])) {
                    $data[] = ['kelas_id' => $kelas_id, 'mapel_id' => $mapel_ids[$index], 'id_tahun_ajaran' => $tahun_ajaran,];
                }
            }

            // Sinkronisasi data pivot
            $guru->guru_kelas_mapel()->detach();
            $guru->guru_kelas_mapel()->sync($data);

            // Commit transaksi
            DB::commit();
            toast('Berhasil mengupdate data guru','success');
            return back();
        } catch (\Exception $e) {
            // Rollback transaksi jika terjadi kesalahan
            DB::rollBack();
            toast('Terjadi kesalahan' . $e->getMessage(), 'error');
            return back();
        }
    }
    public function deleteGuru(Guru $guru)
    {
        // Mulai transaksi database
        DB::beginTransaction();

        try {
            // Hapus relasi guru-kelas-mapel
            $guru->guru_kelas_mapel()->detach();

            // Hapus user terkait
            if ($guru->user) {
                $guru->user->delete();
            }

            // Hapus data guru
            $guru->delete();

            // Commit transaksi
            DB::commit();
            toast('Berhasil menghapus data guru', 'success');
            return back();
        } catch (\Exception $e) {
            // Rollback transaksi jika terjadi kesalahan
            DB::rollBack();
            toast('Terjadi kesalahan: ' . $e->getMessage(), 'error');
            return back();
        }
    }

    public function getAllKelas(Request $request)
    {
        $search = $request->input('search');

        $kelas = Kelas::where('kelas', 'LIKE', "%{$search}%")
                      ->paginate(10);

        return view('admin.siswa.kelas', compact('kelas', 'search'));
    }

    public function getSiswaByKelas(Request $request, Kelas $kelas){
        $search = $request->input('search');
        $kelases = Kelas::all();
        $siswas =  Siswa::where('kelas_id', $kelas->id)->where('nama', 'LIKE', "%{$search}%")->paginate(10);
        $currentKelas = Kelas::where('id',$kelas->id)->get();

        $kelas= $kelas;
        return view('admin.siswa.siswa',compact('siswas','kelases','currentKelas','kelas'));
    }

    public function AddSiswa(Request $request){
        // Validasi input
        $validator = Validator::make($request->all(), [
            'name' => ['required', 'string', 'max:255'],
            'email' => [
                'required',
                'string',
                'email',
                'max:255',
                Rule::unique(User::class),
            ],
            'password' => $this->passwordRules(),
            'role' => ['required', 'string'],
            'nomer_induk' => ['required', 'string'],
            'kelas_id' => 'required',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        // Mulai transaksi database
        DB::beginTransaction();

        try {
            // Membuat user baru
            $user = User::create([
                'name' => $request->input('name'),
                'email' => $request->input('email'),
                'password' => Hash::make($request->input('password')),
            ]);

            // Assign role ke user
            $user->assignRole($request->input('role'));

            // Membuat siswa baru
            $siswa = Siswa::create([
                'nama' => $request->input('name'),
                'nomer_induk' => $request->input('nomer_induk'),
                'user_id' => $user->id,
                'kelas_id' => $request->input('kelas_id'),
            ]);

            DB::commit();
            // Commit transaksi
            toast('Berhasil menambah data siswa','success');
            return back();
        } catch (\Exception $e) {
            // Rollback transaksi jika terjadi kesalahan
            DB::rollBack();
            toast('Terjadi kesalahan' . $e->getMessage(), 'error');
            return back();
        }
    }
    public function updateSiswa(Request $request, Siswa $siswa)
    {
        // Validasi input
        $validator = Validator::make($request->all(), [
            'nama' => ['required', 'string', 'max:255'],
            'nomer_induk' => ['required', 'string'],
            'kelas_id' => 'required',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        // Mulai transaksi database
        DB::beginTransaction();

        try {
            // Update siswa
            $siswa->update([
                'nama' => $request->input('nama'),
                'nomer_induk' => $request->input('nomer_induk'),
                'kelas_id' => $request->input('kelas_id'),
            ]);
            // Update user name
            $user = User::find($siswa->user_id);
            if ($user) {
                $user->update([
                    'name' => $request->input('nama'),
                ]);
            }

            DB::commit();
            // Commit transaksi
            toast('Berhasil mengupdate data siswa', 'success');
            return back();
        } catch (\Exception $e) {
            // Rollback transaksi jika terjadi kesalahan
            DB::rollBack();
            toast('Terjadi kesalahan: ' . $e->getMessage(), 'error');
            return back();
        }
    }
    public function deleteSiswa(Siswa $siswa)
    {
        // Mulai transaksi database
        DB::beginTransaction();

        try {
            // Find related user
            $user = User::find($siswa->user_id);

            // Delete siswa
            $siswa->delete();

            // Delete user
            if ($user) {
                $user->delete();
            }

            DB::commit();
            // Commit transaksi
            toast('Berhasil menghapus data siswa', 'success');
            return back();
        } catch (\Exception $e) {
            // Rollback transaksi jika terjadi kesalahan
            DB::rollBack();
            toast('Terjadi kesalahan: ' . $e->getMessage(), 'error');
            return back();
        }
    }


}
