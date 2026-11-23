@extends('layouts.app')

@section('title', 'Practical 11.2 — Date/Time & Contextual Greeting | GMCA Laravel')

@section('content')
<div style="max-width: 650px; margin: 10px auto; display: flex; flex-direction: column; gap: 20px;">
    
    <div>
        <a href="{{ route('php.index') }}" class="btn btn-outline">← Back to PHP Practicals</a>
    </div>

    <div class="card">
        <div class="card-header">
            <div class="card-title">
                <span>⏰</span> Practical 11.2: Date/Time & Contextual Greeting
            </div>
            <span class="badge badge-green">Time Engine</span>
        </div>

        <!-- Greeting Card Preview -->
        <div style="background: {{ $theme }}; color: #FFFFFF; border-radius: 12px; padding: 28px; text-align: center; margin-bottom: 24px; box-shadow: 0 10px 20px rgba(0,0,0,0.15);">
            <div style="font-size: 12px; text-transform: uppercase; letter-spacing: 1px; margin-bottom: 6px; opacity: 0.9;">
                HOUR: {{ sprintf('%02d:00 hrs', $hour) }} • INDIAN STANDARD TIME
            </div>
            <h2 style="font-size: 32px; font-weight: 800; color: #FFFFFF; margin: 0 0 8px;">
                {{ $greeting }}
            </h2>
            <p style="margin: 0; font-size: 13.5px; opacity: 0.95;">
                Welcome to Government MCA College Maninagar Indian Railways Portal!
            </p>
        </div>

        <!-- Simulation Hour Slider -->
        <form method="POST" action="{{ route('php.11_2') }}" id="hourForm">
            @csrf
            <div class="form-group">
                <div style="display: flex; justify-content: space-between; margin-bottom: 6px;">
                    <label class="form-label">Simulate Hour of Day (0 to 23):</label>
                    <strong><span id="hourVal">{{ $hour }}</span>:00 hrs</strong>
                </div>
                <input type="range" name="hour" min="0" max="23" value="{{ $hour }}" class="form-control" 
                       style="padding: 0; cursor: pointer;" 
                       oninput="document.getElementById('hourVal').innerText = this.value" 
                       onchange="document.getElementById('hourForm').submit()">
            </div>
            <div style="display: flex; justify-content: space-between; font-size: 11px; color: var(--text-muted);">
                <span>00:00 (Night)</span>
                <span>06:00 (Morning)</span>
                <span>12:00 (Afternoon)</span>
                <span>18:00 (Evening)</span>
                <span>23:00 (Night)</span>
            </div>
        </form>
    </div>

</div>
@endsection
