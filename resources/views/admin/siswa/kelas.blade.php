@extends('layout.master')
@section('title','Manajemen Siswa')
@section('content')
@include('sweetalert::alert')
<!-- Page Heading -->
<h1 class="h3 mb-2 text-gray-800"></h1>

<!-- DataTales Example -->
<nav aria-label="breadcrumb">
    <ol class="breadcrumb">
      <li class="breadcrumb-item"><a href="/admin/">Home</a></li>
      <li class="breadcrumb-item active" aria-current="page">Data Kelas Siswa</li>
    </ol>
</nav>
<div class="card shadow mb-4">
    <div class="card-header py-3">
        <h6 class="m-0 font-weight-bold text-primary">Tabel Data Siswa</h6>
    </div>
    <div class="card-body">
        <!-- Search Form -->
        <form action="{{ route('kelas-siswa-admin') }}" method="GET" class="mb-3">
            <div class="input-group">
                <input type="text" name="search" class="form-control" placeholder="Cari kelas" value="{{ request('search') }}">
                <div class="input-group-append">
                    <button class="btn btn-primary" type="submit">Cari</button>
                </div>
            </div>
        </form>
        <div class="table-responsive">
            @if($kelas->count() > 0)
                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>Kelas</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($kelas as $kls)
                        <tr>
                            <td>
                                <a href="{{ route('siswa-by-kelas-admin',$kls) }}" class="text-decoration-none">
                                    {{ $kls->kelas }}
                                </a>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
                <div class="d-flex justify-content-center mt-4">
                    {{ $kelas->appends(['search' => request('search')])->links('pagination::bootstrap-4') }} <!-- Pagination links -->
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
