@extends('layouts.app')

@section('title', 'Practical 9.1 — Max of 3 Values | GMCA Laravel')

@section('content')
<div style="max-width: 650px; margin: 10px auto; display: flex; flex-direction: column; gap: 20px;">
    
    <div>
        <a href="{{ route('php.index') }}" class="btn btn-outline">← Back to PHP Practicals</a>
    </div>

    <div class="card">
        <div class="card-header">
            <div class="card-title">
                <span>🔢</span> Practical 9.1: Find Maximum of 3 Numbers
            </div>
            <span class="badge badge-navy">PHP Conditional Logic</span>
        </div>

        <form method="POST" action="{{ route('php.9_1') }}">
            @csrf

            <div style="display: grid; grid-template-columns: repeat(3, 1fr); gap: 14px; margin-bottom: 16px;">
                <div class="form-group">
                    <label class="form-label">Number 1:</label>
                    <input type="number" step="any" name="num1" class="form-control" value="{{ $n1 }}" required>
                </div>
                <div class="form-group">
                    <label class="form-label">Number 2:</label>
                    <input type="number" step="any" name="num2" class="form-control" value="{{ $n2 }}" required>
                </div>
                <div class="form-group">
                    <label class="form-label">Number 3:</label>
                    <input type="number" step="any" name="num3" class="form-control" value="{{ $n3 }}" required>
                </div>
            </div>

            <button type="submit" class="btn btn-primary" style="width: 100%;">
                Compute Maximum Value
            </button>
        </form>

        <div style="margin-top: 20px; padding: 18px; background: #F0FDF4; border: 1.5px solid #86EFAC; border-radius: 8px; text-align: center;">
            <div style="font-size: 12px; color: var(--text-muted); text-transform: uppercase; font-weight: 700;">Maximum Result</div>
            <div style="font-size: 36px; font-weight: 800; color: #15803D; margin: 4px 0;">{{ $maxVal }}</div>
            <div style="font-size: 13px; color: #166534;">{{ $step }}</div>
        </div>
    </div>

</div>
@endsection
