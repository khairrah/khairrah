@extends('layouts.siswa')

@section('content')

<!-- HEADER BOX -->
<div class="bg-white p-6 rounded-[2rem] border border-gray-100 shadow-sm mb-6 flex items-center gap-4">
    <div class="text-3xl">
        🔐
    </div>
    <div>
        <h1 class="text-2xl font-bold text-gray-800 tracking-tight">
            Ganti Password
        </h1>
        <p class="text-[12px] text-gray-400">
            Amankan akun Anda dengan password yang kuat
        </p>
    </div>
</div>

<div class="max-w-md mx-auto md:mx-0">
    <div class="bg-white p-8 rounded-[2rem] border border-gray-100 shadow-sm">
        
        <form method="POST" action="{{ route('password.update') }}" class="space-y-4">
            @csrf
            @method('put')

            <!-- Current Password -->
            <div x-data="{ show: false }">
                <label class="block text-[11px] font-bold text-gray-400 uppercase tracking-widest mb-2 px-1">Password Saat Ini</label>
                <div class="relative">
                    <input :type="show ? 'text' : 'password'" name="current_password" required
                           class="w-full px-4 py-3 bg-gray-50/50 border border-gray-100 rounded-2xl text-[13px] focus:ring-1 focus:ring-blue-300 outline-none transition shadow-inner pr-12">
                    <button type="button" @click="show = !show" class="absolute right-4 top-1/2 transform -translate-y-1/2 text-gray-400 hover:text-blue-600 transition">
                        <svg x-show="!show" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                        <svg x-show="show" x-cloak class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l18 18"></path></svg>
                    </button>
                </div>
                @error('current_password', 'updatePassword')
                    <p class="text-red-500 text-[10px] mt-1 px-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- New Password -->
            <div x-data="{ show: false }">
                <label class="block text-[11px] font-bold text-gray-400 uppercase tracking-widest mb-2 px-1">Password Baru</label>
                <div class="relative">
                    <input :type="show ? 'text' : 'password'" name="password" required
                           class="w-full px-4 py-3 bg-gray-50/50 border border-gray-100 rounded-2xl text-[13px] focus:ring-1 focus:ring-blue-300 outline-none transition shadow-inner pr-12">
                    <button type="button" @click="show = !show" class="absolute right-4 top-1/2 transform -translate-y-1/2 text-gray-400 hover:text-blue-600 transition">
                        <svg x-show="!show" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                        <svg x-show="show" x-cloak class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l18 18"></path></svg>
                    </button>
                </div>
                @error('password', 'updatePassword')
                    <p class="text-red-500 text-[10px] mt-1 px-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Confirm Password -->
            <div x-data="{ show: false }">
                <label class="block text-[11px] font-bold text-gray-400 uppercase tracking-widest mb-2 px-1">Konfirmasi Password Baru</label>
                <div class="relative">
                    <input :type="show ? 'text' : 'password'" name="password_confirmation" required
                           class="w-full px-4 py-3 bg-gray-50/50 border border-gray-100 rounded-2xl text-[13px] focus:ring-1 focus:ring-blue-300 outline-none transition shadow-inner pr-12">
                    <button type="button" @click="show = !show" class="absolute right-4 top-1/2 transform -translate-y-1/2 text-gray-400 hover:text-blue-600 transition">
                        <svg x-show="!show" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                        <svg x-show="show" x-cloak class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l18 18"></path></svg>
                    </button>
                </div>
                @error('password_confirmation', 'updatePassword')
                    <p class="text-red-500 text-[10px] mt-1 px-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="pt-2">
                <button type="submit"
                        class="w-full bg-blue-600 text-white py-3 rounded-2xl font-bold text-sm hover:bg-blue-700 transition shadow-lg shadow-blue-100">
                    Simpan Perubahan
                </button>
            </div>

            @if (session('status') === 'password-updated')
                <p x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 3000)"
                   class="text-green-600 text-center text-xs font-bold mt-2">
                   ✅ Password berhasil diperbarui!
                </p>
            @endif

        </form>

    </div>
</div>

@endsection
