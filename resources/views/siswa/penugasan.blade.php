@extends('layout.master')
@section('title', 'Selamat datang Guru')
@section('content')
@include('sweetalert::alert')

<!-- Page Heading -->
<h1 class="h3 mb-2 text-gray-800">Tabel Penugasan Siswa</h1>
<p class="mb-4">Tabel lembar soal penugasan siswa yang tersedia.</p>

<!-- DataTales Example -->
<div class="card shadow mb-4">
    <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Tabel Data Lembar Soal</h6>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                <thead>
                    <tr>
                        <th>Guru</th>
                        <th>Mata Pelajaran</th>
                        <th>Kelas</th>
                        <th>Tanggal Mulai</th>
                        <th>Tanggal Selesai</th>
                        <th>Tipe Soal</th>
                        <th>Soal</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($lembarSoals as $lembarSoal)
                    <tr>
                        <td>{{ $lembarSoal->guru->nama }}</td>
                        <td>{{ $lembarSoal->mapel->nama }}</td>
                        <td>{{ $lembarSoal->kelas->kelas }}</td>
                        <td>{{ $lembarSoal->tanggal_mulai }}</td>
                        <td>{{ $lembarSoal->tanggal_selesai }}</td>
                        <td>{{ $lembarSoal->tipe_soal }}</td>
                        <td>
                            <a href="{{ route('lembar-soal-pengerjaan', $lembarSoal) }}" type="button" class="btn btn-info">
                                Mulai
                            </a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection