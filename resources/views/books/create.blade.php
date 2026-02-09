<!DOCTYPE html>
<html>
<head>
    <title>Tambah Buku</title>
</head>
<body>
    <h2>Tambah Buku</h2>

    <form action="/books" method="POST">
        @csrf

        <label>Kode Buku</label><br>
        <input type="text" name="kode_buku"><br><br>

        <label>Judul</label><br>
        <input type="text" name="judul"><br><br>

        <label>Pengarang</label><br>
        <input type="text" name="pengarang"><br><br>

        <label>Penerbit</label><br>
        <input type="text" name="penerbit"><br><br>

        <label>Tahun</label><br>
        <input type="number" name="tahun"><br><br>

        <label>Stok</label><br>
        <input type="number" name="stok"><br><br>

        <button type="submit">Simpan</button>
    </form>

    <br>
    <a href="/books">Kembali</a>
</body>
</html>
