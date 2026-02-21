@extends('layouts.admin')

@section('title', 'Tambah Lapangan')
@section('header', 'Tambah Lapangan Baru')

@section('content')
<div class="max-w-2xl p-6 bg-white border border-gray-200 rounded-xl shadow-sm dark:bg-gray-800 dark:border-gray-700">
    <div class="flex items-center mb-6 pb-4 border-b border-gray-100 dark:border-gray-700">
        <i data-lucide="plus-circle" class="w-5 h-5 text-primary-500 me-2"></i>
        <h3 class="text-lg font-bold text-gray-900 dark:text-white">Formulir Lapangan</h3>
    </div>
    
    <form action="{{ route('admin.courts.store') }}" method="POST" enctype="multipart/form-data" class="space-y-4">
        @csrf
        <div>
            <label for="name" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Nama Lapangan</label>
            <input type="text" name="name" id="name" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500 @error('name') border-red-500 @enderror" placeholder="Contoh: Lapangan A" value="{{ old('name') }}" required>
            @error('name') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
        </div>

        <div>
            <label for="price_per_hour" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Harga Per Jam (Rp)</label>
            <div class="relative">
                <div class="absolute inset-y-0 start-0 flex items-center ps-3.5 pointer-events-none">
                    <span class="text-gray-500 text-sm font-bold">Rp</span>
                </div>
                <input type="number" name="price_per_hour" id="price_per_hour" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full ps-10 p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:text-white @error('price_per_hour') border-red-500 @enderror" value="{{ old('price_per_hour') }}" required>
            </div>
            @error('price_per_hour') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
        </div>

        <div>
            <label for="description" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Deskripsi</label>
            <textarea name="description" id="description" rows="3" class="block p-2.5 w-full text-sm text-gray-900 bg-gray-50 rounded-lg border border-gray-300 focus:ring-primary-500 focus:border-primary-500 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500" placeholder="Masukkan deskripsi lapangan">{{ old('description') }}</textarea>
            @error('description') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
        </div>

        <div>
            <label class="block mb-2 text-sm font-medium text-gray-900 dark:text-white" for="photo">Foto Lapangan</label>
            <input name="photo" id="photo" class="block w-full text-sm text-gray-900 border border-gray-300 rounded-lg cursor-pointer bg-gray-50 dark:text-gray-400 focus:outline-none dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 @error('photo') border-red-500 @enderror" type="file">
            <p class="mt-1 text-xs text-gray-500 dark:text-gray-300">Max: 2MB, Format: jpg, png, jpeg, webp</p>
            @error('photo') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
        </div>

        <div>
            <label for="is_active" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Status</label>
            <select name="is_active" id="is_active" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                <option value="1" {{ old('is_active') == '1' ? 'selected' : '' }}>Aktif</option>
                <option value="0" {{ old('is_active') == '0' ? 'selected' : '' }}>Nonaktif</option>
            </select>
        </div>

        <div class="flex items-center space-x-3 pt-4 border-t border-gray-100 dark:border-gray-700 mt-6">
            <button type="submit" class="text-white bg-primary-600 hover:bg-primary-700 focus:ring-4 focus:outline-none focus:ring-primary-300 font-bold rounded-lg text-sm px-5 py-2.5 text-center dark:bg-primary-500 dark:hover:bg-primary-600 shadow-lg shadow-primary-500/30 uppercase tracking-widest">
                Simpan Lapangan
            </button>
            <a href="{{ route('admin.courts.index') }}" class="py-2.5 px-5 text-sm font-medium text-gray-900 focus:outline-none bg-white rounded-lg border border-gray-200 hover:bg-gray-100 hover:text-primary-700 dark:bg-gray-800 dark:text-gray-400 dark:border-gray-600 dark:hover:text-white dark:hover:bg-gray-700 uppercase tracking-widest">
                Batal
            </a>
        </div>
    </form>
</div>
@endsection
