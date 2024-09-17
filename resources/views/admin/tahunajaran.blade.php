@extends('layout.master')
@section('title', 'Manajemen Tahun Ajaran')
@section('content')
@include('sweetalert::alert')

<!-- Page Heading -->
<h1 class="h3 mb-2 text-gray-800"></h1>
<nav aria-label="breadcrumb">
    <ol class="breadcrumb">
      <li class="breadcrumb-item"><a href="/admin/">Home</a></li>
      <li class="breadcrumb-item active" aria-current="page">Data Tahun Ajaran</li>
    </ol>
</nav>

<!-- DataTales Example -->
<div class="card shadow mb-4">
    <div class="card-header py-3">
        <div class="d-flex justify-content-between">
            <h6 class="m-0 font-weight-bold text-primary">Tabel Data Tahun Ajaran</h6>
            <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#createModal">Tambah</button>
        </div>
    </div>
    <div class="card-body">
        <form action="{{ route('tahun_ajaran.index') }}" method="GET" class="mb-3">
            <div class="input-group">
                <input type="text" name="search" class="form-control" placeholder="Cari Tahun Ajaran" value="{{ request('search') }}">
                <div class="input-group-append">
                    <button class="btn btn-primary" type="submit">Cari</button>
                </div>
            </div>
        </form>
        <div class="table-responsive">
            @if($tahun_ajaran->count() > 0)
                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>Tahun Ajaran</th>
                            <th>Edit</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($tahun_ajaran as $tahun)
                        <tr>
                            <td>{{ $tahun->tahun_ajaran }}</td>
                            <td>
                                <div class="dropdown">
                                    <a class="text-decoration-none text-dark dropdown-toggle" href="#" role="button" id="dropdownMenuLink{{ $tahun->id }}" data-toggle="dropdown" aria-expanded="false">
                                        Aksi
                                    </a>
                                    <ul class="dropdown-menu" aria-labelledby="dropdownMenuLink{{ $tahun->id }}">
                                        <li class="p-2">
                                            <!-- Edit Button -->
                                            <button class="btn btn-primary w-100" data-toggle="modal" data-target="#editModal{{ $tahun->id }}">
                                                <i class="fas fa-edit"></i> Edit
                                            </button>
                                        </li>
                                        <li class="p-2">
                                            <!-- Delete Button -->
                                            <button class="btn btn-danger w-100" data-toggle="modal" data-target="#deleteModal{{ $tahun->id }}">
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
                    {{ $tahun_ajaran->links('pagination::bootstrap-4') }} <!-- Pagination links -->
                </div>
            @else
                <div class="alert alert-info" role="alert">
                    Tidak ada data tahun ajaran, silahkan tambahkan data.
                </div>
            @endif
        </div>
    </div>
</div>

<!-- Modals -->
@foreach ($tahun_ajaran as $tahun)
<!-- Edit Modal -->
<div class="modal fade" id="editModal{{ $tahun->id }}" tabindex="-1" aria-labelledby="editModalLabel{{ $tahun->id }}" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editModalLabel{{ $tahun->id }}">Edit Tahun Ajaran</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="{{ route('tahun_ajaran.update', $tahun) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-body">
                    <div class="container">
                        <div class="form-group">
                            <label for="tahun_ajaran{{ $tahun->id }}">Tahun Ajaran</label>
                            <input type="text" value="{{ $tahun->tahun_ajaran }}" name="tahun_ajaran" class="form-control" id="tahun_ajaran{{ $tahun->id }}" required>
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

<!-- Delete Modal -->
<div class="modal fade" id="deleteModal{{ $tahun->id }}" tabindex="-1" aria-labelledby="deleteModalLabel{{ $tahun->id }}" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="deleteModalLabel{{ $tahun->id }}">Hapus Tahun Ajaran</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="{{ route('tahun_ajaran.destroy', $tahun) }}" method="POST">
                @csrf
                @method('DELETE')
                <div class="modal-body">
                    <p>Apakah Anda yakin ingin menghapus tahun ajaran ini?</p>
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

<!-- Create Modal -->
<div class="modal fade" id="createModal" data-backdrop="static" data-keyboard="false" tabindex="-1" aria-labelledby="createModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="createModalLabel">Tambah Tahun Ajaran</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="{{ route('tahun_ajaran.store') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="container">
                        <div class="form-group">
                            <label for="tahun_ajaran">Tahun Ajaran</label>
                            <input type="text" name="tahun_ajaran" class="form-control" id="tahun_ajaran" required>
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
