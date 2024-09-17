<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>PDF Laporan</title>
    <style>
        body {
            font-family: Arial, sans-serif;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        table, th, td {
            border: 1px solid black;
        }
        th, td {
            padding: 10px;
            text-align: left;
        }
        th {
            background-color: #f2f2f2;
        }
        h2 {
            text-align: center;
        }
    </style>
</head>
<body>

    <h2>Laporan Tugas</h2>
    @if ($lembarJawabs->count() > 1)
        <!-- Display lembar soal data only once -->
        <h3>Data Lembar Soal</h3>
        @if (isset($lembarJawabs[0]))
            <h5>Guru: {{ $lembarJawabs[0]->lembar_soal->guru->nama }}</h5>
            <h5>Kelas: {{ $lembarJawabs[0]->lembar_soal->kelas->kelas }}</h5>
            <h5>Mata Pelajaran: {{ $lembarJawabs[0]->lembar_soal->mapel->nama }}</h5>
        @endif  
        <table>
            <tr>
                <th>Siswa</th>
                <th>Nomer Induk</th>
                <th>Kelas</th>
                <th>Nilai</th>
            </tr>
            @foreach ($lembarJawabs as $lembarJawab)
                <tr>
                    <td>{{ $lembarJawab->siswa->nama }}</td>
                    <td>{{ $lembarJawab->siswa->nomer_induk }}</td>
                    <td>{{ $lembarJawab->lembar_soal->kelas->kelas }}</td>
                    <td>{{ $lembarJawab->nilai }}</td>
                </tr>
            @endforeach
        </table>
    @else
        @foreach ($lembarJawabs as $lembarJawab)
            <h3>Data Lembar Soal</h3>
            <table>
                <tr>
                    <th>Guru</th>
                    <th>Siswa</th>
                    <th>Mata Pelajaran</th>
                    <th>Kelas</th>
                    <th>Tanggal Mulai</th>
                    <th>Tanggal Selesai</th>
                </tr>
                <tr>
                    <td>{{ $lembarJawab->lembar_soal->guru->nama }}</td>
                    <td>{{ $lembarJawab->siswa->nama }}</td>
                    <td>{{ $lembarJawab->lembar_soal->mapel->nama }}</td>
                    <td>{{ $lembarJawab->lembar_soal->kelas->kelas }}</td>
                    <td>{{ $lembarJawab->lembar_soal->tanggal_mulai }}</td>
                    <td>{{ $lembarJawab->lembar_soal->tanggal_selesai }}</td>
                </tr>
            </table>

            <h3>Data Lembar Jawab</h3>
            <table>
                <tr>
                    <th>No.</th>
                    <th>Soal</th>
                    <th>Jawaban</th>
                    <th>Poin</th>
                </tr>
                @foreach ($lembarJawab->jawaban as $index => $jawaban)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td>{{ $lembarJawab->lembar_soal->soal->firstWhere('id', $jawaban->soal_id)->soal }}</td>
                        <td>{{ $jawaban->jawaban }}</td>
                        <td>{{ $jawaban->poin }}</td>
                    </tr>
                @endforeach
                <tr>
                    <td colspan="3">Nilai</td>
                    <td>{{ $lembarJawab->nilai }}</td>
                </tr>
            </table>
            <p class="text-muted">feedback : {{ $lembarJawab->feedback  }}</p>

            <hr>
        @endforeach
    @endif
</body>
</html>
