@extends('layout.master')
@section('title', 'Manajemen Mata Pelajaran')
@section('content')
    @include('sweetalert::alert')

    <!-- Page Heading -->
    <h1 class="h3 mb-2 text-gray-800"></h1>
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
          <li class="breadcrumb-item"><a href="/admin/">Home</a></li>
          <li class="breadcrumb-item active" aria-current="page">Mata Pelajaran</li>
        </ol>
    </nav>
    <!-- DataTales Example -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <div class="d-flex justify-content-between">
                <h6 class="m-0 font-weight-bold text-primary">Tabel Data Mata Pelajaran</h6>
                <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#staticBackdrop">Tambah</button>
            </div>
        </div>
        <div class="card-body">
            <form action="{{ route('mapel-admin') }}" method="GET" class="mb-3">
                <div class="input-group">
                    <input type="text" name="search" class="form-control" placeholder="Cari Mata Pelajaran" value="{{ request('search') }}">
                    <div class="input-group-append">
                        <button class="btn btn-primary" type="submit">Cari</button>
                    </div>
                </div>
            </form>
            <div class="table-responsive">
                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>Nama Mata Pelajaran</th>
                            <th>Kode Mapel</th>
                            <th>Deskripsi</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($mapels as $mapel)
                        <tr>
                            <td>{{ $mapel->nama }}</td>
                            <td>{{ $mapel->kode_mapel }}</td>
                            <td>{{ $mapel->deskripsi }}</td>
                            <td>
                                <div class="dropdown">
                                    <a class="text-decoration-none text-dark dropdown-toggle" href="#" role="button" id="dropdownMenuLink{{ $mapel->id }}" data-toggle="dropdown" aria-expanded="false">
                                        Aksi
                                    </a>
                                    <ul class="dropdown-menu" aria-labelledby="dropdownMenuLink{{ $mapel->id }}">
                                        <li class="p-2">
                                            <button class="btn btn-primary w-100" data-toggle="modal" data-target="#editModal{{ $mapel->id }}">
                                                <i class="fas fa-edit"></i> Edit
                                            </button>
                                        </li>
                                        <li class="p-2">
                                            <button class="btn btn-danger w-100" data-toggle="modal" data-target="#hapusModal{{ $mapel->id }}">
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
            <!-- Paginasi Laravel -->
            <div class="d-flex justify-content-center">
                {{ $mapels->appends(['search' => request('search')])->links('pagination::bootstrap-4') }}
            </div>
        </div>
    </div>

    <!-- Modal Edit -->
    @foreach ($mapels as $mapel)
    <div class="modal fade" id="editModal{{ $mapel->id }}" tabindex="-1" aria-labelledby="editModalLabel{{ $mapel->id }}" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editModalLabel{{ $mapel->id }}">Edit Mata Pelajaran</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form action="{{ route('update-mapel', $mapel) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="modal-body">
                        <div class="container">
                            <div class="form-group">
                                <label for="nama{{ $mapel->id }}">Nama mata pelajaran</label>
                                <input type="text" value="{{ $mapel->nama }}" name="nama" class="form-control" id="nama{{ $mapel->id }}" required>
                            </div>
                            <div class="form-group">
                                <label for="kode_mapel{{ $mapel->id }}">Kode Mapel</label>
                                <input type="text" value="{{ $mapel->kode_mapel }}" name="kode_mapel" class="form-control" id="kode_mapel{{ $mapel->id }}" required>
                            </div>
                            <div class="form-group">
                                <label for="deskripsi{{ $mapel->id }}">Deskripsi</label>
                                <textarea name="deskripsi" class="form-control" id="deskripsi{{ $mapel->id }}" rows="3">{{ $mapel->deskripsi }}</textarea>
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
    @endforeach

    <!-- Modal Hapus -->
    @foreach ($mapels as $mapel)
    <div class="modal fade" id="hapusModal{{ $mapel->id }}" tabindex="-1" aria-labelledby="hapusModalLabel{{ $mapel->id }}" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="hapusModalLabel{{ $mapel->id }}">Hapus Mata Pelajaran</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form action="{{ route('delete-mapel', $mapel) }}" method="POST">
                    @csrf
                    @method('DELETE')
                    <div class="modal-body">
                        <p>Apakah Anda yakin ingin menghapus mata pelajaran ini?</p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-danger">Hapus</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    @endforeach

    <!-- Modal Tambah -->
    <div class="modal fade" id="staticBackdrop" data-backdrop="static" data-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="staticBackdropLabel">Tambah Mata Pelajaran</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form action="{{ route('add-mapel') }}" method="POST">
                    @csrf
                    <div class="modal-body">
                        <div class="container">
                            <div class="form-group">
                                <label for="nama">Nama mata pelajaran</label>
                                <input type="text" name="nama" class="form-control" id="nama" required>
                            </div>
                            <div class="form-group">
                                <label for="kode_mapel">Kode Mapel</label>
                                <input type="text" name="kode_mapel" class="form-control" id="kode_mapel" required>
                            </div>
                            <div class="form-group">
                                <label for="deskripsi">Deskripsi</label>
                                <textarea name="deskripsi" class="form-control" id="deskripsi" rows="3"></textarea>
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
