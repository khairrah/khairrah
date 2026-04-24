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

        <div class="mb-4" x-data="{ show: false }">
            <label class="block text-sm font-semibold mb-2" style="color: #374151;">Password (Kosongkan jika tidak ingin mengubah)</label>
            <div class="relative">
                <input :type="show ? 'text' : 'password'" name="password" class="w-full px-3 py-2 border border-gray-300 rounded pr-10" style="background-color: #CDEDEA;">
                <button type="button" @click="show = !show" class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-500 hover:text-gray-700">
                    <svg x-show="!show" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                    <svg x-show="show" x-cloak class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l18 18"></path></svg>
                </button>
            </div>
            @error('password')
                <span class="text-sm" style="color: #e74c3c;">{{ $message }}</span>
            @enderror
        </div>

        <div class="mb-4" x-data="{ show: false }">
            <label class="block text-sm font-semibold mb-2" style="color: #374151;">Konfirmasi Password</label>
            <div class="relative">
                <input :type="show ? 'text' : 'password'" name="password_confirmation" class="w-full px-3 py-2 border border-gray-300 rounded pr-10" style="background-color: #CDEDEA;">
                <button type="button" @click="show = !show" class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-500 hover:text-gray-700">
                    <svg x-show="!show" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                    <svg x-show="show" x-cloak class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l18 18"></path></svg>
                </button>
            </div>
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
