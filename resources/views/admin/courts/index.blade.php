@extends('layouts.admin')

@section('title', 'Daftar Lapangan')
@section('header', 'Manajemen Lapangan')

@section('content')
<div class="row">
    <div class="col-12">
        @if(session('success'))
            <div class="alert alert-success alert-dismissible shadow-sm border-0">
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                <h5><i class="icon fas fa-check"></i> Berhasil!</h5>
                {{ session('success') }}
            </div>
        @endif

        <div class="card card-outline card-primary shadow-sm border-0 overflow-hidden">
            <div class="card-header border-0 mt-2">
                <h3 class="card-title font-weight-bold text-uppercase small tracking-wider text-muted">Daftar Seluruh Lapangan</h3>
                <div class="card-tools">
                    <a href="{{ route('admin.courts.create') }}" class="btn btn-primary btn-sm font-weight-bold px-3">
                        <i class="fas fa-plus mr-1"></i> TAMBAH LAPANGAN
                    </a>
                </div>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover table-valign-middle mb-0">
                        <thead class="bg-light">
                            <tr>
                                <th style="width: 50px" class="text-center border-0">#</th>
                                <th style="width: 100px" class="border-0">Foto</th>
                                <th class="border-0">Nama Lapangan</th>
                                <th class="border-0">Harga / Jam</th>
                                <th class="text-center border-0">Status</th>
                                <th style="width: 150px" class="text-right border-0">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($courts as $court)
                            <tr>
                                <td class="text-center text-muted small">{{ $loop->iteration }}</td>
                                <td>
                                    @if($court->photo)
                                        <div class="img-thumbnail border-0 shadow-sm p-0 rounded overflow-hidden" style="width: 80px; height: 50px;">
                                            <img src="{{ asset('storage/' . $court->photo) }}" alt="{{ $court->name }}" style="width: 100%; height: 100%; object-fit: cover;">
                                        </div>
                                    @else
                                        <div class="bg-light rounded text-center d-flex align-items-center justify-content-center border" style="width: 80px; height: 50px;">
                                            <i class="fas fa-image text-muted opacity-25"></i>
                                        </div>
                                    @endif
                                </td>
                                <td class="font-weight-bold text-dark">{{ $court->name }}</td>
                                <td class="text-primary font-weight-bold">
                                    <small>Rp</small> {{ number_format($court->price_per_hour, 0, ',', '.') }}
                                </td>
                                <td class="text-center">
                                    @if($court->is_active)
                                        <span class="badge bg-success-gradient px-3 py-2 shadow-sm" style="background: linear-gradient(135deg, #28a745 0%, #218838 100%)">AKTIF</span>
                                    @else
                                        <span class="badge bg-danger-gradient px-3 py-2 shadow-sm" style="background: linear-gradient(135deg, #dc3545 0%, #c82333 100%)">NONAKTIF</span>
                                    @endif
                                </td>
                                <td class="text-right">
                                    <div class="btn-group shadow-sm rounded overflow-hidden">
                                        <a href="{{ route('admin.courts.edit', $court) }}" class="btn btn-info btn-sm px-3" title="Edit">
                                           <i class="fas fa-edit"></i>
                                        </a>
                                        <form action="{{ route('admin.courts.destroy', $court) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus lapangan ini?')" class="d-inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-danger btn-sm px-3 border-left" title="Hapus">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="6" class="py-5 text-center text-muted">
                                    <i class="fas fa-folder-open fa-2x mb-2 d-block opacity-25"></i>
                                    Belum ada data lapangan.
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
