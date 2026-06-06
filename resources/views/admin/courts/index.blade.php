@extends('layouts.admin')

@section('title', 'Manajemen Lapangan')
@section('header', 'Manajemen Lapangan')

@section('content')
<div class="mb-4 mt-n2 d-flex justify-content-between align-items-center flex-wrap">
    <p class="text-muted text-sm mb-0">Kelola dan pantau seluruh lapangan padel yang terdaftar di sistem.</p>
    <a href="{{ route('admin.courts.create') }}" class="btn btn-primary font-weight-bold px-4 rounded-xl">
        <i class="fas fa-plus mr-2"></i> Tambah Lapangan
    </a>
</div>

<!-- Search & Filter Bar -->
<div class="card p-3 border-gray-200 mb-4 bg-white">
    <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
        <!-- Search Input -->
        <div class="flex-grow-1" style="max-width: 420px; min-width: 260px;">
            <div class="input-group">
                <div class="input-group-prepend">
                    <span class="input-group-text bg-transparent border-right-0 border-gray-200">
                        <i class="fas fa-search text-muted"></i>
                    </span>
                </div>
                <input type="text" id="search-court" class="form-control border-left-0 border-gray-200" placeholder="Cari nama lapangan atau lokasi...">
            </div>
        </div>
        
        <!-- Filters Group -->
        <div class="d-flex align-items-center flex-wrap gap-2">
            <!-- Court Type Filter -->
            <div class="btn-group" role="group" id="type-filter">
                <button type="button" class="btn btn-outline-primary active text-xs py-2 px-3" data-type="all">Semua Tipe</button>
                <button type="button" class="btn btn-outline-primary text-xs py-2 px-3" data-type="Indoor">Indoor</button>
                <button type="button" class="btn btn-outline-primary text-xs py-2 px-3" data-type="Outdoor">Outdoor</button>
            </div>
            
            <!-- Court Status Filter -->
            <div class="btn-group" role="group" id="status-filter">
                <button type="button" class="btn btn-outline-primary active text-xs py-2 px-3" data-status="all">Semua Status</button>
                <button type="button" class="btn btn-outline-primary text-xs py-2 px-3" data-status="active">Active</button>
                <button type="button" class="btn btn-outline-primary text-xs py-2 px-3" data-status="inactive">Inactive</button>
            </div>
        </div>
    </div>
</div>

