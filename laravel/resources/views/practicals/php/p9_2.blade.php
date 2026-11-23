@extends('layouts.app')

@section('title', 'Practical 9.2 — Print 1 to N Numbers | GMCA Laravel')

@section('content')
<div style="max-width: 800px; margin: 10px auto; display: flex; flex-direction: column; gap: 20px;">
    
    <div>
        <a href="{{ route('php.index') }}" class="btn btn-outline">← Back to PHP Practicals</a>
    </div>

    <div class="card">
        <div class="card-header">
            <div class="card-title">
                <span>🔄</span> Practical 9.2: Print 1 to N Numbers with Analysis
            </div>
            <span class="badge badge-navy">PHP For Loop</span>
        </div>

        <form method="POST" action="{{ route('php.9_2') }}" style="display: flex; gap: 12px; align-items: flex-end; margin-bottom: 20px;">
            @csrf
            <div style="flex: 1;">
                <label class="form-label">Enter N (Upper Limit 1 to 100):</label>
                <input type="number" name="limit" class="form-control" value="{{ $limit }}" min="1" max="100" required>
            </div>
            <button type="submit" class="btn btn-primary">Generate Loop</button>
        </form>

        <!-- Stats -->
        <div style="display: grid; grid-template-columns: repeat(3, 1fr); gap: 12px; margin-bottom: 20px; text-align: center;">
            <div style="background: #EFF6FF; padding: 12px; border-radius: 6px;">
                <div style="font-size: 11px; color: var(--text-muted);">CUMULATIVE SUM</div>
                <div style="font-size: 20px; font-weight: 800; color: var(--navy);">{{ $sum }}</div>
            </div>
            <div style="background: #F0FDF4; padding: 12px; border-radius: 6px;">
                <div style="font-size: 11px; color: var(--text-muted);">EVEN NUMBERS</div>
                <div style="font-size: 20px; font-weight: 800; color: var(--green);">{{ $evenCount }}</div>
            </div>
            <div style="background: #FFF7ED; padding: 12px; border-radius: 6px;">
                <div style="font-size: 11px; color: var(--text-muted);">ODD NUMBERS</div>
                <div style="font-size: 20px; font-weight: 800; color: var(--saffron-dark);">{{ $oddCount }}</div>
            </div>
        </div>

        <!-- Numbers Badges Grid -->
        <div style="display: flex; flex-wrap: wrap; gap: 8px; max-height: 350px; overflow-y: auto; padding: 12px; background: #F8FAFC; border: 1px solid var(--border-color); border-radius: 8px;">
            @foreach($numbers as $item)
                <span class="badge {{ $item['isEven'] ? 'badge-green' : 'badge-saffron' }}" style="font-size: 13px; padding: 5px 10px; font-family: 'Fira Code', monospace;">
                    {{ $item['val'] }} ({{ $item['isEven'] ? 'Even' : 'Odd' }})
                </span>
            @endforeach
        </div>
    </div>

</div>
@endsection
