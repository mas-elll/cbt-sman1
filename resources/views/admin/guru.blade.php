@extends('layout.master')
@section('title', 'Manajemen Guru')
@section('content')
    @include('sweetalert::alert')
    <!-- Page Heading -->
    <h1 class="h3 mb-2 text-gray-800"></h1>
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
          <li class="breadcrumb-item"><a href="/admin/">Home</a></li>
          <li class="breadcrumb-item active" aria-current="page">Data Guru</li>
        </ol>
    </nav>
    <!-- DataTales Example -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <div class="d-flex justify-content-between">
                <h6 class="m-0 font-weight-bold text-primary">Tabel Data Guru</h6>
                <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#staticBackdrop">Tambah</button>
            </div>
        </div>
        <div class="card-body">
            <form action="{{ route('guru-admin') }}" method="GET" class="mb-3">
                <div class="input-group">
                    <input type="text" name="search" class="form-control" placeholder="Cari Guru" value="{{ request('search') }}">
                    <div class="input-group-append">
                        <button class="btn btn-primary" type="submit">Cari</button>
                    </div>
                </div>
            </form>
            <div class="table-responsive">
                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>Nama</th>
                            <th>Nomer Induk Guru</th>
                            <th>Email</th>
                            {{-- <th>Password</th> --}}
                            <th>Kelas & Mata Pelajaran</th>
                            <th>Edit</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($gurus as $guru)
                        <tr>
                            <td>{{ $guru->nama }}</td>
                            <td>{{ $guru->nomer_induk }}</td>
                            <td>{{ $guru->user->email }}</td>
                            {{-- <td>{{ $guru->user->password }}</td> --}}
                            <td>
                                @foreach ($guru->kelasMapel as $kelasMapel)
                                    {{ $kelasMapel->kelas->kelas }} - {{ $kelasMapel->mapel->nama }}
                                    @if(!$loop->last)
                                        <br>
                                    @endif
                                @endforeach
                            </td>
                            <td>
                                <div class="dropdown">
                                    <a class="text-decoration-none text-dark dropdown-toggle" href="#" role="button" id="dropdownMenuLink{{ $guru->id }}" data-toggle="dropdown" aria-expanded="false">
                                        Aksi
                                    </a>
                                    <ul class="dropdown-menu" aria-labelledby="dropdownMenuLink{{ $guru->id }}">
                                        <li class="p-2">
                                            <!-- Tombol Edit -->
                                            <button class="btn btn-primary w-100" data-toggle="modal" data-target="#editModal{{ $guru->id }}">
                                                <i class="fas fa-edit"></i> Edit
                                            </button>
                                        </li>
                                        <li class="p-2">
                                            <!-- Tombol Hapus -->
                                            <button class="btn btn-danger w-100" data-toggle="modal" data-target="#hapusModal{{ $guru->id }}">
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
                <div class="d-flex justify-content-center mt-4">
                    {{ $gurus->appends(['search' => request('search')])->links('pagination::bootstrap-4') }} <!-- Pagination links -->
                </div>
            </div>
        </div>
    </div>

    <!-- Modals -->
    @foreach ($gurus as $guru)
    <div class="modal fade" id="editModal{{ $guru->id }}" tabindex="-1" aria-labelledby="editModalLabel{{ $guru->id }}" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editModalLabel{{ $guru->id }}">Edit Guru</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form action="{{ route('update-guru', $guru) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="modal-body">
                        <div class="container">
                            <div class="form-group">
                                <label for="name{{ $guru->id }}">Nama</label>
                                <input type="text" value="{{ $guru->nama }}" name="nama" class="form-control" id="name{{ $guru->id }}" required>
                            </div>
                            <div class="form-group">
                                <label for="nomer_induk{{ $guru->id }}">Nomer Induk</label>
                                <input type="text" value="{{ $guru->nomer_induk }}" name="nomer_induk" class="form-control" id="nomer_induk{{ $guru->id }}" required>
                            </div>
                            <div class="form-group">
                                <label for="tahun_ajaran{{ $guru->id }}">Tahun Ajaran</label>
                                <select name="tahun_ajaran" class="custom-select custom-select-md mb-3" id="tahun_ajaran{{ $guru->id }}" required>
                                    <option value="" disabled>Pilih Tahun Ajaran</option>
                                    @foreach($tahunAjaran as $tahun)
                                        <option value="{{ $tahun->id }}" {{ $guru->id_tahun_ajaran == $tahun->id ? 'selected' : '' }}>{{ $tahun->tahun_ajaran }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div id="dynamic-inputs-edit-{{ $guru->id }}">
                                @foreach ($guru->kelasMapel as $kelasMapel)
                                <div class="form-group dynamic-input">
                                    <label>Kelas</label>
                                    <select name="kelas_id[]" class="custom-select custom-select-md mb-3" required>
                                        <option selected disabled>Pilih Kelas</option>
                                        @foreach($kelas as $kls)
                                            <option value="{{ $kls->id }}" @if($kls->id == $kelasMapel->kelas->id) selected @endif>{{ $kls->kelas }}</option>
                                        @endforeach
                                    </select>
                                    <label>Mata pelajaran</label>
                                    <select name="mapel_id[]" class="custom-select custom-select-md mb-3" required>
                                        <option selected disabled>Pilih Mata Pelajaran</option>
                                        @foreach($mapels as $mapel)
                                            <option value="{{ $mapel->id }}" @if($mapel->id == $kelasMapel->mapel->id) selected @endif>{{ $mapel->nama }}</option>
                                        @endforeach
                                    </select>
                                    <button type="button" class="btn btn-danger remove-input">Hapus</button>
                                </div>
                                @endforeach
                            </div>
                            <button type="button" class="btn btn-primary add-more" data-id="{{ $guru->id }}">Tambah</button>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Kirim</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal Hapus -->
    <div class="modal fade" id="hapusModal{{ $guru->id }}" tabindex="-1" aria-labelledby="hapusModalLabel{{ $guru->id }}" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="hapusModalLabel{{ $guru->id }}">Hapus Guru</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <p>Apakah Anda yakin ingin menghapus data {{ $guru->nama }}?</p>
                </div>
                <div class="modal-footer">
                    <form action="{{ route('delete-guru-admin', $guru->id) }}" method="POST">
                        @csrf
                        @method('DELETE')
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-danger">Hapus</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    @endforeach

    <!-- Modal CREATE -->
    <div class="modal fade" id="staticBackdrop" data-backdrop="static" data-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="staticBackdropLabel">Tambah User Guru</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form action="{{ route('add-guru') }}" method="POST">
                    @csrf
                    <div class="modal-body">
                        <div class="container">
                            <div class="form-group">
                                <label for="name">Nama</label>
                                <input type="text" name="name" class="form-control" id="name" required>
                            </div>
                            <div class="form-group">
                                <label for="exampleInputEmail1">Email</label>
                                <input type="email" name="email" class="form-control" id="exampleInputEmail1" aria-describedby="emailHelp" required>
                            </div>
                            <div class="form-group">
                                <label for="exampleInputPassword1">Kata sandi</label>
                                <input type="password" name="password" class="form-control" id="exampleInputPassword1" required>
                            </div>
                            <div class="form-group">
                                <label for="password_confirm">Konfirmasi Kata sandi</label>
                                <input type="password" name="password_confirmation" class="form-control" id="password_confirm" required>
                            </div>
                            <div class="form-group">
                                <label for="nomer_induk">Nomer Induk</label>
                                <input type="text" name="nomer_induk" class="form-control" id="nomer_induk" required>
                            </div>
                            <div class="form-group">
                                <label for="tahun_ajaran">Tahun Ajaran</label>
                                <select name="id_tahun_ajaran" class="custom-select custom-select-md mb-3" id="tahun_ajaran" required>
                                    <option selected disabled>Pilih Tahun Ajaran</option>
                                    @foreach($tahunAjaran as $tahun)
                                        <option value="{{ $tahun->id }}">{{ $tahun->tahun_ajaran }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <input type="text" name="role" value="guru" hidden>
                            <div id="dynamic-inputs">
                                <div class="form-group dynamic-input">
                                    <label>Kelas</label>
                                    <select name="kelas_id[]" class="custom-select custom-select-md mb-3" required>
                                        <option selected disabled>Pilih Kelas</option>
                                        @foreach($kelas as $kls)
                                            <option value="{{ $kls->id }}">{{ $kls->kelas }}</option>
                                        @endforeach
                                    </select>
                                    <label>Mata pelajaran</label>
                                    <select name="mapel_id[]" class="custom-select custom-select-md mb-3" required>
                                        <option selected disabled>Pilih Mata Pelajaran</option>
                                        @foreach($mapels as $mapel)
                                            <option value="{{ $mapel->id }}">{{ $mapel->nama }}</option>
                                        @endforeach
                                    </select>
                                    <button type="button" class="btn btn-danger remove-input">Hapus</button>
                                </div>
                            </div>
                            <button type="button" id="tambah" class="btn btn-primary">Tambah</button>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Kirim</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
document.addEventListener('DOMContentLoaded', function() {
    // Untuk modal create
    document.getElementById('tambah').addEventListener('click', function() {
        var dynamicInputs = document.getElementById('dynamic-inputs');
        var newInputGroup = document.createElement('div');
        newInputGroup.className = 'form-group dynamic-input';

        newInputGroup.innerHTML = `
        <hr class="mx-3 my-3">
            <label>Kelas</label>
            <select name="kelas_id[]" class="custom-select custom-select-md mb-3" required>
                <option selected disabled>Pilih Kelas</option>
                @foreach($kelas as $kls)
                    <option value="{{ $kls->id }}">{{ $kls->kelas }}</option>
                @endforeach
            </select>
            <label>Mata pelajaran</label>
            <select name="mapel_id[]" class="custom-select custom-select-md mb-3" required>
                <option selected disabled>Pilih Mata Pelajaran</option>
                @foreach($mapels as $mapel)
                    <option value="{{ $mapel->id }}">{{ $mapel->nama }}</option>
                @endforeach
            </select>
            <button type="button" class="btn btn-danger remove-input">Hapus</button>
        `;

        dynamicInputs.appendChild(newInputGroup);
    });

    // Untuk modal edit
    document.querySelectorAll('.add-more').forEach(button => {
        button.addEventListener('click', function() {
            var id = this.getAttribute('data-id');
            var dynamicInputs = document.getElementById('dynamic-inputs-edit-' + id);
            var newInputGroup = document.createElement('div');
            newInputGroup.className = 'form-group dynamic-input';

            newInputGroup.innerHTML = `
            <hr class="mx-3 my-3">
                <label>Kelas</label>
                <select name="kelas_id[]" class="custom-select custom-select-md mb-3" required>
                    <option selected disabled>Pilih Kelas</option>
                    @foreach($kelas as $kls)
                        <option value="{{ $kls->id }}">{{ $kls->kelas }}</option>
                    @endforeach
                </select>
                <label>Mata pelajaran</label>
                <select name="mapel_id[]" class="custom-select custom-select-md mb-3" required>
                    <option selected disabled>Pilih Mata Pelajaran</option>
                    @foreach($mapels as $mapel)
                        <option value="{{ $mapel->id }}">{{ $mapel->nama }}</option>
                    @endforeach
                </select>
                <button type="button" class="btn btn-danger remove-input">Hapus</button>
            `;

            dynamicInputs.appendChild(newInputGroup);
        });
    });

    // Menghapus input
    document.addEventListener('click', function(e) {
        if (e.target.classList.contains('remove-input')) {
            e.target.closest('.dynamic-input').remove();
        }
    });
});

    </script>
    @endpush

@endsection
