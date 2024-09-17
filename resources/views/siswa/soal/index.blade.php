@extends('layout.master')

@section('title', 'Selamat datang Siswa')

@section('content')
    @include('sweetalert::alert')

    <!-- Page Heading -->
    <h1 class="h3 mb-2 text-gray-800">Lembar Soal {{ $lembarSoals->mapel->nama }}</h1>
    <p class="mb-4">Kerjakan sesuai instruksi yang ada.</p>

    @if(session('error'))
        <div class="alert alert-danger">
            {{ session('error') }}
        </div>
    @else
        <div class="card shadow-md mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">Lembar Soal {{ $lembarSoals->mapel->nama }}</h6>
            </div>
            <div class="card-body px-5">
                <div class="my-3">
                    <div class="row">
                        <div class="col-sm-2 col-4">
                            <h6 class="font-weight-bold">Nama Guru</h6>
                        </div>
                        <div class="col-sm-1 col-2">:</div>
                        <div class="col-sm-4 col-6">
                            <h6 class="font-weight-bold">{{ $lembarSoals->guru->nama }}</h6>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-2 col-4">
                            <h6 class="font-weight-bold">Mata Pelajaran</h6>
                        </div>
                        <div class="col-sm-1 col-2">:</div>
                        <div class="col-sm-4 col-6">
                            <h6 class="font-weight-bold">{{ $lembarSoals->mapel->nama }}</h6>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-2 col-4">
                            <h6 class="font-weight-bold">Kelas</h6>
                        </div>
                        <div class="col-sm-1 col-2">:</div>
                        <div class="col-sm-4 col-6">
                            <h6 class="font-weight-bold">{{ $lembarSoals->kelas->kelas }}</h6>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-2 col-4">
                            <h6 class="font-weight-bold">Tipe Soal</h6>
                        </div>
                        <div class="col-sm-1 col-2">:</div>
                        <div class="col-sm-4 col-6">
                            <h6 class="font-weight-bold">{{ $lembarSoals->tipe_soal }}</h6>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-2 col-4">
                            <h6 class="font-weight-bold">Nama Siswa</h6>
                        </div>
                        <div class="col-sm-1 col-2">:</div>
                        <div class="col-sm-4 col-6">
                            <h6 class="font-weight-bold">{{ auth()->user()->name }}</h6>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-2 col-4">
                            <h6 class="font-weight-bold">Tanggal Mulai</h6>
                        </div>
                        <div class="col-sm-1 col-2">:</div>
                        <div class="col-sm-4 col-6">
                            <h6 class="font-weight-bold">{{ $formattedTanggalMulai }}</h6>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-2 col-4">
                            <h6 class="font-weight-bold">Tanggal Selesai</h6>
                        </div>
                        <div class="col-sm-1 col-2">:</div>
                        <div class="col-sm-4 col-6">
                            <h6 class="font-weight-bold">{{ $formattedTanggalSelesai }}</h6>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-2 col-4">
                            <h6 class="font-weight-bold">Sisa Waktu</h6>
                        </div>
                        <div class="col-sm-1 col-2">:</div>
                        <div class="col-sm-4 col-6">
                            <h6 id="durasi" class="font-weight-bold"></h6>
                        </div>
                    </div>
                </div>
                <hr>
                <form id="jawabanForm" action="{{ route('simpan-lembar-jawab') }}" method="POST">
                    @csrf
                    <input type="hidden" name="lembar_soal_id" value="{{ $lembarSoals->id }}">
                    @foreach ($soals as $soal)
                        <div class="mb-4">
                            <div class="d-flex justify-content-between">
                                <h5>Soal {{ $loop->iteration + ($soals->currentPage() - 1) * $soals->perPage() }}</h5>
                                <p></p>
                                <p>poin {{ $soal->poin }}</p>
                            </div>
                            <p>{!! $soal->soal !!}</p>

                            @if($soal->tipe_soal == 'Pilihan Ganda')
                <div>
                    <label>Pilih jawaban yang benar:</label>
                    @foreach(['A', 'B', 'C', 'D', 'E'] as $option)
                        @php
                            $pilihan = 'pilihan_' . strtolower($option);
                        @endphp
                        @if(!empty($soal->$pilihan))
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="jawaban[{{ $soal->id }}]" value="{{ $option }}">
                                <label class="form-check-label">{{ $option }}. {!! nl2br(e($soal->$pilihan)) !!}</label>
                            </div>
                        @endif
                    @endforeach
                </div>
                <hr>
                            @elseif($soal->tipe_soal == 'Essay')
                                <div>
                                    <label for="jawaban_{{ $soal->id }}">Tulis jawaban Anda:</label>
                                    <textarea class="form-control" name="jawaban[{{ $soal->id }}]" id="jawaban_{{ $soal->id }}" rows="4"></textarea>
                                </div>
                            @endif
                        </div>
                    @endforeach

                    <!-- Hidden Input untuk Lembar Soal ID -->
                    <input type="hidden" name="lembar_soal_id" value="{{ $lembarSoals->id }}">

                    <!-- Tampilkan tombol "Simpan Jawaban" hanya di halaman terakhir -->
                    @if ($soals->lastPage() === $soals->currentPage())
                        <div class="mt-4 d-flex justify-content-end">
                            <button type="submit" class="btn btn-primary">Simpan Jawaban</button>
                        </div>
                    @endif
                </form>

                <!-- Tambahkan navigasi paginasi -->
                <div class="d-flex justify-content-center mt-4">
                    {{ $soals->links('pagination::bootstrap-4') }}
                </div>
            </div>
        </div>
    @endif

@endsection

@push('scripts')
<script>

let durasiTimeout; // Variabel untuk menyimpan timeout durasi

// Fungsi untuk menghitung dan update durasi real-time
function updateDurasi() {
    const durasiElement = document.getElementById('durasi');
    const tanggalMulai = new Date('{{ $lembarSoals->tanggal_mulai }}');
    const tanggalSelesai = new Date('{{ $lembarSoals->tanggal_selesai }}');
    let durasi = Math.max((tanggalSelesai - new Date()), 0); // Durasi dalam milidetik

    durasiTimeout = setInterval(() => {
        durasi -= 1000; // Kurangi 1 detik (1000 milidetik)
        if (durasi >= 0) {
            const days = Math.floor(durasi / (1000 * 60 * 60 * 24));
            const hours = Math.floor((durasi % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
            const minutes = Math.floor((durasi % (1000 * 60 * 60)) / (1000 * 60));
            const seconds = Math.floor((durasi % (1000 * 60)) / 1000);
            durasiElement.textContent = `${days} hari, ${hours} jam, ${minutes} menit, ${seconds} detik`;
        } else {
            document.getElementById('jawabanForm').submit();
            clearInterval(durasiTimeout); // Hentikan interval
            // Otomatis submit form
        }
    }, 1000); // Update setiap 1 detik
}

// Panggil fungsi updateDurasi saat dokumen sudah siap
document.addEventListener('DOMContentLoaded', () => {
    updateDurasi();
});


    $(document).ready(function() {
        $('#dataTable').DataTable();
    });
</script>
@endpush

