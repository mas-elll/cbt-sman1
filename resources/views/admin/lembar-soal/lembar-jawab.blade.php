@extends('layout.master')
@section('title','Manajemen Lembar Jawab')
@section('content')
@include('sweetalert::alert')
<!-- Page Heading -->
<h1 class="h3 mb-2 text-gray-800"></h1>
<nav aria-label="breadcrumb">
    <ol class="breadcrumb">
      <li class="breadcrumb-item"><a href="/admin/">Home</a></li>
      <li class="breadcrumb-item"><a href="/admin/lembar-soal/">Kelas</a></li>
      <li class="breadcrumb-item active" aria-current="page">Lembar Jawab</li>
    </ol>
</nav>
<!-- DataTales Example -->
<div class="card shadow mb-4">
    <div class="card-header py-3">
        <h6 class="m-0 font-weight-bold text-primary">Tabel Data Lembar Jawab Siswa</h6>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            @if($lembarJawabs->count() > 0)
                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            {{-- <th>Guru</th> --}}
                            <th>Siswa</th>
                            {{-- <th>Mata Pelajaran</th>
                            <th>Kelas</th>
                            <th>Tanggal Mulai</th>
                            <th>Tanggal Selesai</th> --}}
                            {{-- <th>Tipe Soal</th> --}}
                            <td>Nilai</td>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($lembarJawabs as $lembarJawab)
                        <tr>
                            {{-- <td>{{ $lembarJawab->lembar_soal->guru->nama }}</td> --}}
                            <td>{{ $lembarJawab->siswa->nama }}</td>
                            {{-- <td>{{ $lembarJawab->lembar_soal->mapel->nama }}</td>
                            <td>{{ $lembarJawab->lembar_soal->kelas->kelas }}</td>
                            <td>{{ $lembarJawab->lembar_soal->tanggal_mulai }}</td>
                            <td>{{ $lembarJawab->lembar_soal->tanggal_selesai }}</td>
                            <td>{{ $lembarJawab->lembar_soal->tipe_soal }}</td> --}}
                            <td>{{ $lembarJawab->nilai }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
                <div class="d-flex justify-content-center mt-4">
                    {{ $lembarJawabs->links('pagination::bootstrap-4') }} <!-- Pagination links -->
                </div>
            @else
                <div class="alert alert-info" role="alert">
                    Tidak ada data penugasan.
                </div>
            @endif
        </div>
    </div>
</div>

<!-- Modals -->
@foreach ($lembarJawabs as $lembarJawab)
<div class="modal fade" id="soalModal{{ $lembarJawab->id }}" tabindex="-1" aria-labelledby="soalModalLabel{{ $lembarJawab->id }}" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="soalModalLabel{{ $lembarJawab->id }}">Koreksi Lembar Jawab ID: {{ $lembarJawab->id }}</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="{{ route('update-nilai-jawaban', $lembarJawab) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-body">
                    <div class="container">
                        @foreach ($lembarJawab->lembar_soal->soal as $soal)
                        <div class="form-group">
                            <div class="d-flex justify-content-between">
                                <label>Soal {{ $loop->iteration }}</label>
                                <p>poin {{ $soal->poin }}</p>
                            </div>
                            <p>{!! nl2br(e($soal->soal)) !!}</p>
                            <label>Jawaban Siswa</label>
                            @php
                                $jawabanSiswa = $lembarJawab->jawaban->firstWhere('soal_id', $soal->id);
                            @endphp
                            @if($jawabanSiswa)
                                <input type="text" class="form-control mb-2" value="{{ $jawabanSiswa->jawaban }}" readonly>
                                <label class="mb-2" for="kunci_jawaban">Kunci Jawaban</label>
                                <input type="text" class="form-control mb-2" value="{{ $soal->kunci_jawaban }}" readonly>
                                <label class="mb-2">Poin</label>
                                <input type="hidden" name="jawaban[{{ $loop->index }}][id]" value="{{ $jawabanSiswa->id }}">
                                <input type="number" class="form-control mb-2" name="jawaban[{{ $loop->index }}][poin]" value="{{ $jawabanSiswa->poin }}" max="{{ $soal->poin }}">
                            @else
                                <p>Belum ada jawaban.</p>
                            @endif
                        </div>
                        <hr>
                        @endforeach
                        <div class="form-group">
                            <label>Beri masukan</label>
                            <textarea name="feedback" class="form-control" rows="3"></textarea>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <div class="d-flex">
                        <button type="submit" class="btn btn-primary mx-2">Simpan</button>
                        <button type="button" class="btn btn-secondary mx-2" data-dismiss="modal">Close</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
@endforeach


@endsection
