@extends('layout.master')

@section('title', 'Selamat datang Siswa')

@section('content')
    @include('sweetalert::alert')
    <!-- Page Heading -->
    <h1 class="h3 mb-2 text-gray-800">Tabel Lembar Soal</h1>
    <p class="mb-4">Tabel Pengelolaan Data Lembar Soal.</p>

    <!-- DataTales Example -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Tabel Data Lembar Soal</h6>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                @if($lembarSoals->count() > 0)
                    <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                        <thead>
                            <tr>
                                <th>Guru</th>
                                <th>Mata Pelajaran</th>
                                <th>Kelas</th>
                                <th>Tanggal Mulai</th>
                                <th>Tanggal Selesai</th>
                                <th>Tipe Soal</th>
                                <th>Status</th>
                                <th>Nilai</th>
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
                                <td>{{ $lembarSoal->lembar_jawab->isNotEmpty() ? 'Selesai' : 'Tidak ada jawaban' }}</td>
                                <td>{{ $lembarSoal->lembar_jawab->first() ? $lembarSoal->lembar_jawab->first()->nilai : '0' }}</td>
                                <td>
                                    <button type="button" class="btn btn-info" data-toggle="modal" data-target="#soalModal{{ $lembarSoal->id }}">
                                        Lihat Soal
                                    </button>
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

    <!-- Modals -->
    @foreach ($lembarSoals as $lembarSoal)
    <div class="modal fade" id="soalModal{{ $lembarSoal->id }}" tabindex="-1" aria-labelledby="soalModalLabel{{ $lembarSoal->id }}" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="soalModalLabel{{ $lembarSoal->id }}">Soal untuk Lembar Soal ID: {{ $lembarSoal->id }}</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="container">
                        @foreach ($lembarSoal->soal as $soal)
                        <div class="form-group">
                            <div class="d-flex justify-content-between">
                                <label>Soal {{ $loop->iteration }}</label>
                                <p>poin {{ $soal->poin }}</p>
                            </div>
                            <p>{!! nl2br(e($soal->soal)) !!}</p>
                            {{-- <label>Kunci Jawaban</label>
                            <input type="text" class="form-control" value="{{ $soal->kunci_jawaban }}" readonly> --}}
                            <label>Jawaban Siswa</label>
                            @php
                                $jawabanSiswa = $lembarSoal->lembar_jawab->first() ? $lembarSoal->lembar_jawab->first()->jawaban->firstWhere('soal_id', $soal->id) : null;
                            @endphp
                            @if($jawabanSiswa)
                                <input type="text" class="form-control mb-2" value="{{ $jawabanSiswa->jawaban }}" readonly>
                                <label>Poin Jawaban</label>
                                <input type="text" class="form-control mb-2" value="{{ $jawabanSiswa->poin }}" readonly>
                            @else
                                <p>Belum ada jawaban.</p>
                            @endif
                        </div>
                        <hr>
                        @endforeach
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
    @endforeach

@endsection

@push('scripts')
<script>
    $(document).ready(function() {
        $('#dataTable').DataTable({
            "paging": false, // Disable the default DataTables paging
            "info": false // Disable the info text
        });
    });
</script>
@endpush
