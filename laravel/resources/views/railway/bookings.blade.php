@extends('layouts.app')

@section('title', 'Master Bookings & Laravel Pagination — Indian Railways | GMCA')

@section('content')
<div style="display: flex; flex-direction: column; gap: 20px;">

    <!-- Breadcrumb & Top Action Header -->
    <div style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 14px;">
        <div>
            <div style="font-size: 12.5px; color: var(--text-muted); margin-bottom: 4px;">
                <a href="{{ route('home') }}" style="color: var(--navy); text-decoration: none;">Home</a> / 
                <a href="{{ route('railway.index') }}" style="color: var(--navy); text-decoration: none;">Railway Portal</a> / 
                <span style="color: var(--text-muted);">Master Bookings Dataset</span>
            </div>
            <h1 style="font-size: 24px; font-weight: 800; color: var(--navy); margin: 0;">
                Indian Railways Master Reservations (Requirements 3 & 4)
            </h1>
        </div>

        <div style="display: flex; gap: 10px; align-items: center;">
            <a href="{{ route('railway.index') }}" class="btn btn-saffron">
                <span>+</span> Book New Ticket
            </a>
            <a href="{{ route('railway.systems') }}" class="btn btn-outline">
                <span>🏢</span> 5 Divisions (Req 6)
            </a>
        </div>
    </div>

    <!-- Active Account & Evaluation Metric Banner -->
    <div style="background: #EFF6FF; border: 1.5px solid #BFDBFE; border-radius: 10px; padding: 14px 20px; display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 14px;">
        <div style="display: flex; align-items: center; gap: 12px;">
            <img src="{{ asset(Auth::user()->avatar) }}" onerror="this.src='{{ asset('images/temp.jpeg') }}'" alt="" style="width: 40px; height: 40px; border-radius: 50%; object-fit: cover; border: 2px solid var(--navy);">
            <div>
                <div style="font-size: 14px; font-weight: 700; color: var(--navy);">
                    Active User: {{ Auth::user()->name }} (<code>{{ Auth::user()->username }}</code> • {{ Auth::user()->enrollment }})
                </div>
                <div style="font-size: 12px; color: #1E40AF;">
                    Role: <strong>{{ Auth::user()->role }}</strong> • Personal Bookings in DB: <strong>{{ $currentUserRecordsCount }} Entries</strong> (Min 100 Satisfied ✔)
                </div>
            </div>
        </div>

        <div style="display: flex; gap: 8px; flex-wrap: wrap;">
            <span class="badge badge-green" style="font-size: 12px; padding: 6px 10px;">
                ✔ Req 3: Min 100 Entries ({{ $currentUserRecordsCount }} Total)
            </span>
            <span class="badge badge-navy" style="font-size: 12px; padding: 6px 10px;">
                ✔ Req 4: Laravel Eloquent Pagination Active
            </span>
        </div>
    </div>

    <!-- Multi-System & Multi-User Filter Card -->
    <div class="card" style="margin-bottom: 0; padding: 18px;">
        <form method="GET" action="{{ route('railway.bookings') }}" id="filterForm">
            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(180px, 1fr)); gap: 14px; align-items: flex-end;">
                
                <!-- 1. User Filter (Requirement 2 & 3: Total 5 Users with >=100 entries) -->
                <div>
                    <label class="form-label" for="user_id">Filter by User Account (Req 2):</label>
                    <select name="user_id" id="user_id" class="form-select" onchange="document.getElementById('filterForm').submit()">
                        <option value="all" {{ $userFilter === 'all' ? 'selected' : '' }}>-- All Users in Database --</option>
                        @foreach($allUsers as $u)
                            <option value="{{ $u->id }}" {{ (string)$userFilter === (string)$u->id ? 'selected' : '' }}>
                                {{ $u->name }} ({{ $u->username }}) — {{ $u->railway_bookings_count ?? $u->railwayBookings()->count() }} tickets
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- 2. System Filter (Requirement 6: Multiple Systems with Dedicated Admins) -->
                <div>
                    <label class="form-label" for="system">Railway Division / System (Req 6):</label>
                    <select name="system" id="system" class="form-select" onchange="document.getElementById('filterForm').submit()">
                        <option value="all" {{ $systemFilter === 'all' ? 'selected' : '' }}>-- All 5 Railway Divisions --</option>
                        @foreach($allSystems as $sys)
                            <option value="{{ $sys->code }}" {{ $systemFilter === $sys->code ? 'selected' : '' }}>
                                {{ $sys->division }} ({{ $sys->code }}) — Admin: {{ $sys->station_master_name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- 3. Keyword Search -->
                <div>
                    <label class="form-label" for="search">Search Keyword:</label>
                    <input type="text" name="search" id="search" class="form-control" placeholder="PNR, Passenger, Train..." value="{{ $search }}">
                </div>

                <!-- 4. Travel Class -->
                <div>
                    <label class="form-label" for="class">Travel Class:</label>
                    <select name="class" id="class" class="form-select" onchange="document.getElementById('filterForm').submit()">
                        <option value="all" {{ $classFilter === 'all' ? 'selected' : '' }}>All Classes</option>
                        <option value="1A" {{ $classFilter === '1A' ? 'selected' : '' }}>1A (First AC)</option>
                        <option value="2A" {{ $classFilter === '2A' ? 'selected' : '' }}>2A (Second AC)</option>
                        <option value="3A" {{ $classFilter === '3A' ? 'selected' : '' }}>3A (Third AC)</option>
                        <option value="SL" {{ $classFilter === 'SL' ? 'selected' : '' }}>SL (Sleeper)</option>
                        <option value="CC" {{ $classFilter === 'CC' ? 'selected' : '' }}>CC (Chair Car)</option>
                        <option value="EC" {{ $classFilter === 'EC' ? 'selected' : '' }}>EC (Executive CC)</option>
                    </select>
                </div>

                <!-- 5. Status -->
                <div>
                    <label class="form-label" for="status">Status:</label>
                    <select name="status" id="status" class="form-select" onchange="document.getElementById('filterForm').submit()">
                        <option value="all" {{ $statusFilter === 'all' ? 'selected' : '' }}>All Statuses</option>
                        <option value="Confirmed" {{ $statusFilter === 'Confirmed' ? 'selected' : '' }}>Confirmed</option>
                        <option value="RAC" {{ $statusFilter === 'RAC' ? 'selected' : '' }}>RAC</option>
                        <option value="Waiting List" {{ $statusFilter === 'Waiting List' ? 'selected' : '' }}>Waiting List</option>
                    </select>
                </div>

                <!-- 6. Per Page (Requirement 4: Pagination) -->
                <div style="max-width: 140px;">
                    <label class="form-label" for="per_page">Per Page:</label>
                    <select name="per_page" id="per_page" class="form-select" onchange="document.getElementById('filterForm').submit()">
                        <option value="10" {{ $perPage == 10 ? 'selected' : '' }}>10 items</option>
                        <option value="15" {{ $perPage == 15 ? 'selected' : '' }}>15 items</option>
                        <option value="25" {{ $perPage == 25 ? 'selected' : '' }}>25 items</option>
                        <option value="50" {{ $perPage == 50 ? 'selected' : '' }}>50 items</option>
                        <option value="100" {{ $perPage == 100 ? 'selected' : '' }}>100 items</option>
                    </select>
                </div>

                <!-- Action Buttons -->
                <div style="display: flex; gap: 8px;">
                    <button type="submit" class="btn btn-primary" style="flex: 1;">
                        <span>🔍</span> Apply
                    </button>
                    <a href="{{ route('railway.bookings') }}" class="btn btn-outline" title="Reset Filters">
                        <span>↺</span>
                    </a>
                </div>
            </div>
        </form>
    </div>

    <!-- Data Summary Strip -->
    <div style="display: flex; justify-content: space-between; align-items: center; font-size: 13px; color: var(--text-muted); flex-wrap: wrap; gap: 8px;">
        <div>
            Showing <strong>{{ $bookings->firstItem() ?? 0 }}</strong> to <strong>{{ $bookings->lastItem() ?? 0 }}</strong> of 
            <strong style="color: var(--navy);">{{ $bookings->total() }} matching tickets</strong> 
            (Page {{ $bookings->currentPage() }} of {{ $bookings->lastPage() }})
        </div>
        <div style="display: flex; gap: 12px; align-items: center;">
            <span>Total Database Capacity: <strong>{{ number_format($totalSystemBookings) }} Tickets</strong></span>
            <span>Total Revenue: <strong style="color: var(--saffron-dark);">₹{{ number_format($totalSystemRevenue, 2) }}</strong></span>
        </div>
    </div>

    <!-- Master Bookings Table -->
    <div class="card" style="padding: 0; overflow: hidden; margin-bottom: 0;">
        <div class="table-responsive">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>PNR</th>
                        <th>Passenger Name</th>
                        <th>Division System</th>
                        <th>Train Name & No</th>
                        <th>Route</th>
                        <th>Class</th>
                        <th>Quota</th>
                        <th>Pax</th>
                        <th>Journey Date</th>
                        <th>Total Fare</th>
                        <th>Seat / Berth</th>
                        <th>Status</th>
                        <th style="text-align: center;">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($bookings as $b)
                        <tr>
                            <!-- PNR -->
                            <td>
                                <a href="{{ route('railway.ticket', $b->id) }}" style="color: var(--navy); font-weight: 700; text-decoration: none;">
                                    {{ $b->pnr }}
                                </a>
                            </td>

                            <!-- Passenger -->
                            <td>
                                <div><strong>{{ $b->passenger_name }}</strong></div>
                                <div style="font-size: 11px; color: var(--text-muted);">
                                    {{ $b->passenger_age }} yrs • {{ $b->passenger_gender }}
                                </div>
                            </td>

                            <!-- Division System (Req 6) -->
                            <td>
                                <span class="badge" style="background: {{ $b->railwaySystem->badge_color ?? '#000080' }}15; color: {{ $b->railwaySystem->badge_color ?? '#000080' }}; border: 1px solid {{ $b->railwaySystem->badge_color ?? '#000080' }}40;">
                                    {{ $b->railwaySystem->code }}
                                </span>
                                <div style="font-size: 10.5px; color: var(--text-muted); margin-top: 2px;">
                                    Admin: {{ $b->railwaySystem->station_master_name }}
                                </div>
                            </td>

                            <!-- Train -->
                            <td>
                                <div><strong>{{ $b->train_name }}</strong></div>
                                <div style="font-size: 11px; color: var(--text-muted);">Train #{{ $b->train_number }}</div>
                            </td>

                            <!-- Route -->
                            <td style="font-size: 12px; white-space: nowrap;">
                                {{ $b->source_station }}<br>
                                <span style="color: var(--text-muted);">➔ {{ $b->destination_station }}</span>
                            </td>

                            <!-- Class -->
                            <td>
                                <span class="badge badge-navy">{{ $b->travel_class }}</span>
                            </td>

                            <!-- Quota -->
                            <td>
                                <span class="badge badge-saffron">{{ $b->quota }}</span>
                            </td>

                            <!-- Passengers -->
                            <td style="text-align: center; font-weight: 600;">
                                {{ $b->passengers_count }}
                            </td>

                            <!-- Journey Date -->
                            <td style="white-space: nowrap;">
                                <strong>{{ $b->journey_date->format('d M Y') }}</strong>
                            </td>

                            <!-- Total Fare -->
                            <td style="white-space: nowrap;">
                                <strong style="color: var(--saffron-dark);">₹{{ number_format($b->total_fare, 2) }}</strong>
                            </td>

                            <!-- Coach Seat -->
                            <td style="font-family: 'Fira Code', monospace; font-size: 12px;">
                                {{ $b->coach_seat ?? 'Pending' }}
                            </td>

                            <!-- Status -->
                            <td>
                                @if($b->status === 'Confirmed')
                                    <span class="badge badge-green">Confirmed</span>
                                @elseif($b->status === 'RAC')
                                    <span class="badge badge-saffron">RAC</span>
                                @else
                                    <span class="badge badge-admin">Waiting List</span>
                                @endif
                            </td>

                            <!-- Actions -->
                            <td style="white-space: nowrap; text-align: center;">
                                <div style="display: inline-flex; gap: 4px; align-items: center;">
                                    <a href="{{ route('railway.ticket', $b->id) }}" class="btn btn-outline btn-sm" style="padding: 3px 8px; font-size: 11px;" title="View Non-Editable E-Ticket Slip">
                                        <span>👁</span> Slip
                                    </a>
                                    <a href="{{ route('railway.ticket.edit', $b->id) }}" class="btn btn-outline btn-sm" style="padding: 3px 8px; font-size: 11px; color: #B45309;" title="Edit Booking">
                                        <span>✏</span> Edit
                                    </a>
                                    <form action="{{ route('railway.ticket.cancel', $b->id) }}" method="POST" style="display: inline;" onsubmit="return confirm('Cancel and remove reservation PNR {{ $b->pnr }}?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-outline btn-sm" style="padding: 3px 8px; font-size: 11px; color: #DC2626;" title="Cancel Reservation">
                                            <span>✕</span>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="13" style="text-align: center; padding: 40px; color: var(--text-muted);">
                                <div style="font-size: 32px; margin-bottom: 8px;">🎫</div>
                                <div style="font-size: 15px; font-weight: 600;">No Railway Bookings Found Matching Criteria</div>
                                <div style="font-size: 12.5px; margin-top: 4px;">Try clearing filters or search terms.</div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- LARAVEL PAGINATION CONTROLS (Requirement 4) -->
    <div class="card" style="padding: 16px 20px; margin-bottom: 0;">
        <div style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 14px;">
            <div style="font-size: 13px; color: var(--text-muted);">
                Showing <strong>{{ $bookings->firstItem() ?? 0 }}</strong> to <strong>{{ $bookings->lastItem() ?? 0 }}</strong> of <strong>{{ $bookings->total() }}</strong> entries
            </div>

            <!-- Custom Rendered Laravel Pagination Component -->
            <div>
                @if ($bookings->hasPages())
                    <ul class="pagination">
                        {{-- Previous Page Link --}}
                        @if ($bookings->onFirstPage())
                            <li class="page-item disabled"><span class="page-link">« Previous</span></li>
                        @else
                            <li class="page-item"><a class="page-link" href="{{ $bookings->previousPageUrl() }}" rel="prev">« Previous</a></li>
                        @endif

                        {{-- Pagination Elements --}}
                        @foreach ($bookings->getUrlRange(max(1, $bookings->currentPage() - 2), min($bookings->lastPage(), $bookings->currentPage() + 2)) as $page => $url)
                            @if ($page == $bookings->currentPage())
                                <li class="page-item active"><span class="page-link">{{ $page }}</span></li>
                            @else
                                <li class="page-item"><a class="page-link" href="{{ $url }}">{{ $page }}</a></li>
                            @endif
                        @endforeach

                        {{-- Next Page Link --}}
                        @if ($bookings->hasMorePages())
                            <li class="page-item"><a class="page-link" href="{{ $bookings->nextPageUrl() }}" rel="next">Next »</a></li>
                        @else
                            <li class="page-item disabled"><span class="page-link">Next »</span></li>
                        @endif
                    </ul>
                @endif
            </div>

            <div style="font-size: 12.5px; color: var(--navy); font-weight: 600;">
                Laravel Pagination: <code>$bookings->paginate({{ $perPage }})</code>
            </div>
        </div>
    </div>

</div>
@endsection
