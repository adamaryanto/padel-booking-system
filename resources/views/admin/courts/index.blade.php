@extends('layouts.admin')

@section('title', 'Daftar Lapangan')
@section('header', 'Manajemen Lapangan')

@section('content')
<div class="relative overflow-x-auto shadow-md sm:rounded-lg p-4 bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700">
    <div class="flex items-center justify-between pb-4 bg-white dark:bg-gray-800">
        <div>
            <h3 class="text-lg font-bold text-gray-900 dark:text-white">Daftar Seluruh Lapangan</h3>
        </div>
        <a href="{{ route('admin.courts.create') }}" class="text-white bg-primary-600 hover:bg-primary-700 focus:ring-4 focus:outline-none focus:ring-primary-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center inline-flex items-center dark:bg-primary-500 dark:hover:bg-primary-600 dark:focus:ring-primary-800 shadow-lg shadow-primary-500/20">
            <i data-lucide="plus" class="w-4 h-4 me-2"></i>
            Tambah Lapangan
        </a>
    </div>

    <table class="w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400">
        <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
            <tr>
                <th scope="col" class="px-6 py-3">#</th>
                <th scope="col" class="px-6 py-3">Foto</th>
                <th scope="col" class="px-6 py-3">Nama Lapangan</th>
                <th scope="col" class="px-6 py-3">Harga / Jam</th>
                <th scope="col" class="px-6 py-3 text-center">Status</th>
                <th scope="col" class="px-6 py-3 text-right">Aksi</th>
            </tr>
        </thead>
        <tbody>
            @forelse($courts as $court)
            <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600">
                <td class="px-6 py-4">{{ $loop->iteration }}</td>
                <td class="px-6 py-4">
                    @if($court->photo)
                        <img src="{{ asset('storage/' . $court->photo) }}" alt="{{ $court->name }}" class="w-20 h-12 object-cover rounded shadow-sm">
                    @else
                        <span class="bg-gray-100 text-gray-800 text-xs font-medium px-2.5 py-0.5 rounded dark:bg-gray-700 dark:text-gray-300">No Photo</span>
                    @endif
                </td>
                <td class="px-6 py-4 font-semibold text-gray-900 dark:text-white">{{ $court->name }}</td>
                <td class="px-6 py-4">Rp. {{ number_format($court->price_per_hour, 0, ',', '.') }}</td>
                <td class="px-6 py-4 text-center">
                    @if($court->is_active)
                        <span class="bg-green-100 text-green-800 text-xs font-medium px-2.5 py-0.5 rounded-full dark:bg-green-900/30 dark:text-green-500 border border-green-200 dark:border-green-800">Aktif</span>
                    @else
                        <span class="bg-red-100 text-red-800 text-xs font-medium px-2.5 py-0.5 rounded-full dark:bg-red-900/30 dark:text-red-500 border border-red-200 dark:border-red-800">Nonaktif</span>
                    @endif
                </td>
                <td class="px-6 py-4 text-right">
                    <div class="inline-flex space-x-2">
                        <a href="{{ route('admin.courts.edit', $court) }}" class="text-blue-600 hover:text-blue-900 dark:text-blue-500 dark:hover:text-blue-400 font-medium">
                           <i data-lucide="edit-3" class="w-5 h-5"></i>
                        </a>
                        <form action="{{ route('admin.courts.destroy', $court) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus lapangan ini?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-red-600 hover:text-red-900 dark:text-red-500 dark:hover:text-red-400 font-medium">
                                <i data-lucide="trash-2" class="w-5 h-5"></i>
                            </button>
                        </form>
                    </div>
                </td>
            </tr>
            @empty
            <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700">
                <td colspan="6" class="px-6 py-4 text-center text-gray-400 italic">Belum ada data lapangan.</td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection
