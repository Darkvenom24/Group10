@extends('layouts.app')

@section('title', 'Edit Reservation PNR ' . $booking->pnr . ' | Indian Railways')

@section('content')
<div style="max-width: 700px; margin: 10px auto;">
    
    <div style="margin-bottom: 16px;">
        <a href="{{ route('railway.bookings') }}" class="btn btn-outline">
            ← Back to Bookings List
        </a>
    </div>

    <div class="card">
        <div class="card-header">
            <div class="card-title">
                <span>✏️</span> Edit Reservation Details (PNR: {{ $booking->pnr }})
            </div>
            <span class="badge badge-navy">{{ $booking->railwaySystem->division }}</span>
        </div>

        <form action="{{ route('railway.ticket.update', $booking->id) }}" method="POST">
            @csrf
            @method('PUT')

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 14px;">
                <!-- Passenger Name -->
                <div class="form-group" style="grid-column: span 2;">
                    <label class="form-label" for="passenger_name">Passenger Name:</label>
                    <input type="text" name="passenger_name" id="passenger_name" class="form-control" value="{{ old('passenger_name', $booking->passenger_name) }}" required>
                </div>

                <!-- Age -->
                <div class="form-group">
                    <label class="form-label" for="passenger_age">Age:</label>
                    <input type="number" name="passenger_age" id="passenger_age" class="form-control" value="{{ old('passenger_age', $booking->passenger_age) }}" min="1" max="120" required>
                </div>

                <!-- Gender -->
                <div class="form-group">
                    <label class="form-label" for="passenger_gender">Gender:</label>
                    <select name="passenger_gender" id="passenger_gender" class="form-select">
                        <option value="Male" {{ $booking->passenger_gender === 'Male' ? 'selected' : '' }}>Male</option>
                        <option value="Female" {{ $booking->passenger_gender === 'Female' ? 'selected' : '' }}>Female</option>
                        <option value="Other" {{ $booking->passenger_gender === 'Other' ? 'selected' : '' }}>Other</option>
                    </select>
                </div>

                <!-- Phone -->
                <div class="form-group" style="grid-column: span 2;">
                    <label class="form-label" for="passenger_phone">Mobile Number:</label>
                    <input type="tel" name="passenger_phone" id="passenger_phone" class="form-control" value="{{ old('passenger_phone', $booking->passenger_phone) }}" pattern="[0-9]{10}" required>
                </div>

                <!-- Class -->
                <div class="form-group">
                    <label class="form-label" for="travel_class">Travel Class:</label>
                    <select name="travel_class" id="travel_class" class="form-select">
                        <option value="1A" {{ $booking->travel_class === '1A' ? 'selected' : '' }}>1A (First AC)</option>
                        <option value="2A" {{ $booking->travel_class === '2A' ? 'selected' : '' }}>2A (Second AC)</option>
                        <option value="3A" {{ $booking->travel_class === '3A' ? 'selected' : '' }}>3A (Third AC)</option>
                        <option value="SL" {{ $booking->travel_class === 'SL' ? 'selected' : '' }}>SL (Sleeper)</option>
                        <option value="CC" {{ $booking->travel_class === 'CC' ? 'selected' : '' }}>CC (Chair Car)</option>
                        <option value="EC" {{ $booking->travel_class === 'EC' ? 'selected' : '' }}>EC (Executive CC)</option>
                    </select>
                </div>

                <!-- Quota -->
                <div class="form-group">
                    <label class="form-label" for="quota">Quota:</label>
                    <select name="quota" id="quota" class="form-select">
                        <option value="GN" {{ $booking->quota === 'GN' ? 'selected' : '' }}>GN (General)</option>
                        <option value="TQ" {{ $booking->quota === 'TQ' ? 'selected' : '' }}>TQ (Tatkal)</option>
                        <option value="LD" {{ $booking->quota === 'LD' ? 'selected' : '' }}>LD (Ladies)</option>
                        <option value="SS" {{ $booking->quota === 'SS' ? 'selected' : '' }}>SS (Senior Citizen)</option>
                        <option value="PT" {{ $booking->quota === 'PT' ? 'selected' : '' }}>PT (Premium Tatkal)</option>
                    </select>
                </div>

                <!-- Passengers Count -->
                <div class="form-group">
                    <label class="form-label" for="passengers_count">Passengers:</label>
                    <input type="number" name="passengers_count" id="passengers_count" class="form-control" value="{{ old('passengers_count', $booking->passengers_count) }}" min="1" max="6" required>
                </div>

                <!-- Journey Date -->
                <div class="form-group">
                    <label class="form-label" for="journey_date">Journey Date:</label>
                    <input type="date" name="journey_date" id="journey_date" class="form-control" value="{{ old('journey_date', $booking->journey_date->format('Y-m-d')) }}" required>
                </div>

                <!-- Status -->
                <div class="form-group" style="grid-column: span 2;">
                    <label class="form-label" for="status">Booking Status:</label>
                    <select name="status" id="status" class="form-select">
                        <option value="Confirmed" {{ $booking->status === 'Confirmed' ? 'selected' : '' }}>Confirmed</option>
                        <option value="RAC" {{ $booking->status === 'RAC' ? 'selected' : '' }}>RAC</option>
                        <option value="Waiting List" {{ $booking->status === 'Waiting List' ? 'selected' : '' }}>Waiting List</option>
                    </select>
                </div>
            </div>

            <div style="margin-top: 18px; padding-top: 16px; border-top: 1px solid var(--border-color); display: flex; justify-content: flex-end; gap: 10px;">
                <a href="{{ route('railway.bookings') }}" class="btn btn-outline">Cancel</a>
                <button type="submit" class="btn btn-primary">
                    <span>💾</span> Save Changes
                </button>
            </div>
        </form>
    </div>

</div>
@endsection
