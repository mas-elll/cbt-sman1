@extends('layout.master')
@section('title', 'Selamat datang Guru')
@section('content')
@include('sweetalert::alert')

<!-- Page Heading -->
<h1 class="h3 mb-2 text-gray-800"></h1>

<!-- DataTales Example -->
<div class="card shadow mb-4">
    <div class="card-header py-3">
        <div class="d-flex justify-content-between">
            <h6 class="m-0 font-weight-bold text-primary">Tabel Data Lembar Soal</h6>
            <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#staticBackdrop">Tambah</button>
        </div>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                <thead>
                    <tr>
                        <th>Guru</th>
                        <th>Mata Pelajaran</th>
                        <th>Kelas</th>
                        <th>Tanggal Mulai</th>
                        <th>Tanggal Selesai</th>
                        <th>Soal</th>
                        <th>Tipe Soal</th>
                        <th>Opsi</th>
                    </tr>
                </thead>
                <tbody id="lembarsoal-table">
                    @foreach ($lembarSoals as $lembarSoal)
                    <tr id="{{ $lembarSoal->id}}">
                        <td>{{ $lembarSoal->guru->nama }}</td>
                        <td>{{ $lembarSoal->mapel->nama }}</td>
                        <td>{{ $lembarSoal->kelas->kelas }}</td>
                        <td>{{ $lembarSoal->tanggal_mulai }}</td>
                        <td>{{ $lembarSoal->tanggal_selesai }}</td>
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
                                        <button class="btn btn-primary w-100" id="editButton-{{$loop->index}}" data-toggle="modal" data-target="#editModal{{ $lembarSoal->id }}">
                                            <i class="fas fa-edit"></i> Edit
                                        </button>
                                    </li>
                                    <li class="p-2">
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
        <div class="d-flex justify-content-center mt-4">
            {{ $lembarSoals->links('pagination::bootstrap-4') }} <!-- Pagination links -->
        </div>
    </div>
</div>

<!-- Modals Lihat -->
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

