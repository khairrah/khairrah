<!DOCTYPE html>
<html>
<head>
    <title>Tambah Alat</title>
</head>
<body>
    <h2>Tambah Alat</h2>

    <form action="/books" method="POST">
        @csrf

        <label>Id Alat</label><br>
        <input type="text" name="kode_buku"><br><br>

        <label>Nama Alat</label><br>
        <input type="text" name="judul"><br><br>

        <label>Lokasi</label><br>
        <input type="text" name="pengarang"><br><br>

        <label>Kondisi</label><br>
        <input type="text" name="penerbit"><br><br>

        <label>Stok</label><br>
        <input type="number" name="stok"><br><br>

        <button type="submit">Simpan</button>
    </form>

    <br>
    <a href="/books">Kembali</a>
</body>
</html>
