@extends('layout.master')

@section('title', 'Manajemen Siswa')

@section('content')
    @include('sweetalert::alert')

    <!-- Page Heading -->
    <h1 class="h3 mb-2 text-gray-800"></h1>
    
    <!-- DataTales Example -->
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
          <li class="breadcrumb-item"><a href="/admin/">Home</a></li>
          <li class="breadcrumb-item"><a href="/admin/siswa/">Kelas Siswa</a></li>
          <li class="breadcrumb-item active" aria-current="page">Data Siswa {{ $kelas->kelas }}</li>
        </ol>
    </nav>
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <div class="d-flex justify-content-between">
                <h6 class="m-0 font-weight-bold text-primary">Tabel Data Siswa</h6>
                <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#staticBackdrop">Tambah</button>
            </div>
        </div>
        <div class="card-body">
            <form action="{{ route('siswa-by-kelas-admin',$kelas) }}" method="GET" class="mb-3">
                <div class="input-group">
                    <input type="text" name="search" class="form-control" placeholder="Cari Siswa" value="{{ request('search') }}">
                    <div class="input-group-append">
                        <button class="btn btn-primary" type="submit">Cari</button>
                    </div>
                </div>
            </form>
            @if ($siswas->isEmpty())
                <div class="alert alert-warning" role="alert">
                    Tidak ada data siswa.
                </div>
            @else
                <div class="table-responsive">
                    <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                        <thead>
                            <tr>
                                <th>Nama</th>
                                <th>NIS</th>
                                <th>Edit</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($siswas as $siswa)
                            <tr>
                                <td>{{ $siswa->nama }}</td>
                                <td>{{ $siswa->nomer_induk }}</td>
                                <td>
                                    <div class="dropdown">
                                        <a class="text-decoration-none text-dark dropdown-toggle" href="#" role="button" id="dropdownMenuLink{{ $siswa->id }}" data-toggle="dropdown" aria-expanded="false">
                                            Aksi
                                        </a>
                                        <ul class="dropdown-menu" aria-labelledby="dropdownMenuLink{{ $siswa->id }}">
                                            <li class="p-2">
                                                <!-- Tombol Edit -->
                                                <button class="btn btn-primary w-100" data-toggle="modal" data-target="#editModal{{ $siswa->id }}">
                                                    <i class="fas fa-edit"></i> Edit
                                                </button>
                                            </li>
                                            <li class="p-2">
                                                <!-- Tombol Hapus -->
                                                <button class="btn btn-danger w-100" data-toggle="modal" data-target="#hapusModal{{ $siswa->id }}">
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
                        {{ $siswas->appends(['search' => request('search')])->links('pagination::bootstrap-4') }} <!-- Pagination links -->
                    </div>
                </div>
            @endif
        </div>
    </div>

    <!-- Modals Edit -->
    @foreach ($siswas as $siswa)
    <div class="modal fade" id="editModal{{ $siswa->id }}" tabindex="-1" aria-labelledby="editModalLabel{{ $siswa->id }}" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editModalLabel{{ $siswa->id }}">Edit Siswa</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form action="{{ route('update-siswa', $siswa) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="modal-body">
                        <div class="container">
                            <div class="form-group">
                                <label for="name{{ $siswa->id }}">Nama</label>
                                <input type="text" value="{{ $siswa->nama }}" name="nama" class="form-control" id="name{{ $siswa->id }}" required>
                            </div>
                            <div class="form-group">
                                <label for="nomer_induk{{ $siswa->id }}">Nomer Induk</label>
                                <input type="text" value="{{ $siswa->nomer_induk }}" name="nomer_induk" class="form-control" id="nomer_induk{{ $siswa->id }}" required>
                            </div>
                            <div class="form-group">
                                <label for="kelas_id{{ $siswa->id }}">Kelas</label>
                                <select name="kelas_id" class="custom-select custom-select-md mb-3" id="kelas_id{{ $siswa->id }}" required>
                                    <option selected disabled>Pilih Kelas</option>
                                    @foreach($kelases as $kls)
                                        <option value="{{ $kls->id }}" @if($kls->id == $siswa->kelas->id) selected @endif>{{ $kls->kelas }}</option>
                                    @endforeach
                                </select>
                            </div>
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
    <!-- Modal for deleting siswa -->
    <div class="modal fade" id="hapusModal{{ $siswa->id }}" tabindex="-1" aria-labelledby="hapusModalLabel{{ $siswa->id }}" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="hapusModalLabel{{ $siswa->id }}">Hapus Siswa</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form action="{{ route('delete-siswa-admin', $siswa) }}" method="POST">
                    @csrf
                    @method('DELETE')
                    <div class="modal-body">
                        <p>Apakah Anda yakin ingin menghapus siswa ini?</p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-danger">Hapus</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    @endforeach

    <!-- Modal for adding new siswa -->
    <div class="modal fade" id="staticBackdrop" data-backdrop="static" data-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="staticBackdropLabel">Tambah User Siswa</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form action="{{ route('add-siswa') }}" method="POST">
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
                            <input type="text" name="role" value="siswa" hidden>
                            <div class="form-group">
                                <label for="kelas_id">Kelas</label>
                                <select name="kelas_id" class="custom-select custom-select-md mb-3" id="kelas_id" required>
                                    @foreach($currentKelas as $kls)
                                        <option selected value="{{ $kls->id }}">{{ $kls->kelas }}</option>
                                    @endforeach
                                </select>
                            </div>
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

@endsection
