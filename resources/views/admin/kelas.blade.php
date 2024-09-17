@extends('layout.master')
@section('title','Manajemen Kelas')
@section('content')
@include('sweetalert::alert')
<!-- Page Heading -->
<h1 class="h3 mb-2 text-gray-800"></h1>
<nav aria-label="breadcrumb">
    <ol class="breadcrumb">
      <li class="breadcrumb-item"><a href="/admin/">Home</a></li>
      <li class="breadcrumb-item active" aria-current="page">Data Kelas</li>
    </ol>
</nav>
<!-- DataTales Example -->
<div class="card shadow mb-4">
    <div class="card-header py-3">
        <div class="d-flex justify-content-between">
            <h6 class="m-0 font-weight-bold text-primary">Tabel Data Kelas</h6>
            <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#staticBackdrop">Tambah</button>
        </div>
    </div>
    <div class="card-body">
        <form action="{{ route('kelas-admin') }}" method="GET" class="mb-3">
            <div class="input-group">
                <input type="text" name="search" class="form-control" placeholder="Cari Kelas" value="{{ request('search') }}">
                <div class="input-group-append">
                    <button class="btn btn-primary" type="submit">Cari</button>
                </div>
            </div>
        </form>
        <div class="table-responsive">
            @if($kelas->count() > 0)
                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>Kelas</th>
                            <th>Edit</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($kelas as $kls)
                        <tr>
                            <td>{{ $kls->kelas }}</td>
                            <td>
                                <div class="dropdown">
                                    <a class="text-decoration-none text-dark dropdown-toggle" href="#" role="button" id="dropdownMenuLink{{ $kls->id }}" data-toggle="dropdown" aria-expanded="false">
                                        Aksi
                                    </a>
                                    <ul class="dropdown-menu" aria-labelledby="dropdownMenuLink{{ $kls->id }}">
                                        <li class="p-2">
                                            <!-- Tombol Edit -->
                                            <button class="btn btn-primary w-100" data-toggle="modal" data-target="#editModal{{ $kls->id }}">
                                                <i class="fas fa-edit"></i> Edit
                                            </button>
                                        </li>
                                        <li class="p-2">
                                            <!-- Tombol Hapus -->
                                            <button class="btn btn-danger w-100" data-toggle="modal" data-target="#hapusModal{{ $kls->id }}">
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
                    {{ $kelas->links('pagination::bootstrap-4') }} <!-- Pagination links -->
                </div>
            @else
                <div class="alert alert-info" role="alert">
                    Tidak ada data kelas, silahkan buat kelas.
                </div>
            @endif
        </div>
    </div>
</div>

<!-- Modals -->
@foreach ($kelas as $kls)
<div class="modal fade" id="editModal{{ $kls->id }}" tabindex="-1" aria-labelledby="editModalLabel{{ $kls->id }}" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editModalLabel{{ $kls->id }}">Edit Kelas</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="{{ route('update-kelas', $kls) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-body">
                    <div class="container">
                        <div class="form-group">
                            <label for="kelas{{ $kls->id }}">Kelas</label>
                            <input type="text" value="{{ $kls->kelas }}" name="kelas" class="form-control" id="kelas{{ $kls->id }}" required>
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

<div class="modal fade" id="hapusModal{{ $kls->id }}" tabindex="-1" aria-labelledby="hapusModalLabel{{ $kls->id }}" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="hapusModalLabel{{ $kls->id }}">Hapus Kelas</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="{{ route('delete-kelas', $kls) }}" method="POST">
                @csrf
                @method('DELETE')
                <div class="modal-body">
                    <p>Apakah Anda yakin ingin menghapus kelas ini?</p>
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

<!-- Modal CREATE -->
<div class="modal fade" id="staticBackdrop" data-backdrop="static" data-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="staticBackdropLabel">Tambah Kelas</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="{{ route('add-kelas') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="container">
                        <div class="form-group">
                            <label for="kelas">Kelas</label>
                            <input type="text" name="kelas" class="form-control" id="kelas" required>
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
