@extends('layout.master')
@section('title','Manajemen Lembar Jawab')
@section('content')
@include('sweetalert::alert')
<!-- Page Heading -->
<h1 class="h3 mb-2 text-gray-800">Tabel Laporan Siswa</h1>
<p class="mb-4">Tabel Pengelolaan Laporan Siswa, <br>Silahkan memilih mata pelajaran terlebih dahulu</p>
<nav aria-label="breadcrumb">
    <ol class="breadcrumb">
      <li class="breadcrumb-item"><a href="/siswa/">Home</a></li>
      <li class="breadcrumb-item active" aria-current="page">Laporan</li>
    </ol>
</nav>
<!-- DataTales Example -->
<div class="card shadow mb-4">
    <div class="card-header py-3">
        <h6 class="m-0 font-weight-bold text-primary">Tabel Data Mata Pelajaran</h6>
    </div>
    <div class="card-body">
        <form action="{{ route('mapel-by-kelas-siswa') }}" method="GET" class="mb-3">
            <div class="input-group">
                <input type="text" name="search" class="form-control" placeholder="Cari Kelas" value="{{ request('search') }}">
                <div class="input-group-append">
                    <button class="btn btn-primary" type="submit">Cari</button>
                </div>
            </div>
        </form>
        <div class="table-responsive">
            @if($mapels->count() > 0)
                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>Guru Dan Mapel</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($mapels as $mapel)
                        <tr>
                            <td>
                                <a href="{{ route('laporan-lembar-jawab-by-mapel', ['kelas' => $mapel->kelas->id, 'mapel' => $mapel->mapel->id]) }}" class="text-decoration-none">{{ $mapel->guru->nama }} - {{ $mapel->mapel->nama }} [{{ $mapel->mapel->kode_mapel }}]</a>
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
                <div class="d-flex justify-content-center mt-4">
                    {{ $mapels->appends(['search' => request('search')])->links('pagination::bootstrap-4') }} <!-- Pagination links -->
                </div>
            @else
                <div class="alert alert-info" role="alert">
                    Tidak ada mata pelajaran.
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
