@extends('layouts.app')

@section('title', 'Practical 10.2 — String Name & Size Passed As Argument | GMCA Laravel')

@section('content')
<div style="max-width: 650px; margin: 10px auto; display: flex; flex-direction: column; gap: 20px;">
    
    <div>
        <a href="{{ route('php.index') }}" class="btn btn-outline">← Back to PHP Practicals</a>
    </div>

    <div class="card">
        <div class="card-header">
            <div class="card-title">
                <span>🔤</span> Practical 10.2: String Name & Length Passed As Argument
            </div>
            <span class="badge badge-saffron">PHP Functions</span>
        </div>

        <form method="POST" action="{{ route('php.10_2') }}" style="margin-bottom: 20px;">
            @csrf
            <div class="form-group">
                <label class="form-label">Enter Student / Author Name:</label>
                <input type="text" name="name" class="form-control" value="{{ $name }}" required>
            </div>
            <button type="submit" class="btn btn-primary" style="width: 100%;">Compute String Metrics</button>
        </form>

        <div style="display: grid; grid-template-columns: repeat(3, 1fr); gap: 12px; margin-bottom: 16px; text-align: center;">
            <div style="background: #F8FAFC; border: 1px solid var(--border-color); padding: 14px; border-radius: 8px;">
                <div style="font-size: 11px; color: var(--text-muted); font-weight: 700;">BYTE LENGTH</div>
                <div style="font-size: 24px; font-weight: 800; color: var(--navy); margin-top: 4px;">{{ $length }}</div>
                <div style="font-size: 11px; color: var(--text-muted);">strlen()</div>
            </div>

            <div style="background: #F8FAFC; border: 1px solid var(--border-color); padding: 14px; border-radius: 8px;">
                <div style="font-size: 11px; color: var(--text-muted); font-weight: 700;">MULTIBYTE CHARS</div>
                <div style="font-size: 24px; font-weight: 800; color: var(--saffron-dark); margin-top: 4px;">{{ $mbLength }}</div>
                <div style="font-size: 11px; color: var(--text-muted);">mb_strlen()</div>
            </div>

            <div style="background: #F8FAFC; border: 1px solid var(--border-color); padding: 14px; border-radius: 8px;">
                <div style="font-size: 11px; color: var(--text-muted); font-weight: 700;">WORD COUNT</div>
                <div style="font-size: 24px; font-weight: 800; color: var(--green); margin-top: 4px;">{{ $words }}</div>
                <div style="font-size: 11px; color: var(--text-muted);">str_word_count()</div>
            </div>
        </div>

        <div style="background: #F1F5F9; border-radius: 8px; padding: 12px 16px; font-size: 13px;">
            <strong>Passed Argument Name:</strong> <code style="color: var(--navy); font-size: 14px;">"{{ $name }}"</code>
        </div>
    </div>

</div>
@endsection