<!-- Court Cards Grid -->
<div class="row" id="court-grid">
    @forelse($courts as $court)
        @php
            // Extract location from description or default
            $location = 'Jakarta';
            if (stripos($court->description, 'Jakarta Selatan') !== false) {
                $location = 'Jakarta Selatan';
            } elseif (stripos($court->description, 'Jakarta Barat') !== false) {
                $location = 'Jakarta Barat';
            } elseif (stripos($court->description, 'Tangerang') !== false) {
                $location = 'Tangerang';
            } elseif (stripos($court->description, 'Bekasi') !== false) {
                $location = 'Bekasi';
            }
            
            // Extract type
            $type = 'Outdoor';
            if (stripos($court->facilities, 'indoor') !== false || stripos($court->name, 'indoor') !== false || stripos($court->description, 'indoor') !== false) {
                $type = 'Indoor';
            }
            
            // Format price per hour
            $price = $court->price_weekday ?: $court->price_per_hour ?: 150000;
        @endphp
        
        <div class="col-md-6 col-lg-4 mb-4 court-card-item" 
             data-name="{{ strtolower($court->name) }}"
             data-location="{{ strtolower($location) }}"
             data-type="{{ $type }}"
             data-status="{{ $court->is_active ? 'active' : 'inactive' }}">
             
            <div class="card h-100 border-gray-200 overflow-hidden bg-white shadow-none hover-shadow transition" style="border-radius: 1.5rem !important;">
                <!-- Card Image -->
                <div class="position-relative bg-light" style="height: 200px;">
                    @if($court->photo)
                        <img src="{{ asset('storage/' . $court->photo) }}" alt="{{ $court->name }}" class="w-100 h-100" style="object-fit: cover;">
                    @else
                        <div class="w-100 h-100 d-flex flex-column align-items-center justify-content-center text-muted">
                            <i class="fas fa-image fa-3x mb-2 opacity-25"></i>
                            <span class="text-xs">No Image Available</span>
                        </div>
                    @endif
                    
                    <!-- Type Badge -->
                    <span class="badge position-absolute" style="top: 15px; left: 15px; background: rgba(255, 255, 255, 0.9); color: #1f2937; padding: 0.4rem 0.8rem; font-size: 0.75rem; border-radius: 0.75rem !important;">
                        {{ $type }}
                    </span>
                    
                    <!-- Status Badge -->
                    <span class="badge position-absolute {{ $court->is_active ? 'badge-success' : 'badge-danger' }}" style="top: 15px; right: 15px; padding: 0.4rem 0.8rem; font-size: 0.75rem; border-radius: 0.75rem !important;">
                        {{ $court->is_active ? 'Active' : 'Inactive' }}
                    </span>
                </div>
                
                <!-- Card Details -->
                <div class="card-body p-4 d-flex flex-column justify-content-between">
                    <div>
                        <h4 class="font-weight-bold text-dark mb-1">{{ $court->name }}</h4>
                        <div class="d-flex align-items-center text-muted text-sm mb-3">
                            <i class="fas fa-map-marker-alt text-emerald mr-1.5" style="color: #10b981;"></i>
                            <span>{{ $location }}</span>
                        </div>
                        
                        <p class="text-muted text-xs line-clamp-3 mb-4" style="min-height: 50px;">
                            {{ $court->description ?: 'Tidak ada deskripsi lapangan.' }}
                        </p>
                    </div>
                    
                    <div>
                        <div class="d-flex justify-content-between align-items-baseline mb-4 border-top pt-3">
                            <span class="text-muted text-xs uppercase tracking-wider font-weight-bold">Price per Hour</span>
                            <span class="font-weight-extrabold text-emerald text-lg" style="color: #10b981;">
                                Rp {{ number_format($price, 0, ',', '.') }}<span class="text-muted text-xs font-weight-normal">/Jam</span>
                            </span>
                        </div>
                        
                        <!-- Action Buttons -->
                        <div class="d-flex gap-2">
                            <a href="{{ route('admin.courts.edit', $court) }}" class="btn btn-outline-primary flex-grow-1 text-xs py-2">
                                <i class="fas fa-edit mr-1"></i> Edit
                            </a>
                            <button class="btn btn-outline-primary text-xs py-2 px-3 view-schedule-btn" 
                                    data-id="{{ $court->id }}" 
                                    data-name="{{ $court->name }}"
                                    data-bookings='@json($court->bookings()->whereDate("booking_date", ">=", today())->with("user")->get())'>
                                Schedule
                            </button>
                            <form action="{{ route('admin.courts.destroy', $court) }}" method="POST" class="d-inline delete-confirm">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger text-xs py-2 px-3" title="Delete">
                                    <i class="fas fa-trash-alt"></i>
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @empty
        <div class="col-12 py-5 text-center text-muted bg-white border-gray-200 rounded-2xl">
            <i class="fas fa-calendar-times fa-3x mb-3 d-block opacity-25"></i>
            <h5>Belum ada lapangan terdaftar</h5>
            <p class="text-xs mb-0">Klik tombol "Tambah Lapangan" untuk menambahkan lapangan baru.</p>
        </div>
    @endforelse
</div>

<!-- View Schedule Modal -->
<div class="modal fade" id="scheduleModal" tabindex="-1" role="dialog" aria-labelledby="scheduleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
        <div class="modal-content border-0 rounded-2xl bg-white shadow-lg overflow-hidden">
            <div class="modal-header border-0 bg-light p-4">
                <h5 class="modal-title font-weight-bold text-dark" id="scheduleModalLabel">Jadwal Lapangan</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body p-4">
                <h6 class="font-weight-bold text-muted uppercase tracking-wider text-xs mb-3">Upcoming Bookings</h6>
                <div class="table-responsive">
                    <table class="table table-hover mb-0" id="modal-booking-table">
                        <thead>
                            <tr>
                                <th>User</th>
                                <th>Tanggal</th>
                                <th>Jam</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody id="modal-booking-list">
                            <!-- JS populated -->
                        </tbody>
                    </table>
                </div>
                <div id="no-bookings-message" class="text-center py-4 text-muted d-none">
                    <i class="far fa-calendar-check fa-2x mb-2 d-block opacity-25"></i>
                    <p class="text-xs mb-0">Tidak ada booking mendatang untuk lapangan ini.</p>
                </div>
            </div>
            <div class="modal-footer border-0 p-4">
                <button type="button" class="btn btn-outline-primary text-xs py-2" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