<!-- Modal EDIT -->
<div class="modal fade" id="editModal{{ $lembarSoal->id }}" tabindex="-1" aria-labelledby="editModalLabel{{ $lembarSoal->id }}" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editModalLabel{{ $lembarSoal->id }}">Edit Lembar Soal</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="{{ route('update-lembar-soal', $lembarSoal) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-body">
                    <div class="container">

                        <div class="form-group">
                            <label for="kelas">Kelas</label>
                            <select name="kelas_id" class="custom-select custom-select-md mb-3" id="kelasEdit-{{$lembarSoal->id}}" required>
                                @foreach($kelasMapel as $km)
                                    <option value="{{ $km->kelas_id }}" @if($km->kelas_id == $lembarSoal->kelas_id) selected @endif>{{ $km->kelas }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <span id="mapelValue-{{$lembarSoal->id}}" style="display: none">{{$lembarSoal->mapel_id}}</span>
                            <label for="mapel">Mata Pelajaran</label>
                            <select name="mapel_id" id="mapelEdit-{{$lembarSoal->id}}" class="custom-select custom-select-md mb-3" required>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="tanggal_mualai">Tanggal Mulai</label>
                            <input type="datetime-local" name="tanggal_mulai" class="form-control" id="tanggal_mulai" value="{{ $lembarSoal->tanggal_mulai }}" required>
                        </div>
                        <div class="form-group">
                            <label for="tanggal_selesai">Tanggal Selesai</label>
                            <input type="datetime-local" name="tanggal_selesai" class="form-control" id="tanggal_selesai" value="{{ $lembarSoal->tanggal_selesai }}" required>
                        </div>
                        <div class="form-group">
                            <label for="tipe_soal">Tipe Soal</label>
                            <select name="tipe_soal" class="custom-select custom-select-md mb-3" required>
                                <option value="Campuran" @if($lembarSoal->tipe_soal == 'Campuran') selected @endif>Campuran</option>
                                <option value="Pilihan Ganda" @if($lembarSoal->tipe_soal == 'Pilihan Ganda') selected @endif>Pilihan Ganda</option>
                                <option value="Essay" @if($lembarSoal->tipe_soal == 'Essay') selected @endif>Essay</option>
                            </select>
                        </div>
                        <div id="dynamic-inputs-edit{{ $lembarSoal->id }}">
                            @foreach($lembarSoal->soal as $index => $soal)
                            <div class="form-group dynamic-input">
                                <label>Soal</label>
                                <textarea name="soal[{{ $index }}][soal]" class="form-control" rows="3" required>{{ $soal->soal }}</textarea>
                                <label>Kunci Jawaban</label>
                                <input type="text" name="soal[{{ $index }}][kunci_jawaban]" class="form-control" value="{{ $soal->kunci_jawaban }}" required>
                                <label>Poin</label>
                                <input type="number" name="soal[{{ $index }}][poin]" class="form-control" value="{{ $soal->poin }}" required>
                                <div class="form-group">
                                    <label for="tipe_soal">Tipe Soal</label>
                                    <select name="soal[{{ $index }}][tipe_soal]" class="custom-select custom-select-md mb-3" required>
                                        <option value="Pilihan Ganda" @if($soal->tipe_soal == 'Pilihan Ganda') selected @endif>Pilihan Ganda</option>
                                        <option value="Essay" @if($soal->tipe_soal == 'Essay') selected @endif>Essay</option>
                                    </select>
                                </div>
                                <button type="button" class="btn btn-danger remove-input mt-2">Hapus</button>
                            </div>
                            @endforeach
                        </div>
                        <button type="button" class="btn btn-primary mt-2" onclick="addDynamicInput({{ $lembarSoal->id }})">Tambah Soal</button>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal HAPUS -->
<div class="modal fade" id="hapusModal{{ $lembarSoal->id }}" tabindex="-1" aria-labelledby="hapusModalLabel{{ $lembarSoal->id }}" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="hapusModalLabel{{ $lembarSoal->id }}">Hapus Lembar Soal</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="{{ route('delete-lembar-soal',$lembarSoal) }}" method="POST">
                @csrf
                @method('DELETE')
                <div class="modal-body">
                    <p>Apakah Anda yakin ingin menghapus lembar soal ini?</p>
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

<!-- Tambah Modal -->
<div class="modal fade" id="staticBackdrop" data-backdrop="static" data-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="staticBackdropLabel">Tambah Lembar Soal</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="{{ route('add-lembar-soal') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="container">


                        <div class="form-group">
                            <label for="kelas">Kelas</label>
                            <select name="kelas_id" class="custom-select custom-select-md mb-3" id="kelasAdd" required>
                                <option disabled selected>Pilih kelas</option>
                                @foreach($kelasMapel as $km)
                                    <option value="{{ $km->kelas_id }}">{{ $km->kelas }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="mapel">Mata Pelajaran</label>
                            <select name="mapel_id" id="mapelAdd" class="custom-select custom-select-md mb-3" required>
                                <option disabled selected>Pilih kelas terlebih dahulu</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="tanggal_muali">Tanggal Mulai</label>
                            <input type="datetime-local" name="tanggal_mulai" class="form-control" id="tanggal_mulai" required>
                        </div>
                        <div class="form-group">
                            <label for="tanggal_selesai">Tanggal Selesai</label>
                            <input type="datetime-local" name="tanggal_selesai" class="form-control" id="tanggal_selesai" required>
                        </div>
                        <div class="form-group">
                            <label for="tipe_soal">Tipe Soal</label>
                            <select name="tipe_soal" class="custom-select custom-select-md mb-3" required>
                                <option value="Pilihan Ganda">Campuran</option>
                                <option value="Pilihan Ganda">Pilihan Ganda</option>
                                <option value="Essay">Essay</option>
                            </select>
                        </div>
                        <div id="dynamic-inputs">
                            <div class="form-group dynamic-input">
                                <label>Soal</label>
                                <textarea id="editor" name="soal[0][soal]" class="form-control" rows="3" tabindex="1"></textarea>
                                <label>Kunci Jawaban</label>
                                <input type="text" name="soal[0][kunci_jawaban]" class="form-control" required>
                                <label>Poin</label>
                                <input type="number" name="soal[0][poin]" class="form-control" required>
                                <label for="tipe_soal">Tipe Soal</label>
                                <select name="soal[0][tipe_soal]" class="custom-select custom-select-md mb-3" onchange="togglePilihanGanda(this, 0)" required>
                                    <option value="" selected>Pilih Tipe Soal</option>
                                    <option value="Pilihan Ganda">Pilihan Ganda</option>
                                    <option value="Essay">Essay</option>
                                </select>
                                <div id="pilihanGandaContainer0" style="display:none;">
                                    <label>Pilihan Jawaban</label>
                                    <div id="pilihanJawaban0">
                                        <div class="form-group">
                                            <input type="text" name="soal[0][pilihan_a]" class="form-control" placeholder="Pilihan A">
                                        </div>
                                        <div class="form-group">
                                            <input type="text" name="soal[0][pilihan_b]" class="form-control" placeholder="Pilihan B">
                                        </div>
                                        <div class="form-group">
                                            <input type="text" name="soal[0][pilihan_c]" class="form-control" placeholder="Pilihan C">
                                        </div>
                                        <div class="form-group">
                                            <input type="text" name="soal[0][pilihan_d]" class="form-control" placeholder="Pilihan D">
                                        </div>
                                        <div class="form-group">
                                            <input type="text" name="soal[0][pilihan_e]" class="form-control" placeholder="Pilihan E">
                                        </div>
                                    </div>
                                </div>
                                <button type="button" class="btn btn-danger remove-input mt-2">Hapus</button>
                            </div>
                        </div>
                        <button type="button" class="btn btn-primary mt-2" onclick="addDynamicInput()">Tambah Soal</button>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script src="{{ asset('sb-template') }}/vendor/jquery/jquery.min.js"></script>
<script>
    ClassicEditor
        .create(document.querySelector('#editor'),{

            ckfinder:{
                uploadUrl: "{{ route('LembarSoal-upload.upload', ['_token' => csrf_token()]) }}"
            },
            image: {
                toolbar: [
                    'imageTextAlternative', 'imageStyle:full', 'imageStyle:side', '|',
                    'resizeImage'
                ],
                resizeOptions: [
                    {
                        name: 'resizeImage:original',
                        value: null,
                        label: 'Original'
                    },
                    {
                        name: 'resizeImage:200',
                        value: '200',
                        label: '200px'
                    },
                    {
                        name: 'resizeImage:400',
                        value: '400',
                        label: '400px'
                    }
                ],
                resizeUnit: 'px'
            }
        })
        .catch(error => {
            console.error(error);
        });
</script>
<script>

    $(document).ready(function(){
        $('#kelasAdd').on('change', function(){
            let kelas = $(this).val();

            if (kelas) {
                $.ajax({
                    url: '/guru/lembar-soal/mapel/' + kelas,
                    type: 'GET',
                    data: {
                        '_token': '{{ csrf_token() }}'
                    },
                    dataType: 'json',
                    success: function(data) {
                        // console.log(data);
                        if (data) {

                            $('#mapelAdd').empty();
                            $('#mapelAdd').append('<option value="">-Pilih-</option>');

                            $.each(data, function(key, mapel) {

                                $('#mapelAdd').append(
                                    '<option value="' + mapel.mapel_id + '">' +
                                    mapel.mapel.nama + '</option>'
                                );

                            });
                        } else {
                            $('#mapelAdd').empty();
                        }
                    }
                });
            } else {
                $('#mapelAdd').empty();
            }
        });



        // untuk ambil berapa jumlah lembar soal
        const lembarSoalTableLength = $('#lembarsoal-table').children().length

        for (j = 0; j < lembarSoalTableLength; j++) {

            const lembarSoalId = $('#lembarsoal-table').children()[j].id

            const kelasValue = $('#kelasEdit-' + lembarSoalId).val()

            let mapelValue = $('#mapelValue-'+lembarSoalId).text()

            // untuk memberi initial value di kelas saat edit
            if (kelasValue) {
                    $.ajax({
                        url: '/guru/lembar-soal/mapel/' + kelasValue,
                        type: 'GET',
                        data: {
                            '_token': '{{ csrf_token() }}'
                        },
                        dataType: 'json',
                        success: function(data) {

                            if (data) {

                                $('#mapelEdit-' + lembarSoalId).empty();
                                $('#mapelEdit-'+ lembarSoalId).append('<option value="">-Pilih-</option>');

                                $.each(data, function(key, mapel) {

                                    if (mapel.mapel_id == mapelValue) {

                                        $('#mapelEdit-' + lembarSoalId).append(
                                            '<option value="' + mapel.mapel_id + '" selected>' + mapel.mapel.nama + '</option>'
                                        );
                                    } else {

                                        $('#mapelEdit - ' + lembarSoalId).append(
                                            '<option value="' + mapel.mapel_id + '" >' + mapel.mapel.nama + '</option>'
                                        );
                                    }

                                });
                            } else {
                                $('#mapelEdit-'+ lembarSoalId).empty();
                            }
                        }
                    });

            }

            // untuk merubah value saat nilai kelas edit berubah
            $('#kelasEdit-' + lembarSoalId).on('change', function(){

                let kelas = $(this).val();


                if (kelas) {
                    console.log('oke')
                    $.ajax({
                        url: '/guru/lembar-soal/mapel/' + kelas,
                        type: 'GET',
                        data: {
                            '_token': '{{ csrf_token() }}'
                        },
                        dataType: 'json',
                        success: function(data) {
                            // console.log(data);
                            if (data) {

                                $('#mapelEdit-' + lembarSoalId).empty();
                                $('#mapelEdit-'+ lembarSoalId).append('<option value="">-Pilih-</option>');

                                $.each(data, function(key, mapel) {

                                    $('#mapelEdit-'+ lembarSoalId).append(
                                        '<option value="' + mapel.mapel_id + '">' +
                                        mapel.mapel.nama + '</option>'
                                    );

                                });
                            } else {
                                $('#mapelEdit-'+ lembarSoalId).empty();
                            }
                        }
                    });
                } else {
                    $('#mapelEdit-'+ lembarSoalId).empty();
                }
            });
        }
    });


    let inputIndex = 1;

function addDynamicInput() {
    const dynamicInputs = document.getElementById('dynamic-inputs');
    const newInput = document.createElement('div');
    newInput.classList.add('form-group', 'dynamic-input');
    newInput.innerHTML = `
        <label>Soal</label>
        <textarea id="editor-${inputIndex}" name="soal[${inputIndex}][soal]" class="form-control" rows="3"></textarea>
        <label>Kunci Jawaban</label>
        <input type="text" name="soal[${inputIndex}][kunci_jawaban]" class="form-control" required>
        <label>Poin</label>
        <input type="number" name="soal[${inputIndex}][poin]" class="form-control" required>
        <label for="tipe_soal">Tipe Soal</label>
        <select name="soal[${inputIndex}][tipe_soal]" class="custom-select custom-select-md mb-3" onchange="togglePilihanGanda(this, ${inputIndex})">
            <option value="" selected>Pilih Tipe Soal</option>
            <option value="Pilihan Ganda">Pilihan Ganda</option>
            <option value="Essay">Essay</option>
        </select>
        <div id="pilihanGandaContainer${inputIndex}" style="display:none;">
            <label>Pilihan Jawaban</label>
            <div id="pilihanJawaban${inputIndex}">
                <div class="form-group">
                    <input type="text" name="soal[${inputIndex}][pilihan_a]" class="form-control" placeholder="Pilihan A">
                </div>
                <div class="form-group">
                    <input type="text" name="soal[${inputIndex}][pilihan_b]" class="form-control" placeholder="Pilihan B">
                </div>
                <div class="form-group">
                    <input type="text" name="soal[${inputIndex}][pilihan_c]" class="form-control" placeholder="Pilihan C">
                </div>
                <div class="form-group">
                    <input type="text" name="soal[${inputIndex}][pilihan_d]" class="form-control" placeholder="Pilihan D">
                </div>
                <div class="form-group">
                    <input type="text" name="soal[${inputIndex}][pilihan_e]" class="form-control" placeholder="Pilihan E">
                </div>
            </div>
        </div>
        <button type="button" class="btn btn-danger remove-input mt-2">Hapus</button>
    `;
    dynamicInputs.appendChild(newInput);

    // Inisialisasi CKEditor pada textarea yang baru
    ClassicEditor
        .create(document.querySelector(`#editor-${inputIndex}`), {
            ckfinder: {
                uploadUrl: "{{ route('LembarSoal-upload.upload', ['_token' => csrf_token()]) }}"
            },
            image: {
                toolbar: [
                    'imageTextAlternative', 'imageStyle:full', 'imageStyle:side', '|',
                    'resizeImage'
                ],
                resizeOptions: [
                    {
                        name: 'resizeImage:original',
                        value: null,
                        label: 'Original'
                    },
                    {
                        name: 'resizeImage:200',
                        value: '200',
                        label: '200px'
                    },
                    {
                        name: 'resizeImage:400',
                        value: '400',
                        label: '400px'
                    }
                ],
                resizeUnit: 'px'
            }
        })
        .catch(error => {
            console.error(error);
        });

    inputIndex++;
    addRemoveListener();
}

function togglePilihanGanda(selectElement, index) {
    const container = document.getElementById(`pilihanGandaContainer${index}`);
    if (selectElement.value === 'Pilihan Ganda') {
        container.style.display = 'block';
    } else {
        container.style.display = 'none';
    }
}

function addRemoveListener() {
    document.querySelectorAll('.remove-input').forEach(button => {
        button.removeEventListener('click', handleRemoveInput);
        button.addEventListener('click', handleRemoveInput);
    });
}

function handleRemoveInput(event) {
    event.target.closest('.dynamic-input').remove();
}

document.addEventListener('DOMContentLoaded', addRemoveListener);
</script>

@endsection
