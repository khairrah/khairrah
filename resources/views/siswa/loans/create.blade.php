@extends('layouts.siswa')

@section('content')

<div style="padding: 20px;">
    <h1 style="font-size: 22px; font-weight: bold; margin-bottom: 15px;">
        📚 Pinjam Buku
    </h1>

    {{-- ERROR --}}
    @if ($errors->any())
        <div style="background-color: #FEE2E2; color: red; padding: 10px; margin-bottom: 10px;">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>• {{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('siswa.loans.store') }}" method="POST">
        @csrf

        {{-- PILIH BUKU --}}
        <div style="margin-bottom: 10px;">
            <label><b>Buku</b></label><br>
            <select name="book_id" required style="width: 100%; padding: 8px;">
                <option value="">-- Pilih Buku --</option>
                @foreach($books as $book)
                    <option value="{{ $book->id }}">
                        {{ $book->judul }} (stok: {{ $book->stok }})
                    </option>
                @endforeach
            </select>
        </div>

        {{-- JUMLAH --}}
        <div style="margin-bottom: 10px;">
            <label><b>Jumlah</b></label><br>
            <input type="number" name="jumlah" required 
                   style="width: 100%; padding: 8px;">
        </div>

        {{-- TANGGAL PINJAM --}}
        <div style="margin-bottom: 10px;">
            <label><b>Tanggal Pinjam</b></label><br>
            <input type="date" name="tanggal_pinjam" required 
                   style="width: 100%; padding: 8px;">
        </div>

        {{-- TANGGAL KEMBALI --}}
        <div style="margin-bottom: 10px;">
            <label><b>Tanggal Kembali</b></label><br>
            <input type="date" name="tanggal_kembali" required 
                   style="width: 100%; padding: 8px;">
        </div>

        {{-- BUTTON SUPER JELAS --}}
        <button type="submit"
            style="
                background-color: red;
                color: white;
                padding: 12px 20px;
                border: none;
                border-radius: 6px;
                margin-top: 15px;
                font-weight: bold;
                font-size: 16px;
                display: block;
                width: 100%;
            ">
            🔥 PINJAM SEKARANG
        </button>

    </form>
</div>

@endsection