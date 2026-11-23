@extends('layouts.app')

@section('title', 'Sign In — Indian Railways Reservation Portal | GMCA Laravel')

@section('content')
<div style="max-width: 900px; margin: 20px auto;">
    
    <div style="text-align: center; margin-bottom: 24px;">
        <h1 style="font-size: 26px; font-weight: 800; color: var(--navy); margin-bottom: 6px;">
            Indian Railways Portal Authentication Gateway
        </h1>
        <p style="color: var(--text-muted); font-size: 14px;">
            Government MCA College, Maninagar (GMCA) • Group 10 Laravel Practical
        </p>
    </div>

    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 24px;">
        <!-- Left: Regular Sign In Form -->
        <div class="card" style="margin-bottom: 0;">
            <div class="card-header">
                <div class="card-title">
                    <span>🔐</span> Sign In to Account
                </div>
            </div>

            <form action="{{ route('login.post') }}" method="POST">
                @csrf

                <div class="form-group">
                    <label class="form-label" for="username">Username or Enrollment:</label>
                    <input type="text" name="username" id="username" class="form-control" placeholder="e.g. ridham61 or station_master" value="{{ old('username') }}" required autofocus>
                    @error('username')
                        <span style="color: #DC2626; font-size: 11.5px; margin-top: 4px; display: block;">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group">
                    <label class="form-label" for="password">Password:</label>
                    <input type="password" name="password" id="password" class="form-control" placeholder="Default: password123" required>
                    <span style="font-size: 11px; color: var(--text-muted); margin-top: 4px; display: block;">Default password for all test accounts is <code>password123</code></span>
                </div>

                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px; font-size: 13px;">
                    <label style="display: flex; align-items: center; gap: 6px; cursor: pointer;">
                        <input type="checkbox" name="remember" value="1"> Remember this device
                    </label>
                </div>

                <button type="submit" class="btn btn-primary" style="width: 100%; padding: 10px; font-size: 14px;">
                    <span>➜</span> Sign In
                </button>
            </form>
        </div>

        <!-- Right: 1-Click Fast Evaluator Login (Requirements 2 & 5) -->
        <div class="card" style="margin-bottom: 0; background: #F8FAFC;">
            <div class="card-header">
                <div class="card-title">
                    <span>⚡</span> 1-Click Evaluator Quick Switch
                </div>
                <span class="badge badge-navy">Total 5 Users (Req 2)</span>
            </div>

            <p style="font-size: 12.5px; color: var(--text-muted); margin-bottom: 14px;">
                Click any profile below to instantly sign in without entering credentials:
            </p>

            <div style="display: flex; flex-direction: column; gap: 8px;">
                @foreach($users->take(5) as $u)
                    <a href="{{ route('quick.login', $u->username) }}" 
                       style="display: flex; align-items: center; justify-content: space-between; padding: 9px 12px; background: #FFFFFF; border: 1.5px solid {{ $u->isStationMaster() ? '#F59E0B' : 'var(--border-color)' }}; border-radius: 8px; text-decoration: none; color: inherit; transition: all 0.15s ease;"
                       onmouseover="this.style.borderColor='var(--navy)'" onmouseout="this.style.borderColor='{{ $u->isStationMaster() ? '#F59E0B' : 'var(--border-color)' }}'">
                        
                        <div style="display: flex; align-items: center; gap: 10px;">
                            <img src="{{ asset($u->avatar) }}" onerror="this.src='{{ asset('images/temp.jpeg') }}'" alt="" style="width: 32px; height: 32px; border-radius: 50%; object-fit: cover;">
                            <div>
                                <div style="font-size: 13px; font-weight: 700; color: var(--navy);">{{ $u->name }}</div>
                                <div style="font-size: 11px; color: var(--text-muted);">
                                    <code>{{ $u->username }}</code> • {{ $u->enrollment }}
                                </div>
                            </div>
                        </div>

                        <div>
                            @if($u->isStationMaster())
                                <span class="badge badge-admin" style="font-size: 10px;">Station Master (Admin)</span>
                            @else
                                <span class="badge badge-navy" style="font-size: 10px;">Member ({{ $u->railwayBookings()->count() }} tickets)</span>
                            @endif
                        </div>
                    </a>
                @endforeach
            </div>

            <!-- Multi-System Division Admins -->
            <div style="margin-top: 14px; padding-top: 12px; border-top: 1px solid var(--border-color);">
                <div style="font-size: 11px; font-weight: 700; color: var(--text-muted); text-transform: uppercase; margin-bottom: 6px;">
                    Other Division Station Masters (Req 6):
                </div>
                <div style="display: flex; gap: 6px; flex-wrap: wrap;">
                    <a href="{{ route('quick.login', 'station_master_ndls') }}" class="btn btn-outline btn-sm" style="font-size: 11px;">SM New Delhi</a>
                    <a href="{{ route('quick.login', 'station_master_csmt') }}" class="btn btn-outline btn-sm" style="font-size: 11px;">SM Mumbai</a>
                    <a href="{{ route('quick.login', 'station_master_mas') }}" class="btn btn-outline btn-sm" style="font-size: 11px;">SM Chennai</a>
                    <a href="{{ route('quick.login', 'station_master_hwh') }}" class="btn btn-outline btn-sm" style="font-size: 11px;">SM Howrah</a>
                </div>
            </div>
        </div>
    </div>

</div>
@endsection
