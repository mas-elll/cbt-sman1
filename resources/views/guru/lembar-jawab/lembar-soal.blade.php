@extends('layout.master')
@section('title','Manajemen Lembar Jawab')
@section('content')
@include('sweetalert::alert')
<!-- Page Heading -->
<h1 class="h3 mb-2 text-gray-800"></h1>
<nav aria-label="breadcrumb">
    <ol class="breadcrumb">
      <li class="breadcrumb-item"><a href="/guru/">Home</a></li>
      <li class="breadcrumb-item"><a href="/guru/lembar-jawab/">Kelas</a></li>
      <li class="breadcrumb-item active" aria-current="page">Lembar Jawab</li>
    </ol>
</nav>
<!-- DataTales Example -->
<div class="card shadow mb-4">
    <div class="card-header py-3">
        <h6 class="m-0 font-weight-bold text-primary">Tabel Data Lembar Jawab Siswa</h6>
    </div>
    <div class="card-body">
        <div class="card-body">
            <div class="table-responsive">
                @if($lembarSoals->count() > 0)
                    <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                        <thead>
                            <tr>
                                <th>Mata Pelajaran</th>
                                <th>Kelas</th>
                                <th>Tanggal Mulai</th>
                                <th>Tanggal Selesai</th>
                                <th>Tipe Soal</th>
                                <th>Opsi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($lembarSoals as $lembarSoal)
                            <tr>
                                <td>{{ $lembarSoal->mapel->nama }}</td>
                                <td>{{ $lembarSoal->kelas->kelas }}</td>
                                <td>{{ $lembarSoal->tanggal_mulai }}</td>
                                <td>{{ $lembarSoal->tanggal_selesai }}</td>
                                <td>{{ $lembarSoal->tipe_soal }}</td>
                                <td class="d-flex">
                                    <a href="{{ route('lembar-jawab-by-id',$lembarSoal) }}" class="btn btn-info">
                                        Lihat Jawaban
                                    </a>
                                    <!-- Link Cetak PDF -->
                                    <a href="{{ route('show-pdf-all-siswa-kelas-mapel', ['kelas' => $lembarSoal->kelas_id, 'mapel' => $lembarSoal->mapel_id, 'lembarSoal' => $lembarSoal->id]) }}" class="btn btn-info mx-2">
                                        Cetak PDF
                                    </a> 
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                    <div class="d-flex justify-content-center mt-4">
                        {{ $lembarSoals->links('pagination::bootstrap-4') }} <!-- Pagination links -->
                    </div>
                @else
                    <div class="alert alert-info" role="alert">
                        Tidak ada data penugasan.
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
