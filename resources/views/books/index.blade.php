<!DOCTYPE html>
<html>
<head>
    <title>Data Buku</title>
</head>
<body>
    <h2>Data Buku</h2>

    <a href="/books/create">Tambah Buku</a>
    <br><br>

    <table border="1" cellpadding="8">
        <tr>
            <th>Kode Buku</th>
            <th>Judul</th>
            <th>Pengarang</th>
            <th>Penerbit</th>
            <th>Tahun</th>
            <th>Stok</th>
        </tr>

        @foreach($books as $buku)
        <tr>
            <td>{{ $buku->kode_buku }}</td>
            <td>{{ $buku->judul }}</td>
            <td>{{ $buku->pengarang }}</td>
            <td>{{ $buku->penerbit }}</td>
            <td>{{ $buku->tahun }}</td>
            <td>{{ $buku->stok }}</td>
        </tr>
        @endforeach
    </table>
</body>
</html>
