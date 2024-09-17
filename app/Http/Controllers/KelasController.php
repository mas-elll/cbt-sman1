<?php

namespace App\Http\Controllers;

use App\Models\Guru;
use App\Models\GuruKelasMapel;
use App\Models\Kelas;
use App\Models\TahunAjaran;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class KelasController extends Controller
{
    public function getAllKelas(Request $request){
        $search = $request->input('search');
        $kelas = Kelas::where('kelas', 'LIKE', "%{$search}%")->paginate(10);
        return view('admin.kelas',compact('kelas'));
    }

    public function pilihTahunAjaran()
    {
        // Ambil semua tahun ajaran
        $tahunAjarans = TahunAjaran::all();
        return view('admin.lembar-soal.pilih_tahun', compact('tahunAjarans'));
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
        return redirect()->route('get-kelas-lembar-soal-admin');
    }

    public function getAllKelasByGuru(Request $request)
    {
        $search = $request->input('search');
        $tahunAjaranId = session('tahun_ajaran');
        $kelasMapels = GuruKelasMapel::with(['kelas', 'mapel', 'guru'])
                    ->where('id_tahun_ajaran', $tahunAjaranId)
                    ->where(function ($query) use ($search) {
                        $query->whereHas('kelas', function ($query) use ($search) {
                            $query->where('kelas', 'LIKE', "%{$search}%");
                        })
                        ->orWhereHas('mapel', function ($query) use ($search) {
                            $query->where('nama', 'LIKE', "%{$search}%");
                        })
                        ->orWhereHas('guru', function ($query) use ($search) {
                            $query->where('nama', 'LIKE', "%{$search}%");
                        });
                    })
                    ->paginate(10);

        return view('admin.lembar-soal.index', compact('kelasMapels'));
    }

    public function addKelas(Request $request)
    {
        // Validasi input
        $validator = Validator::make($request->all(), [
            'kelas' => ['required', 'string', 'max:255'],
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        try {
            // Membuat kelas baru
            Kelas::create([
                'kelas' => $request->input('kelas'),
            ]);

            toast('Berhasil menambah data kelas', 'success');
            return back();
        } catch (\Exception $e) {
            toast('Terjadi kesalahan: ' . $e->getMessage(), 'error');
            return back();
        }
    }

    public function updateKelas(Request $request, Kelas $kelas)
    {
        // Validasi input
        $validator = Validator::make($request->all(), [
            'kelas' => ['required', 'string', 'max:255'],
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        try {
            // Update kelas
            $kelas->update([
                'kelas' => $request->input('kelas'),
            ]);

            toast('Berhasil mengupdate data kelas', 'success');
            return back();
        } catch (\Exception $e) {
            toast('Terjadi kesalahan: ' . $e->getMessage(), 'error');
            return back();
        }
    }

    public function deleteKelas(Kelas $kelas)
    {
        try {
            // Hapus kelas
            $kelas->delete();

            toast('Berhasil menghapus data kelas', 'success');
            return back();
        } catch (\Exception $e) {
            toast('Terjadi kesalahan: ' . $e->getMessage(), 'error');
            return back();
        }
    }

}
