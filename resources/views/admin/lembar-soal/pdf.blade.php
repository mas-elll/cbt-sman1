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
</body>
</html>
