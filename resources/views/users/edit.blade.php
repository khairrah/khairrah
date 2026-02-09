@extends('layouts.admin')

@section('content')
<h1 class="text-3xl font-bold mb-6" style="color: #374151;">Edit User</h1>

<div class="max-w-md rounded shadow p-6" style="background-color: #FFF7E6; border: 2px solid #CDEDEA;">
    <form method="POST" action="{{ route('users.update', $user->id) }}">
        @csrf
        @method('PUT')

        <div class="mb-4">
            <label class="block text-sm font-semibold mb-2" style="color: #374151;">Nama</label>
            <input type="text" name="name" value="{{ $user->name }}" required class="w-full px-3 py-2 border border-gray-300 rounded" style="background-color: #CDEDEA;">
            @error('name')
                <span class="text-sm" style="color: #e74c3c;">{{ $message }}</span>
            @enderror
        </div>

        <div class="mb-4">
            <label class="block text-sm font-semibold mb-2" style="color: #374151;">Email</label>
            <input type="email" name="email" value="{{ $user->email }}" required class="w-full px-3 py-2 border border-gray-300 rounded" style="background-color: #CDEDEA;">
            @error('email')
                <span class="text-sm" style="color: #e74c3c;">{{ $message }}</span>
            @enderror
        </div>

        <div class="mb-4">
            <label class="block text-sm font-semibold mb-2" style="color: #374151;">Password (Kosongkan jika tidak ingin mengubah)</label>
            <input type="password" name="password" class="w-full px-3 py-2 border border-gray-300 rounded" style="background-color: #CDEDEA;">
            @error('password')
                <span class="text-sm" style="color: #e74c3c;">{{ $message }}</span>
            @enderror
        </div>

        <div class="mb-4">
            <label class="block text-sm font-semibold mb-2" style="color: #374151;">Konfirmasi Password</label>
            <input type="password" name="password_confirmation" class="w-full px-3 py-2 border border-gray-300 rounded" style="background-color: #CDEDEA;">
        </div>

        <div class="mb-6">
            <label class="block text-sm font-semibold mb-2" style="color: #374151;">Role</label>
            <select name="role" required class="w-full px-3 py-2 border border-gray-300 rounded" style="background-color: #CDEDEA;">
                <option value="admin" {{ $user->role === 'admin' ? 'selected' : '' }}>Admin</option>
                <option value="siswa" {{ $user->role === 'siswa' ? 'selected' : '' }}>Siswa</option>
                <option value="petugas" {{ $user->role === 'petugas' ? 'selected' : '' }}>Petugas</option>
            </select>
            @error('role')
                <span class="text-sm" style="color: #e74c3c;">{{ $message }}</span>
            @enderror
        </div>

        <div class="flex gap-3">
            <button type="submit" class="flex-1 py-2 rounded font-semibold" style="background-color: #CDEDEA; color: #374151; cursor: pointer;">
                Simpan
            </button>
            <a href="{{ route('users.index') }}" class="flex-1 py-2 rounded font-semibold text-center" style="background-color: #CDEDEA; color: #374151; text-decoration: none;">
                Batal
            </a>
        </div>
    </form>
</div>

@endsection
