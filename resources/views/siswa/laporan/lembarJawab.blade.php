@extends('layout.master')
@section('title','Manajemen Lembar Jawab')
@section('content')
@include('sweetalert::alert')
@push('style')
<style>
    .dynamic-textarea {
        width: 100%;
        max-width: 100%; /* Ensures it doesn't go beyond the container */
        box-sizing: border-box; /* Includes padding and border in the element's total width and height */
        resize: none; /* Prevents resizing */
    }
</style>
@endpush
<!-- Page Heading -->
<h1 class="h3 mb-2 text-gray-800">Tabel Lembar Jawab Siswa</h1>
<p class="mb-4">Tabel Pengelolaan Data Lembar Jawab Siswa</p>

<!-- DataTales Example -->
<div class="card shadow mb-4">
    <div class="card-header py-3">
        <h6 class="m-0 font-weight-bold text-primary">Tabel Data Lembar Jawab Siswa</h6>
    </div>
    <div class="card-body">
        <form action="{{ route('laporan-lembar-jawab-by-mapel',['kelas' => $currentKelas,'mapel' => $currentMapel]) }}" method="GET" class="mb-3">
            <div class="input-group">
                <input type="text" name="search" class="form-control" placeholder="Cari Kelas" value="{{ request('search') }}">
                <div class="input-group-append">
                    <button class="btn btn-primary" type="submit">Cari</button>
                </div>
            </div>
        </form>
        @if ($lembarJawabs)
            <a href="{{ route('show-all-pdf-siswa', ['kelas' => $currentKelas, 'mapel' => $currentMapel ]) }}" class="btn btn-info my-3">
                Cetak PDF
            </a>
        @endif
        <div class="table-responsive">
            @if($lembarJawabs->count() > 0)
                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            {{-- <th class="text-center">Guru</th>
                            <th class="text-center">Siswa</th>
                            <th class="text-center">Mata Pelajaran</th>
                            <th class="text-center">Kelas</th> --}}
                            <th class="text-center">Tanggal Mulai</th>
                            <th class="text-center">Tanggal Selesai</th>
                            <th class="text-center">Tipe Soal</th>
                            <th class="text-center">Nilai</th>
                            <th class="text-center">Opsi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($lembarJawabs as $lembarJawab)
                        <tr>
                            {{-- <td class="text-center">{{ $lembarJawab->lembar_soal->guru->nama }}</td>
                            <td class="text-center">{{ $lembarJawab->siswa->nama }}</td>
                            <td class="text-center">{{ $lembarJawab->lembar_soal->mapel->nama }}</td>
                            <td class="text-center">{{ $lembarJawab->lembar_soal->kelas->kelas }}</td> --}}
                            <td class="text-center">{{ $lembarJawab->lembar_soal->tanggal_mulai }}</td>
                            <td class="text-center">{{ $lembarJawab->lembar_soal->tanggal_selesai }}</td>
                            <td class="text-center">{{ $lembarJawab->lembar_soal->tipe_soal }}</td>
                            <td class="text-center">{{ $lembarJawab->nilai }}</td>

                            <td class="d-flex align-items-center">
                                <button class="btn btn-info mx-2" data-toggle="modal" data-target="#soalModal{{ $lembarJawab->id }}">
                                    Lihat Jawaban
                                </button>
                                <!-- Link Cetak PDF -->
                                <a href="{{ route('show-pdf-siswa', ['kelas' => $lembarJawab->lembar_soal->kelas_id, 'mapel' => $lembarJawab->lembar_soal->mapel_id, 'lembarJawab' => $lembarJawab->id ]) }}" class="btn btn-info mx-2">
                                    Cetak PDF
                                </a>
                            </td>
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
                <h5 class="modal-title" id="soalModalLabel{{ $lembarJawab->id }}">Lembar Jawab ID: {{ $lembarJawab->id }}</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
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
                            <label>Poin</label>
                            <input type="text" class="form-control mb-2" value="{{ $jawabanSiswa->poin }}" readonly>
                        @else
                            <p>Belum ada jawaban.</p>
                        @endif
                    </div>
                    <hr>
                    @endforeach
                    <label>Feedback</label>
                    <textarea class="dynamic-textarea text-muted" rows="4" readonly>{{ $lembarJawab->feedback }}</textarea>
                </div>
            </div>
            <div class="modal-footer">
                <div class="d-flex">
                    <button type="button" class="btn btn-secondary mx-2" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
</div>
@endforeach
@endsection
