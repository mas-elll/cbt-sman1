<?php

namespace App\Http\Controllers;

use App\Models\Mapel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class MataPelajaranController extends Controller
{
    public function getAllMapel(Request $request)
    {
        $search = $request->input('search');
        $mapels = Mapel::where(function ($query) use ($search) {
            $query->where('nama', 'LIKE', "%{$search}%")
                  ->orWhere('kode_mapel', 'LIKE', "%{$search}%");
        })->paginate(10);
        return view('admin.mapel', compact('mapels'));
    }

    public function addMapel(Request $request)
    {
        // Validasi input
        $validator = Validator::make($request->all(), [
            'nama' => ['required', 'string', 'max:255'],
            'kode_mapel' => ['required', 'string', 'max:255'],
            'deskripsi' => ['required', 'string'],
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        try {
            // Membuat mata pelajaran baru
            Mapel::create([
                'nama' => $request->input('nama'),
                'kode_mapel' => $request->input('kode_mapel'),
                'deskripsi' => $request->input('deskripsi'),
            ]);

            toast('Berhasil menambah data mata pelajaran', 'success');
            return back();
        } catch (\Exception $e) {
            toast('Terjadi kesalahan: ' . $e->getMessage(), 'error');
            return back();
        }
    }

    public function updateMapel(Request $request, Mapel $mapel)
    {
        // Validasi input
        $validator = Validator::make($request->all(), [
            'nama' => ['required', 'string', 'max:255'],
            'kode_mapel' => ['required', 'string', 'max:255'],
            'deskripsi' => ['required', 'string'],
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        try {
            // Update mata pelajaran
            $mapel->update([
                'nama' => $request->input('nama'),
                'kode_mapel' => $request->input('kode_mapel'),
                'deskripsi' => $request->input('deskripsi'),
            ]);

            toast('Berhasil mengupdate data mata pelajaran', 'success');
            return back();
        } catch (\Exception $e) {
            toast('Terjadi kesalahan: ' . $e->getMessage(), 'error');
            return back();
        }
    }

    public function deleteMapel(Mapel $mapel)
    {
        try {
            // Hapus mata pelajaran
            $mapel->delete();

            toast('Berhasil menghapus data mata pelajaran', 'success');
            return back();
        } catch (\Exception $e) {
            toast('Terjadi kesalahan: ' . $e->getMessage(), 'error');
            return back();
        }
    }
}