@endsection

@push('styles')
<style>
    .gap-2 {
        gap: 0.5rem;
    }
    .hover-shadow:hover {
        transform: translateY(-4px);
        box-shadow: 0 10px 25px rgba(0, 0, 0, 0.05) !important;
    }
    .transition {
        transition: all 0.3s cubic-bezier(0.16, 1, 0.3, 1);
    }
    .line-clamp-3 {
        display: -webkit-box;
        -webkit-line-clamp: 3;
        -webkit-box-orient: vertical;  
        overflow: hidden;
    }
</style>
@endpush

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const searchInput = document.getElementById('search-court');
        const typeButtons = document.querySelectorAll('#type-filter button');
        const statusButtons = document.querySelectorAll('#status-filter button');
        const courtCards = document.querySelectorAll('.court-card-item');
        
        let currentType = 'all';
        let currentStatus = 'all';
        let searchQuery = '';
        
        function filterCards() {
            courtCards.forEach(card => {
                const name = card.getAttribute('data-name');
                const location = card.getAttribute('data-location');
                const type = card.getAttribute('data-type');
                const status = card.getAttribute('data-status');
                
                const matchesSearch = name.includes(searchQuery) || location.includes(searchQuery);
                const matchesType = currentType === 'all' || type === currentType;
                const matchesStatus = currentStatus === 'all' || status === currentStatus;
                
                if (matchesSearch && matchesType && matchesStatus) {
                    card.style.display = 'block';
                } else {
                    card.style.display = 'none';
                }
            });
        }
        
        // Search Input listener
        searchInput.addEventListener('input', function(e) {
            searchQuery = e.target.value.toLowerCase();
            filterCards();
        });
        
        // Type filter listener
        typeButtons.forEach(btn => {
            btn.addEventListener('click', function() {
                typeButtons.forEach(b => b.classList.remove('active'));
                this.classList.add('active');
                currentType = this.getAttribute('data-type');
                filterCards();
            });
        });
        
        // Status filter listener
        statusButtons.forEach(btn => {
            btn.addEventListener('click', function() {
                statusButtons.forEach(b => b.classList.remove('active'));
                this.classList.add('active');
                currentStatus = this.getAttribute('data-status');
                filterCards();
            });
        });
        
        // Schedule modal listener
        $('.view-schedule-btn').on('click', function() {
            const courtName = $(this).data('name');
            const bookings = $(this).data('bookings');
            
            $('#scheduleModalLabel').text('Jadwal - ' + courtName);
            
            const tbody = $('#modal-booking-list');
            tbody.empty();
            
            if (bookings && bookings.length > 0) {
                $('#modal-booking-table').removeClass('d-none');
                $('#no-bookings-message').addClass('d-none');
                
                bookings.forEach(booking => {
                    // format date
                    const d = new Date(booking.booking_date);
                    const formattedDate = d.toLocaleDateString('id-ID', { day: '2-digit', month: 'short', year: 'numeric' });
                    const timeRange = booking.start_time.substring(0,5) + ' - ' + booking.end_time.substring(0,5) + ' WIB';
                    
                    let statusBadge = '';
                    if (booking.status === 'pending') {
                        statusBadge = '<span class="badge badge-warning">Pending</span>';
                    } else if (booking.status === 'approved' || booking.status === 'confirmed') {
                        statusBadge = '<span class="badge badge-success">Confirmed</span>';
                    } else if (booking.status === 'completed') {
                        statusBadge = '<span class="badge badge-info">Completed</span>';
                    } else {
                        statusBadge = `<span class="badge badge-danger">${booking.status}</span>`;
                    }
                    
                    tbody.append(`
                        <tr>
                            <td class="font-weight-bold text-dark">${booking.user.name}</td>
                            <td>${formattedDate}</td>
                            <td>${timeRange}</td>
                            <td>${statusBadge}</td>
                        </tr>
                    `);
                });
            } else {
                $('#modal-booking-table').addClass('d-none');
                $('#no-bookings-message').removeClass('d-none');
            }
            
            $('#scheduleModal').modal('show');
        });
    });
</script>
@endpush

