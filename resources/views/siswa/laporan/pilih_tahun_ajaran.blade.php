@extends('layout.master')
@section('title','Pilih Tahun Ajaran')
@section('content')

<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card shadow">
                <div class="card-header">
                    <h4 class="text-center">Pilih Tahun Ajaran</h4>
                </div>
                <div class="card-body">
                    <form action="{{ route('set-tahun-ajaran3') }}" method="POST">
                        @csrf
                        <div class="form-group">
                            <label for="tahun_ajaran">Tahun Ajaran</label>
                            <select name="tahun_ajaran" id="tahun_ajaran" class="form-control" required>
                                <option value="">Pilih Tahun Ajaran</option>
                                @foreach($tahunAjarans as $tahunAjaran)
                                    <option value="{{ $tahunAjaran->id }}">{{ $tahunAjaran->tahun_ajaran }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group text-center mt-4">
                            <button type="submit" class="btn btn-primary">Lanjutkan</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection
