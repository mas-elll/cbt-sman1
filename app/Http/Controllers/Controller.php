<?php

namespace App\Http\Controllers;

use App\Models\Guru;
use App\Models\Kelas;
use App\Models\Mapel;
use App\Models\Siswa;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;

class Controller extends BaseController
{
    use AuthorizesRequests, ValidatesRequests;

    public function DashboardAdmin(){
        $gurus = Guru::count();
        $kelas = Kelas::count();
        $siswas = Siswa::count();
        $mapels = Mapel::count();
        
        return view('admin.index',compact('gurus','kelas','siswas','mapels'));
    }
    public function DashboardGuru(Request $request){
        $UserId = auth()->user()->id;
        $guru = Guru::where('user_id', $UserId)
                    ->with('kelasMapel.kelas', 'kelasMapel.mapel')
                    ->first();
    
        // Hitung jumlah kelas dan mapel
        $jumlahKelas = $guru->kelasMapel->pluck('kelas_id')->unique()->count();
        $jumlahMapel = $guru->kelasMapel->pluck('mapel_id')->unique()->count();
    
        return view('guru.index', compact('guru', 'jumlahKelas', 'jumlahMapel'));
    }
    
}
