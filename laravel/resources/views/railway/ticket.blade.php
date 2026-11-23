@extends('layouts.app')

@section('title', 'Electronic Reservation Slip (ERS) — PNR ' . $booking->pnr . ' | Indian Railways')

@section('content')
<div style="max-width: 850px; margin: 10px auto;">

    <!-- Top Action Bar -->
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 16px;">
        <a href="{{ route('railway.bookings') }}" class="btn btn-outline">
            ← Back to All Bookings
        </a>

        <div style="display: flex; gap: 8px;">
            <button type="button" onclick="window.print()" class="btn btn-primary">
                <span>🖨️</span> Print E-Ticket Slip
            </button>
            <a href="{{ route('railway.index') }}" class="btn btn-saffron">
                <span>+</span> Book Another Ticket
            </a>
        </div>
    </div>

    <!-- Printable Official ERS Ticket Card -->
    <div class="card" style="border: 2px solid var(--navy); padding: 0; overflow: hidden; background: #FFFFFF; box-shadow: 0 10px 25px rgba(0,0,0,0.1);">
        
        <!-- Ticket Header Banner -->
        <div style="background: var(--navy); color: #FFFFFF; padding: 18px 24px; display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 14px;">
            <div style="display: flex; align-items: center; gap: 14px;">
                <div style="width: 48px; height: 48px; background: #FFFFFF; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 24px; border: 2px solid var(--saffron);">
                    🚆
                </div>
                <div>
                    <div style="font-size: 18px; font-weight: 800; letter-spacing: 0.5px;">INDIAN RAILWAYS PASSENGER RESERVATION</div>
                    <div style="font-size: 12px; color: #CBD5E1;">ELECTRONIC RESERVATION SLIP (ERS) — VALID WITH ORIGINAL ID PROOF</div>
                </div>
            </div>

            <div style="text-align: right; background: rgba(255,255,255,0.12); padding: 8px 14px; border-radius: 6px; border: 1px solid rgba(255,255,255,0.25);">
                <div style="font-size: 11px; text-transform: uppercase; color: var(--saffron); font-weight: 700;">PNR NUMBER</div>
                <div style="font-size: 20px; font-weight: 800; font-family: 'Fira Code', monospace; color: #FFFFFF;">
                    {{ $booking->pnr }}
                </div>
            </div>
        </div>

        <div style="padding: 24px;">
            
            <!-- System & Division Origin Strip (Requirement 6) -->
            <div style="background: #F8FAFC; border: 1px solid var(--border-color); border-radius: 6px; padding: 10px 14px; margin-bottom: 20px; display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 8px; font-size: 12.5px;">
                <div>
                    <span>Issuing Railway Division:</span>
                    <strong style="color: var(--navy);">{{ $booking->railwaySystem->name }}</strong>
                </div>
                <div>
                    <span>Superintending Admin:</span>
                    <strong>{{ $booking->railwaySystem->station_master_name }} (Station Master)</strong>
                </div>
            </div>

            <!-- Train & Route Details Grid -->
            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 16px; margin-bottom: 20px; padding-bottom: 20px; border-bottom: 1px solid var(--border-color); font-size: 13px;">
                <div>
                    <div style="color: var(--text-muted); font-size: 11px; font-weight: 700;">TRAIN NUMBER & NAME</div>
                    <div style="font-size: 15px; font-weight: 800; color: var(--navy);">{{ $booking->train_number }} {{ $booking->train_name }}</div>
                </div>

                <div>
                    <div style="color: var(--text-muted); font-size: 11px; font-weight: 700;">FROM / DEPARTURE</div>
                    <div style="font-weight: 700;">{{ $booking->source_station }}</div>
                </div>

                <div>
                    <div style="color: var(--text-muted); font-size: 11px; font-weight: 700;">TO / ARRIVAL</div>
                    <div style="font-weight: 700;">{{ $booking->destination_station }}</div>
                </div>

                <div>
                    <div style="color: var(--text-muted); font-size: 11px; font-weight: 700;">DATE OF JOURNEY</div>
                    <div style="font-size: 14px; font-weight: 800; color: var(--saffron-dark);">
                        {{ $booking->journey_date->format('l, d F Y') }}
                    </div>
                </div>

                <div>
                    <div style="color: var(--text-muted); font-size: 11px; font-weight: 700;">CLASS & QUOTA</div>
                    <div>
                        <span class="badge badge-navy">{{ $booking->travel_class }}</span>
                        <span class="badge badge-saffron">{{ $booking->quota }}</span>
                    </div>
                </div>

                <div>
                    <div style="color: var(--text-muted); font-size: 11px; font-weight: 700;">BOOKING TIMESTAMP</div>
                    <div style="color: #475569;">{{ $booking->created_at->format('d/m/Y H:i:s') }}</div>
                </div>
            </div>

            <!-- Passenger Details Table -->
            <div style="margin-bottom: 20px;">
                <div style="font-size: 13px; font-weight: 700; color: var(--navy); margin-bottom: 8px; text-transform: uppercase;">
                    Passenger Information & Coach/Seat Allotment
                </div>
                <div class="table-responsive">
                    <table class="data-table" style="border: 1px solid var(--border-color);">
                        <thead>
                            <tr style="background: #F1F5F9; color: var(--navy);">
                                <th style="background: #F1F5F9; color: var(--navy); border: 1px solid var(--border-color);">#</th>
                                <th style="background: #F1F5F9; color: var(--navy); border: 1px solid var(--border-color);">Passenger Name</th>
                                <th style="background: #F1F5F9; color: var(--navy); border: 1px solid var(--border-color);">Age & Gender</th>
                                <th style="background: #F1F5F9; color: var(--navy); border: 1px solid var(--border-color);">Booking Status</th>
                                <th style="background: #F1F5F9; color: var(--navy); border: 1px solid var(--border-color);">Coach & Seat No</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td style="border: 1px solid var(--border-color); font-weight: 700;">1</td>
                                <td style="border: 1px solid var(--border-color);"><strong>{{ $booking->passenger_name }}</strong></td>
                                <td style="border: 1px solid var(--border-color);">{{ $booking->passenger_age }} Years / {{ $booking->passenger_gender }}</td>
                                <td style="border: 1px solid var(--border-color);">
                                    <span class="badge badge-green">{{ $booking->status }}</span>
                                </td>
                                <td style="border: 1px solid var(--border-color); font-family: 'Fira Code', monospace; font-weight: 700; color: var(--navy);">
                                    {{ $booking->coach_seat ?? 'B2-24 (MB)' }}
                                </td>
                            </tr>
                            @if($booking->passengers_count > 1)
                                @for($p = 2; $p <= $booking->passengers_count; $p++)
                                    <tr>
                                        <td style="border: 1px solid var(--border-color); font-weight: 700;">{{ $p }}</td>
                                        <td style="border: 1px solid var(--border-color);">Passenger {{ $p }} (Family Member)</td>
                                        <td style="border: 1px solid var(--border-color);">{{ 20 + $p * 3 }} Years / {{ $p % 2 == 0 ? 'Female' : 'Male' }}</td>
                                        <td style="border: 1px solid var(--border-color);"><span class="badge badge-green">{{ $booking->status }}</span></td>
                                        <td style="border: 1px solid var(--border-color); font-family: 'Fira Code', monospace; font-weight: 700; color: var(--navy);">
                                            {{ substr($booking->coach_seat ?? 'B2-24', 0, 3) }}-{{ 24 + $p }} ({{ $p % 2 == 0 ? 'UB' : 'SL' }})
                                        </td>
                                    </tr>
                                @endfor
                            @endif
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Fare Breakdown & Barcode Seal -->
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px; align-items: center; background: #F8FAFC; border: 1px solid var(--border-color); border-radius: 8px; padding: 16px;">
                <div style="font-size: 12.5px; line-height: 1.8;">
                    <div style="font-size: 13px; font-weight: 700; color: var(--navy); margin-bottom: 4px;">Fare Details Breakdown:</div>
                    <div style="display: flex; justify-content: space-between;">
                        <span>Ticket Base Fare ({{ $booking->passengers_count }} Pax):</span>
                        <span>₹{{ number_format($booking->total_fare * 0.85, 2) }}</span>
                    </div>
                    <div style="display: flex; justify-content: space-between;">
                        <span>IRCTC Convenience & Reservation Fee:</span>
                        <span>₹{{ number_format($booking->total_fare * 0.10, 2) }}</span>
                    </div>
                    <div style="display: flex; justify-content: space-between;">
                        <span>GST / Service Tax (5%):</span>
                        <span>₹{{ number_format($booking->total_fare * 0.05, 2) }}</span>
                    </div>
                    <div style="display: flex; justify-content: space-between; font-size: 15px; font-weight: 800; color: var(--navy); border-top: 1px dashed #CBD5E1; padding-top: 6px; margin-top: 4px;">
                        <span>Total Fare Paid:</span>
                        <span style="color: var(--saffron-dark);">₹{{ number_format($booking->total_fare, 2) }}</span>
                    </div>
                </div>

                <!-- Barcode & Seal Simulation -->
                <div style="text-align: center; border-left: 1px solid var(--border-color); padding-left: 20px;">
                    <div style="font-family: 'Fira Code', monospace; font-size: 28px; letter-spacing: 4px; color: #1E293B; margin-bottom: 4px;">
                        ||||| | |||| ||| |||| | |||||
                    </div>
                    <div style="font-size: 11px; color: var(--text-muted); font-family: 'Fira Code', monospace;">
                        *{{ $booking->pnr }}-AUTH-{{ $booking->id }}*
                    </div>
                    <div style="margin-top: 8px; font-size: 11px; color: var(--green-dark); font-weight: 700;">
                        ✔ DIGITALLY VERIFIED BY INDIAN RAILWAYS GATEWAY
                    </div>
                </div>
            </div>

            <!-- Instructions Notice -->
            <div style="margin-top: 16px; font-size: 11.5px; color: #64748B; line-height: 1.5;">
                • Please carry valid Government photo identity proof (Aadhaar, Voter ID, Passport, Driving License) during journey.<br>
                • This ERS document is generated by Group 10 Laravel Railway Information Portal for academic practical examination.
            </div>

        </div>

    </div>

</div>
@endsection
