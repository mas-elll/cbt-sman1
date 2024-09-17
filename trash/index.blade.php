@extends('layout.master')
@section('title','Manajemen Laporan Siswa')
@section('content')
@include('sweetalert::alert')
<!-- Page Heading -->
<h1 class="h3 mb-2 text-gray-800">Tabel Siswa</h1>
<p class="mb-4">Tabel Pengelolaan Data Siswa, <br>Silahkan memilih kelas terlebih dahulu</p>
<nav aria-label="breadcrumb">
    <ol class="breadcrumb">
      <li class="breadcrumb-item"><a href="/guru/">Home</a></li>
      <li class="breadcrumb-item active" aria-current="page">Kelas</li>
    </ol>
</nav>
<!-- DataTales Example -->
<div class="card shadow mb-4">
    <div class="card-header py-3">
        <h6 class="m-0 font-weight-bold text-primary">Tabel Data Siswa</h6>
    </div>
    <div class="card-body">
        <form action="{{ route('get-kelas-lembar-jawab') }}" method="GET" class="mb-3">
            <div class="input-group">
                <input type="text" name="search" class="form-control" placeholder="Cari Kelas" value="{{ request('search') }}">
                <div class="input-group-append">
                    <button class="btn btn-primary" type="submit">Cari</button>
                </div>
            </div>
        </form>
        <div class="table-responsive">
            @if($kelasMapels->count() > 0)
                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>Kelas Dan Mapel</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($kelasMapels as $kelasMapel)
                        <tr>
                            <td>
                                <a href="{{ route('get-lembar-soal-by-kelas-mapel', ['kelas' => $kelasMapel->kelas->id, 'mapel' => $kelasMapel->mapel->id]) }}" class="text-decoration-none">{{ $kelasMapel->kelas->kelas }} - {{ $kelasMapel->mapel->nama }}</a>
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
                <div class="d-flex justify-content-center mt-4">
                    {{ $kelasMapels->appends(['search' => request('search')])->links('pagination::bootstrap-4') }} <!-- Pagination links -->
                </div>
            @else
                <div class="alert alert-info" role="alert">
                    Tidak ada data kelas, silahkan buat kelas.
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
