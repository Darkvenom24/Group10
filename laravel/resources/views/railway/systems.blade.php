@extends('layouts.app')

@section('title', '5 Railway Division Systems & Station Masters (Req 6) | GMCA Laravel')

@section('content')
<div style="display: flex; flex-direction: column; gap: 24px;">

    <!-- Top Requirement 6 Showcase Banner -->
    <div style="background: linear-gradient(135deg, #000080, #00004d); color: #FFFFFF; border-radius: 12px; padding: 24px 28px; border-left: 6px solid var(--saffron);">
        <div style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 16px;">
            <div>
                <span class="badge badge-saffron" style="font-size: 11px; margin-bottom: 8px;">
                    Requirement 6: Multiple Systems with Dedicated Admins (Station Masters)
                </span>
                <h1 style="font-size: 26px; font-weight: 800; color: #FFFFFF; margin: 4px 0 8px;">
                    5 Distinct Indian Railways Division Systems
                </h1>
                <p style="color: #CBD5E1; font-size: 14px; margin: 0; max-width: 800px;">
                    Just like selecting different libraries in a Library Management System gives data of that specific library with its assigned Librarian, 
                    selecting any of our 5 Railway Systems displays the train routes, station data, and bookings managed by that specific Division's 
                    <strong>Station Master (Admin)</strong>.
                </p>
            </div>

            <div>
                <span class="badge badge-green" style="font-size: 13px; padding: 8px 14px;">
                    ✔ 5 Systems + 5 Station Masters
                </span>
            </div>
        </div>
    </div>

    <!-- Active Division Status Indicator -->
    <div style="background: #F0FDF4; border: 1.5px solid #86EFAC; border-radius: 8px; padding: 12px 18px; display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 10px;">
        <div style="display: flex; align-items: center; gap: 10px; font-size: 13.5px; color: #166534;">
            <span style="font-size: 20px;">🟢</span>
            <div>
                Current Active Division: <strong>{{ $activeSystem->name }}</strong> (Admin: <strong>{{ $activeSystem->station_master_name }}</strong>)
            </div>
        </div>
        <a href="{{ route('railway.index') }}" class="btn btn-saffron btn-sm">
            Book in Active System →
        </a>
    </div>

    <!-- 5 Railway Systems Cards Grid -->
    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(360px, 1fr)); gap: 20px;">
        @foreach($systems as $sys)
            <div class="card" style="margin-bottom: 0; border-top: 4px solid {{ $sys->badge_color ?? 'var(--navy)' }}; display: flex; flex-direction: column; justify-content: space-between; position: relative;">
                
                @if($sys->code === $activeSystem->code)
                    <span class="badge badge-green" style="position: absolute; top: 14px; right: 14px; font-size: 11px;">
                        ● ACTIVE SYSTEM
                    </span>
                @endif

                <div>
                    <!-- Header -->
                    <div style="display: flex; align-items: center; gap: 12px; margin-bottom: 12px;">
                        <div style="width: 44px; height: 44px; border-radius: 8px; background: {{ $sys->badge_color ?? 'var(--navy)' }}15; color: {{ $sys->badge_color ?? 'var(--navy)' }}; display: flex; align-items: center; justify-content: center; font-size: 22px; font-weight: 800;">
                            🚆
                        </div>
                        <div>
                            <div style="font-size: 16px; font-weight: 800; color: var(--navy);">{{ $sys->division }}</div>
                            <div style="font-size: 12px; color: var(--text-muted);">{{ $sys->zone }} • Code: <strong>{{ $sys->code }}</strong></div>
                        </div>
                    </div>

                    <p style="font-size: 13px; color: #475569; margin-bottom: 16px; line-height: 1.5;">
                        {{ $sys->description }}
                    </p>

                    <!-- Assigned Station Master Card (Requirement 5 & 6) -->
                    <div style="background: #F8FAFC; border: 1px solid var(--border-color); border-radius: 8px; padding: 12px 14px; margin-bottom: 16px;">
                        <div style="font-size: 11px; text-transform: uppercase; color: var(--text-muted); font-weight: 700; margin-bottom: 4px;">
                            Designated Station Master (Admin)
                        </div>
                        <div style="font-size: 14px; font-weight: 700; color: var(--navy); display: flex; align-items: center; gap: 6px;">
                            <span>👨‍💼</span> {{ $sys->station_master_name }}
                        </div>
                        <div style="font-size: 11.5px; color: #475569; margin-top: 4px;">
                            ✉️ {{ $sys->station_master_email }}<br>
                            📞 {{ $sys->station_master_phone }}
                        </div>
                    </div>

                    <!-- Division Statistics -->
                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 10px; font-size: 12px; margin-bottom: 16px;">
                        <div style="background: #EFF6FF; padding: 8px 10px; border-radius: 6px;">
                            <span style="color: var(--text-muted); display: block;">HEADQUARTERS</span>
                            <strong>{{ $sys->headquarters }}</strong>
                        </div>
                        <div style="background: #EFF6FF; padding: 8px 10px; border-radius: 6px;">
                            <span style="color: var(--text-muted); display: block;">DATABASE BOOKINGS</span>
                            <strong style="color: var(--navy);">{{ $sys->bookings_count }} Tickets</strong>
                        </div>
                    </div>
                </div>

                <!-- Action Button to Switch to this System -->
                <div style="display: flex; gap: 8px; border-top: 1px solid var(--border-color); padding-top: 14px;">
                    <a href="{{ route('switch.system', $sys->code) }}" class="btn {{ $sys->code === $activeSystem->code ? 'btn-outline' : 'btn-primary' }}" style="flex: 1;">
                        {{ $sys->code === $activeSystem->code ? '● Currently Selected' : 'Switch To This Division System' }}
                    </a>
                    <a href="{{ route('railway.bookings', ['system' => $sys->code]) }}" class="btn btn-outline" title="Filter master bookings by this division">
                        <span>📊</span> Bookings
                    </a>
                </div>

            </div>
        @endforeach
    </div>

</div>
@endsection
