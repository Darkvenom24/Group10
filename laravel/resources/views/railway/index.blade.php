@extends('layouts.app')

@section('title', 'Ticket Reservation Portal — Indian Railways | GMCA Laravel')

@section('content')
<div style="display: flex; flex-direction: column; gap: 24px;">

    <!-- Division Banner & Station Master Admin Card (Requirement 5 & 6) -->
    <div class="card" style="border-left: 6px solid var(--navy); margin-bottom: 0;">
        <div style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 16px;">
            <div>
                <div style="display: flex; align-items: center; gap: 8px; margin-bottom: 6px;">
                    <span class="badge badge-navy" style="font-size: 11px;">SYSTEM: {{ $activeSystem->code }}</span>
                    <span class="badge badge-green" style="font-size: 11px;">Zone: {{ $activeSystem->zone }}</span>
                    <span class="badge badge-admin" style="font-size: 11px;">Admin: Station Master</span>
                </div>
                <h1 style="font-size: 24px; font-weight: 800; color: var(--navy); margin: 0 0 6px;">
                    {{ $activeSystem->name }}
                </h1>
                <p style="font-size: 13.5px; color: #475569; margin: 0;">
                    Headquarters: <strong>{{ $activeSystem->headquarters }}</strong> • Assigned Admin: <strong>{{ $activeSystem->station_master_name }}</strong> ({{ $activeSystem->station_master_email }})
                </p>
            </div>

            <div style="display: flex; gap: 10px; align-items: center;">
                <a href="{{ route('railway.bookings', ['system' => $activeSystem->code]) }}" class="btn btn-outline">
                    <span>📋</span> View Division Bookings
                </a>
                <a href="{{ route('railway.systems') }}" class="btn btn-primary">
                    <span>🔁</span> Switch Railway System
                </a>
            </div>
        </div>
    </div>

    <!-- Booking Form & Fare Matrix Grid -->
    <div style="display: grid; grid-template-columns: 2fr 1fr; gap: 24px;">
        
        <!-- Left: Ticket Reservation Form -->
        <div class="card" style="margin-bottom: 0;">
            <div class="card-header">
                <div class="card-title">
                    <span>🎫</span> Passenger Reservation Form (Practical 3 & 12 In Laravel)
                </div>
                <span class="badge badge-saffron">Instant PNR & E-Ticket</span>
            </div>

            <form action="{{ route('railway.book') }}" method="POST" id="bookingForm">
                @csrf

                <!-- System Selection -->
                <input type="hidden" name="railway_system_id" value="{{ $activeSystem->id }}">
                <input type="hidden" name="source_station" id="src_station" value="{{ $trains[0]['src'] }}">
                <input type="hidden" name="destination_station" id="dst_station" value="{{ $trains[0]['dst'] }}">

                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 14px;">
                    <!-- Passenger Name -->
                    <div class="form-group" style="grid-column: span 2;">
                        <label class="form-label" for="passenger_name">Full Passenger Name: *</label>
                        <input type="text" name="passenger_name" id="passenger_name" class="form-control" 
                               value="{{ old('passenger_name', Auth::check() ? Auth::user()->name : '') }}" 
                               placeholder="e.g. Ridham Bambhaniya" required>
                        @error('passenger_name')
                            <span style="color: #DC2626; font-size: 11px;">{{ $message }}</span>
                        @enderror
                    </div>

                    <!-- Age -->
                    <div class="form-group">
                        <label class="form-label" for="passenger_age">Age: *</label>
                        <input type="number" name="passenger_age" id="passenger_age" class="form-control" 
                               value="{{ old('passenger_age', 24) }}" min="1" max="120" required>
                    </div>

                    <!-- Gender -->
                    <div class="form-group">
                        <label class="form-label" for="passenger_gender">Gender: *</label>
                        <select name="passenger_gender" id="passenger_gender" class="form-select" required>
                            <option value="Male" {{ old('passenger_gender') === 'Male' ? 'selected' : '' }}>Male</option>
                            <option value="Female" {{ old('passenger_gender') === 'Female' ? 'selected' : '' }}>Female</option>
                            <option value="Other" {{ old('passenger_gender') === 'Other' ? 'selected' : '' }}>Other</option>
                        </select>
                    </div>

                    <!-- Mobile -->
                    <div class="form-group" style="grid-column: span 2;">
                        <label class="form-label" for="passenger_phone">10-Digit Mobile Number: *</label>
                        <input type="tel" name="passenger_phone" id="passenger_phone" class="form-control" 
                               value="{{ old('passenger_phone', '9825012345') }}" 
                               pattern="[0-9]{10}" placeholder="e.g. 9825012345" required>
                        @error('passenger_phone')
                            <span style="color: #DC2626; font-size: 11px;">{{ $message }}</span>
                        @enderror
                    </div>

                    <!-- Train Route Selection -->
                    <div class="form-group" style="grid-column: span 2;">
                        <label class="form-label" for="train_select">Select Train in {{ $activeSystem->division }}: *</label>
                        <select name="train_info" id="train_select" class="form-select" onchange="updateTrainRoute()" required>
                            @foreach($trains as $index => $t)
                                <option value="{{ $t['no'] }}|{{ $t['name'] }}|{{ $t['src'] }}|{{ $t['dst'] }}|{{ $t['base'] }}" {{ $index === 0 ? 'selected' : '' }}>
                                    {{ $t['no'] }} — {{ $t['name'] }} ({{ $t['src'] }} ➔ {{ $t['dst'] }})
                                </option>
                            @endforeach
                        </select>
                        <input type="hidden" name="train_number" id="train_number" value="{{ $trains[0]['no'] }}">
                        <input type="hidden" name="train_name" id="train_name" value="{{ $trains[0]['name'] }}">
                    </div>

                    <!-- Travel Class -->
                    <div class="form-group">
                        <label class="form-label" for="travel_class">Travel Class: *</label>
                        <select name="travel_class" id="travel_class" class="form-select" onchange="calculateLiveFare()" required>
                            <option value="3A" selected>AC 3 Tier (3A)</option>
                            <option value="2A">AC 2 Tier (2A)</option>
                            <option value="1A">AC First Class (1A)</option>
                            <option value="SL">Sleeper Class (SL)</option>
                            <option value="CC">AC Chair Car (CC)</option>
                            <option value="EC">Executive Chair Car (EC)</option>
                        </select>
                    </div>

                    <!-- Quota -->
                    <div class="form-group">
                        <label class="form-label" for="quota">Quota: *</label>
                        <select name="quota" id="quota" class="form-select" onchange="calculateLiveFare()" required>
                            <option value="GN" selected>General Quota (GN)</option>
                            <option value="TQ">Tatkal Quota (TQ)</option>
                            <option value="LD">Ladies Quota (LD)</option>
                            <option value="SS">Senior Citizen (SS)</option>
                            <option value="PT">Premium Tatkal (PT)</option>
                        </select>
                    </div>

                    <!-- Passengers Count -->
                    <div class="form-group">
                        <label class="form-label" for="passengers_count">Passengers (1-6): *</label>
                        <input type="number" name="passengers_count" id="passengers_count" class="form-control" 
                               value="1" min="1" max="6" oninput="calculateLiveFare()" required>
                    </div>

                    <!-- Journey Date -->
                    <div class="form-group">
                        <label class="form-label" for="journey_date">Date of Journey: *</label>
                        <input type="date" name="journey_date" id="journey_date" class="form-control" 
                               value="{{ date('Y-m-d', strtotime('+3 days')) }}" required>
                    </div>
                </div>

                <div style="margin-top: 18px; padding-top: 18px; border-top: 1px solid var(--border-color); display: flex; justify-content: space-between; align-items: center;">
                    <div style="font-size: 13px; color: #475569;">
                        Booking User: <strong>{{ Auth::user()->name }}</strong> (<code>{{ Auth::user()->username }}</code>)
                    </div>
                    <button type="submit" class="btn btn-saffron" style="padding: 10px 24px; font-size: 14px;">
                        <span>✔</span> Confirm & Generate E-Ticket
                    </button>
                </div>
            </form>
        </div>

        <!-- Right: Live Fare Estimation & Station Master Profile -->
        <div style="display: flex; flex-direction: column; gap: 20px;">
            
            <!-- Live Dynamic Fare Calculator -->
            <div class="card" style="margin-bottom: 0; background: #F8FAFC; border: 1.5px solid var(--saffron);">
                <div class="card-header" style="border-bottom-color: #FED7AA;">
                    <div class="card-title" style="color: var(--saffron-dark);">
                        <span>🧮</span> Dynamic Fare Calculator
                    </div>
                    <span class="badge badge-saffron">Auto Computed</span>
                </div>

                <div style="font-size: 13px; display: flex; flex-direction: column; gap: 10px;">
                    <div style="display: flex; justify-content: space-between;">
                        <span style="color: var(--text-muted);">Base Distance Fare:</span>
                        <strong id="display_base_rate">₹1,200</strong>
                    </div>
                    <div style="display: flex; justify-content: space-between;">
                        <span style="color: var(--text-muted);">Class Multiplier:</span>
                        <strong id="display_multiplier">3A (x1.0)</strong>
                    </div>
                    <div style="display: flex; justify-content: space-between;">
                        <span style="color: var(--text-muted);">Passengers:</span>
                        <strong id="display_pax">1 Passenger</strong>
                    </div>
                    <div style="display: flex; justify-content: space-between;">
                        <span style="color: var(--text-muted);">Quota Surcharge:</span>
                        <strong id="display_surcharge">₹0 (GN)</strong>
                    </div>

                    <div style="margin-top: 10px; padding: 12px; background: #FFFFFF; border: 2px dashed var(--saffron); border-radius: 8px; text-align: center;">
                        <div style="font-size: 11px; text-transform: uppercase; color: var(--text-muted); font-weight: 700;">Total Estimated Fare</div>
                        <div style="font-size: 28px; font-weight: 800; color: var(--navy); margin-top: 2px;" id="display_total_fare">
                            ₹1,200.00
                        </div>
                    </div>
                </div>
            </div>

            <!-- Designated Station Master Admin Card (Requirement 5 & 6) -->
            <div class="card" style="margin-bottom: 0;">
                <div class="card-header">
                    <div class="card-title" style="font-size: 15px;">
                        <span>👨‍💼</span> Station Master (Admin)
                    </div>
                    <span class="badge badge-admin">In-Charge</span>
                </div>

                <div style="display: flex; align-items: center; gap: 12px; margin-bottom: 12px;">
                    <div style="width: 44px; height: 44px; border-radius: 50%; background: var(--navy); color: #FFFFFF; display: flex; align-items: center; justify-content: center; font-size: 20px;">
                        🎖
                    </div>
                    <div>
                        <div style="font-weight: 700; color: var(--navy); font-size: 14px;">{{ $activeSystem->station_master_name }}</div>
                        <div style="font-size: 11.5px; color: var(--text-muted);">Station Superintendent • {{ $activeSystem->code }}</div>
                    </div>
                </div>

                <div style="font-size: 12px; line-height: 1.6; color: #475569;">
                    <div>📞 <strong>Phone:</strong> {{ $activeSystem->station_master_phone }}</div>
                    <div>✉️ <strong>Official Email:</strong> {{ $activeSystem->station_master_email }}</div>
                    <div>🏢 <strong>Division:</strong> {{ $activeSystem->division }} ({{ $activeSystem->zone }})</div>
                </div>
            </div>

            <!-- Division Statistics -->
            <div class="card" style="margin-bottom: 0; background: #EFF6FF; border-color: #BFDBFE;">
                <div style="font-size: 12px; font-weight: 700; color: var(--navy); margin-bottom: 8px; text-transform: uppercase;">
                    Division Live Operations:
                </div>
                <div style="display: flex; justify-content: space-between; font-size: 13px; margin-bottom: 6px;">
                    <span>Active Daily Trains:</span>
                    <strong>{{ $activeSystem->total_trains }} Trains</strong>
                </div>
                <div style="display: flex; justify-content: space-between; font-size: 13px; margin-bottom: 6px;">
                    <span>Division Bookings in DB:</span>
                    <strong style="color: var(--navy);">{{ $systemBookingsCount }} Tickets</strong>
                </div>
                <div style="display: flex; justify-content: space-between; font-size: 13px;">
                    <span>Total Division Revenue:</span>
                    <strong style="color: var(--saffron-dark);">₹{{ number_format($systemRevenue, 2) }}</strong>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Bookings Table in this System -->
    @if($recentBookings->count() > 0)
        <div class="card">
            <div class="card-header">
                <div class="card-title">
                    <span>📑</span> Recently Issued Tickets in {{ $activeSystem->division }}
                </div>
                <a href="{{ route('railway.bookings', ['system' => $activeSystem->code]) }}" class="btn btn-outline btn-sm">
                    View All in System →
                </a>
            </div>

            <div class="table-responsive">
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>PNR</th>
                            <th>Passenger Name</th>
                            <th>Train</th>
                            <th>Class</th>
                            <th>Pax</th>
                            <th>Date</th>
                            <th>Fare</th>
                            <th>Status</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($recentBookings as $b)
                            <tr>
                                <td><strong><a href="{{ route('railway.ticket', $b->id) }}" style="color: var(--navy); text-decoration: none;">{{ $b->pnr }}</a></strong></td>
                                <td>{{ $b->passenger_name }}</td>
                                <td>{{ $b->train_number }} {{ $b->train_name }}</td>
                                <td><span class="badge badge-navy">{{ $b->travel_class }}</span></td>
                                <td>{{ $b->passengers_count }}</td>
                                <td>{{ $b->journey_date->format('d M Y') }}</td>
                                <td><strong style="color: var(--saffron-dark);">₹{{ number_format($b->total_fare, 2) }}</strong></td>
                                <td><span class="badge badge-green">{{ $b->status }}</span></td>
                                <td>
                                    <a href="{{ route('railway.ticket', $b->id) }}" class="btn btn-primary btn-sm" style="font-size: 11px;">View Slip</a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    @endif

