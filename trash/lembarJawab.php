@extends('layout.master')
@section('title', 'Selamat datang Admin')
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
                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>Guru</th>
                            <th>Mata Pelajaran</th>
                            <th>Kelas</th>
                            <th>Tanggal</th>
                            <th>Waktu</th>
                            <th>Soal</th>
                            <th>Tipe Soal</th>
                            <th>Opsi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($lembarSoals as $lembarSoal)
                        <tr>
                            <td>{{ $lembarSoal->guru->nama }}</td>
                            <td>{{ $lembarSoal->mapel->nama }}</td>
                            <td>{{ $lembarSoal->kelas->kelas }}</td>
                            <td>{{ $lembarSoal->tanggal }}</td>
                            <td>{{ $lembarSoal->waktu }}</td>
                            <td>
                                <button type="button" class="btn btn-info" data-toggle="modal" data-target="#soalModal{{ $lembarSoal->id }}">
                                    Lihat Soal
                                </button>
                            </td>
                            <td>{{ $lembarSoal->tipe_soal }}</td>
                            <td>
                                <div class="dropdown">
                                    <a class="text-decoration-none text-dark dropdown-toggle" href="#" role="button" id="dropdownMenuLink{{ $lembarSoal->id }}" data-toggle="dropdown" aria-expanded="false">
                                        Aksi
                                    </a>
                                    <ul class="dropdown-menu" aria-labelledby="dropdownMenuLink{{ $lembarSoal->id }}">
                                        <li class="p-2">
                                            <!-- Tombol Edit -->
                                            <button class="btn btn-primary w-100" data-toggle="modal" data-target="#editModal{{ $lembarSoal->id }}">
                                                <i class="fas fa-edit"></i> Edit
                                            </button>
                                        </li>
                                        <li class="p-2">
                                            <!-- Tombol Hapus -->
                                            <button class="btn btn-danger w-100" data-toggle="modal" data-target="#hapusModal{{ $lembarSoal->id }}">
                                                <i class="fas fa-trash"></i> Hapus
                                            </button>
                                        </li>
                                    </ul>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
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
                            <label>Soal</label>
                            <p>{!! nl2br(e($soal->soal)) !!}</p>
                            <label>Kunci Jawaban</label>
                            <input type="text" class="form-control" value="{{ $soal->kunci_jawaban }}" readonly>
                            <label>Poin</label>
                            <input type="number" class="form-control" value="{{ $soal->poin }}" readonly>
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
        $('#dataTable').DataTable();
    });
</script>
@endpush
