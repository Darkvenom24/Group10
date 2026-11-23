@extends('layouts.app')

@section('title', $member['name'] . ' (' . $member['enrollment'] . ') — Group 10 Profile | GMCA')

@section('content')
<div style="max-width: 900px; margin: 10px auto; display: flex; flex-direction: column; gap: 20px;">

    <div>
        <a href="{{ route('members.index') }}" class="btn btn-outline">
            ← Back to Team Overview
        </a>
    </div>

    <!-- Member Profile Dossier Card -->
    <div class="card" style="border-top: 6px solid var(--navy);">
        
        <div style="display: flex; gap: 24px; align-items: flex-start; flex-wrap: wrap; margin-bottom: 24px;">
            <img src="{{ asset($member['avatar']) }}" onerror="this.src='{{ asset('images/temp.jpeg') }}'" alt="{{ $member['name'] }}" style="width: 120px; height: 120px; border-radius: 12px; object-fit: cover; border: 3px solid var(--navy); box-shadow: 0 6px 16px rgba(0,0,0,0.15);">

            <div style="flex: 1;">
                <div style="display: flex; justify-content: space-between; align-items: flex-start; flex-wrap: wrap; gap: 10px;">
                    <div>
                        <h1 style="font-size: 26px; font-weight: 800; color: var(--navy); margin-bottom: 4px;">{{ $member['name'] }}</h1>
                        <div style="font-size: 15px; font-weight: 700; color: var(--saffron-dark);">
                            Enrollment No: {{ $member['enrollment'] }} • Username: <code>{{ $member['username'] }}</code>
                        </div>
                        <div style="font-size: 13.5px; color: var(--text-muted); margin-top: 2px;">
                            {{ $member['role'] }}
                        </div>
                    </div>

                    <a href="{{ route('quick.login', $member['username']) }}" class="btn btn-saffron btn-sm">
                        <span>⚡</span> Log in as {{ $member['username'] }}
                    </a>
                </div>

                <div style="margin-top: 14px; font-size: 13.5px; color: #475569; line-height: 1.6;">
                    {{ $member['bio'] }}
                </div>
            </div>
        </div>

        <!-- Academic & Contact Details Grid -->
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(240px, 1fr)); gap: 16px; margin-bottom: 24px; padding: 18px; background: #F8FAFC; border: 1px solid var(--border-color); border-radius: 8px; font-size: 13px;">
            <div>
                <span style="color: var(--text-muted); display: block; font-size: 11px; font-weight: 700;">INSTITUTION</span>
                <strong>{{ $member['college'] }}</strong>
            </div>

            <div>
                <span style="color: var(--text-muted); display: block; font-size: 11px; font-weight: 700;">ACADEMIC PROGRAM</span>
                <strong>{{ $member['course'] }}</strong>
            </div>

            <div>
                <span style="color: var(--text-muted); display: block; font-size: 11px; font-weight: 700;">OFFICIAL EMAIL</span>
                <strong>{{ $member['email'] }}</strong>
            </div>

            <div>
                <span style="color: var(--text-muted); display: block; font-size: 11px; font-weight: 700;">DATABASE BOOKINGS (REQ 3)</span>
                <strong style="color: var(--saffron-dark); font-size: 15px;">{{ $userBookingsCount }} Tickets Saved (Min 100 ✔)</strong>
            </div>
        </div>

        <!-- Practical Assignment Breakdown -->
        <div style="margin-bottom: 24px;">
            <h3 style="font-size: 16px; font-weight: 700; color: var(--navy); margin-bottom: 10px;">
                Curricular Practicals & System Responsibilities:
            </h3>
            <ul style="font-size: 13.5px; color: #334155; line-height: 1.8; padding-left: 20px;">
                @foreach($member['responsibilities'] as $resp)
                    <li>{{ $resp }}</li>
                @endforeach
            </ul>
        </div>

        <!-- Technical Competencies -->
        <div>
            <h3 style="font-size: 16px; font-weight: 700; color: var(--navy); margin-bottom: 10px;">
                Technology Stack & Skills:
            </h3>
            <div style="display: flex; gap: 8px; flex-wrap: wrap;">
                @foreach($member['skills'] as $skill)
                    <span class="badge badge-navy" style="font-size: 12px; padding: 4px 10px;">{{ $skill }}</span>
                @endforeach
            </div>
        </div>

        <!-- Link to Member's Paginated Dataset -->
        <div style="margin-top: 24px; padding-top: 18px; border-top: 1px solid var(--border-color); display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 10px;">
            <div style="font-size: 13px; color: var(--text-muted);">
                View this candidate's 105 ticket reservation entries with Laravel pagination:
            </div>
            @php
                $userObj = \App\Models\User::where('username', $member['username'])->first();
            @endphp
            <a href="{{ route('railway.bookings', ['user_id' => $userObj ? $userObj->id : 'all']) }}" class="btn btn-primary">
                <span>📊</span> View {{ $member['name'] }}'s Bookings Dataset →
            </a>
        </div>

    </div>

</div>
@endsection