</div>
@endsection

@section('scripts')
<script>
    const classRates = {
        '1A': 3000,
        '2A': 1800,
        '3A': 1200,
        'SL': 500,
        'CC': 850,
        'EC': 1750
    };

    function updateTrainRoute() {
        const select = document.getElementById('train_select');
        const parts = select.value.split('|');
        if (parts.length >= 5) {
            document.getElementById('train_number').value = parts[0];
            document.getElementById('train_name').value = parts[1];
            document.getElementById('src_station').value = parts[2];
            document.getElementById('dst_station').value = parts[3];
        }
        calculateLiveFare();
    }

    function calculateLiveFare() {
        const travelClass = document.getElementById('travel_class').value;
        const quota = document.getElementById('quota').value;
        const paxCount = parseInt(document.getElementById('passengers_count').value) || 1;

        const baseRate = classRates[travelClass] || 1000;
        let surcharge = (quota === 'TQ' || quota === 'PT') ? (250 * paxCount) : 0;
        let total = (baseRate * paxCount) + surcharge;

        document.getElementById('display_base_rate').innerText = '₹' + baseRate.toLocaleString();
        document.getElementById('display_multiplier').innerText = travelClass + ' Standard';
        document.getElementById('display_pax').innerText = paxCount + (paxCount > 1 ? ' Passengers' : ' Passenger');
        document.getElementById('display_surcharge').innerText = surcharge > 0 ? '+₹' + surcharge.toLocaleString() + ' (' + quota + ')' : '₹0 (' + quota + ')';
        document.getElementById('display_total_fare').innerText = '₹' + total.toLocaleString('en-IN', {minimumFractionDigits: 2});
    }

    // Initialize on load
    document.addEventListener('DOMContentLoaded', function() {
        calculateLiveFare();
    });
</script>
@endsection
