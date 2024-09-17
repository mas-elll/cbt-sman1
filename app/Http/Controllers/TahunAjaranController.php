<?php

namespace App\Http\Controllers;

use App\Models\TahunAjaran;
use Illuminate\Http\Request;

class TahunAjaranController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('search');
        $tahun_ajaran = TahunAjaran::where('tahun_ajaran', 'LIKE', "%{$search}%")->paginate(10);
        return view('admin.tahunajaran', compact('tahun_ajaran'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'tahun_ajaran' => 'required|string|max:255',
        ]);

        TahunAjaran::create($request->all());

        return redirect()->route('tahun_ajaran.index')->with('success', 'Tahun Ajaran berhasil ditambahkan.');
    }

    public function update(Request $request, TahunAjaran $tahun_ajaran)
    {
        $request->validate([
            'tahun_ajaran' => 'required|string|max:255',
        ]);

        $tahun_ajaran->update($request->all());

        return redirect()->route('tahun_ajaran.index')->with('success', 'Tahun Ajaran berhasil diperbarui.');
    }

    public function destroy(TahunAjaran $tahun_ajaran)
    {
        $tahun_ajaran->delete();

        return redirect()->route('tahun_ajaran.index')->with('success', 'Tahun Ajaran berhasil dihapus.');
    }
}
