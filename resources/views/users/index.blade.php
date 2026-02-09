@extends('layouts.admin')

@section('content')
<h1 class="text-3xl font-bold mb-6" style="color: #374151;">Manajemen User</h1>

<a href="{{ route('users.create') }}" class="inline-block mb-4 px-4 py-2 rounded font-semibold" style="background-color: #CDEDEA; color: #374151;">
    + Tambah User
</a>

<div class="overflow-x-auto rounded shadow" style="background-color: #FFF7E6;">
<table class="w-full border">
    <thead style="background-color: #DCEBFA;">
        <tr>
            <th class="px-4 py-2" style="color: #374151;">No</th>
            <th class="px-4 py-2" style="color: #374151;">Nama</th>
            <th class="px-4 py-2" style="color: #374151;">Email</th>
            <th class="px-4 py-2" style="color: #374151;">Role</th>
            <th class="px-4 py-2" style="color: #374151;">Aksi</th>
        </tr>
    </thead>
    <tbody>
        @foreach($users as $index => $user)
        <tr class="border-b text-center">
            <td class="px-4 py-2">{{ $index + 1 }}</td>
            <td class="px-4 py-2">{{ $user->name }}</td>
            <td class="px-4 py-2">{{ $user->email }}</td>
            <td class="px-4 py-2">
                @if($user->role === 'admin')
                    <span class="bg-blue-100 text-blue-800 px-2 py-1 rounded font-semibold text-sm">Admin</span>
                @elseif($user->role === 'siswa')
                    <span class="bg-green-100 text-green-800 px-2 py-1 rounded font-semibold text-sm">Siswa</span>
                @else
                    <span class="bg-yellow-100 text-yellow-800 px-2 py-1 rounded font-semibold text-sm">Petugas</span>
                @endif
            </td>
            <td class="px-4 py-2 space-x-2">
                <a href="{{ route('users.edit', $user->id) }}" class="px-2 py-1 rounded font-semibold text-sm" style="background-color: #CDEDEA; color: #374151; text-decoration: none;">
                    Edit
                </a>
                <button onclick="openDeleteModal({{ $user->id }}, '{{ $user->name }}')" class="px-2 py-1 rounded font-semibold text-sm" style="background-color: #e74c3c; color: white; cursor: pointer;">
                    Hapus
                </button>
            </td>
        </tr>
        @endforeach
    </tbody>
</table>
</div>

<!-- Modal Delete -->
<div id="deleteModal" style="display: none; position: fixed; inset: 0; background-color: rgba(0, 0, 0, 0.5); align-items: center; justify-content: center; z-index: 50;">
    <div style="border-radius: 0.5rem; box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1); padding: 1.5rem; max-width: 28rem; margin-left: 1rem; margin-right: 1rem; background-color: #DCEBFA; border: 4px solid #CDEDEA;">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1rem;">
            <h2 style="font-size: 1.25rem; font-weight: bold; color: #374151;">Konfirmasi Hapus</h2>
            <button onclick="closeDeleteModal()" style="color: #9CA3AF; cursor: pointer; font-size: 1.5rem;">×</button>
        </div>
        <p style="margin-bottom: 1.5rem; color: #374151;">Hapus user <strong id="userName"></strong>?</p>
        <div style="display: flex; gap: 0.75rem;">
            <form id="deleteForm" method="POST" style="flex: 1;">
                @csrf
                @method('DELETE')
                <button type="submit" style="width: 100%; padding: 0.5rem; border-radius: 0.375rem; font-weight: 600; background-color: #e74c3c; color: white; cursor: pointer;">
                    Hapus
                </button>
            </form>
            <button onclick="closeDeleteModal()" style="flex: 1; padding: 0.5rem; border-radius: 0.375rem; font-weight: 600; background-color: #CDEDEA; color: #374151; cursor: pointer;">
                Batal
            </button>
        </div>
    </div>
</div>

<script>
    function openDeleteModal(id, name) {
        document.getElementById('userName').textContent = name;
        document.getElementById('deleteForm').action = `/users/${id}`;
        document.getElementById('deleteModal').style.display = 'flex';
    }

    function closeDeleteModal() {
        document.getElementById('deleteModal').style.display = 'none';
    }

    document.getElementById('deleteModal').addEventListener('click', function(e) {
        if (e.target === this) {
            closeDeleteModal();
        }
    });
</script>

@endsection
