@extends('layouts.app')

@section('title', 'Group 10 Team Members — Government MCA College, Maninagar | GMCA')

@section('content')
<div style="display: flex; flex-direction: column; gap: 24px;">

    <!-- Top Header Banner -->
    <div style="background: linear-gradient(135deg, var(--navy) 0%, var(--navy-dark) 100%); color: #FFFFFF; border-radius: 12px; padding: 24px 28px; border-left: 6px solid var(--saffron);">
        <span class="badge badge-saffron" style="margin-bottom: 8px;">Master of Computer Applications (MCA) — Academic Year 2025–2026</span>
        <h1 style="font-size: 26px; font-weight: 800; color: #FFFFFF; margin: 4px 0 8px;">
            Group 10 Academic Development Team
        </h1>
        <p style="color: #CBD5E1; font-size: 14px; margin: 0;">
            Government MCA College, Maninagar (GMCA) • Course: Web Technology Practicals (WTP - MC01094051)
        </p>
    </div>

    <!-- Team Cards Grid -->
    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(320px, 1fr)); gap: 24px;">
        @foreach($members as $m)
            @php
                $user = \App\Models\User::where('username', $m['username'])->first();
                $bookingCount = $user ? $user->railwayBookings()->count() : 0;
            @endphp
            <div class="card" style="margin-bottom: 0; display: flex; flex-direction: column; justify-content: space-between;">
                <div>
                    <!-- Member Header -->
                    <div style="display: flex; align-items: center; gap: 16px; margin-bottom: 16px;">
                        <img src="{{ asset($m['avatar']) }}" onerror="this.src='{{ asset('images/temp.jpeg') }}'" alt="{{ $m['name'] }}" style="width: 70px; height: 70px; border-radius: 50%; object-fit: cover; border: 3px solid var(--navy); box-shadow: 0 4px 8px rgba(0,0,0,0.1);">
                        <div>
                            <h2 style="font-size: 18px; font-weight: 800; color: var(--navy); margin-bottom: 2px;">{{ $m['name'] }}</h2>
                            <div style="font-size: 13px; color: var(--saffron-dark); font-weight: 700;">{{ $m['enrollment'] }}</div>
                            <div style="font-size: 12px; color: var(--text-muted);">{{ $m['role'] }}</div>
                        </div>
                    </div>

                    <p style="font-size: 13px; color: #475569; margin-bottom: 16px; line-height: 1.5;">
                        {{ $m['bio'] }}
                    </p>

                    <!-- Academic Practical Responsibilities -->
                    <div style="background: #F8FAFC; border: 1px solid var(--border-color); border-radius: 8px; padding: 12px; margin-bottom: 16px;">
                        <div style="font-size: 11px; font-weight: 700; color: var(--text-muted); text-transform: uppercase; margin-bottom: 6px;">
                            Curriculum Practicals Assigned:
                        </div>
                        <ul style="font-size: 12px; color: #334155; padding-left: 18px; line-height: 1.6;">
                            @foreach($m['responsibilities'] as $resp)
                                <li>{{ $resp }}</li>
                            @endforeach
                        </ul>
                    </div>

                    <!-- Skills Tags -->
                    <div style="display: flex; gap: 6px; flex-wrap: wrap; margin-bottom: 16px;">
                        @foreach($m['skills'] as $skill)
                            <span class="badge badge-navy" style="font-size: 11px;">{{ $skill }}</span>
                        @endforeach
                    </div>
                </div>

                <!-- Footer Action -->
                <div style="display: flex; justify-content: space-between; align-items: center; border-top: 1px solid var(--border-color); padding-top: 14px; gap: 8px;">
                    <a href="{{ route('members.show', $m['id']) }}" class="btn btn-primary btn-sm" style="flex: 1;">
                        View Full Profile Dossier →
                    </a>
                    <a href="{{ route('quick.login', $m['username']) }}" class="btn btn-outline btn-sm" title="Sign in as this user">
                        Switch Account
                    </a>
                </div>
            </div>
        @endforeach
    </div>

</div>
@endsection
