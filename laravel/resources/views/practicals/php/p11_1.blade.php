@extends('layouts.app')

@section('title', 'Practical 11.1 — Maximum & Minimum Numbers | GMCA Laravel')

@section('content')
<div style="max-width: 700px; margin: 10px auto; display: flex; flex-direction: column; gap: 20px;">
    
    <div>
        <a href="{{ route('php.index') }}" class="btn btn-outline">← Back to PHP Practicals</a>
    </div>

    <div class="card">
        <div class="card-header">
            <div class="card-title">
                <span>⚖️</span> Practical 11.1: Max and Min Numbers (If-Else & Ternary)
            </div>
            <span class="badge badge-green">Control Structures</span>
        </div>

        <form method="POST" action="{{ route('php.11_1') }}" style="margin-bottom: 20px;">
            @csrf
            <div style="display: grid; grid-template-columns: repeat(3, 1fr); gap: 14px; margin-bottom: 16px;">
                <div class="form-group">
                    <label class="form-label">Value A:</label>
                    <input type="number" step="any" name="a" class="form-control" value="{{ $a }}" required>
                </div>
                <div class="form-group">
                    <label class="form-label">Value B:</label>
                    <input type="number" step="any" name="b" class="form-control" value="{{ $b }}" required>
                </div>
                <div class="form-group">
                    <label class="form-label">Value C:</label>
                    <input type="number" step="any" name="c" class="form-control" value="{{ $c }}" required>
                </div>
            </div>
            <button type="submit" class="btn btn-primary" style="width: 100%;">Evaluate Max & Min</button>
        </form>

        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 16px;">
            <!-- If-Else Method -->
            <div style="background: #EFF6FF; border: 1px solid #BFDBFE; border-radius: 8px; padding: 16px;">
                <div style="font-size: 12px; font-weight: 700; color: var(--navy); text-transform: uppercase; margin-bottom: 8px;">
                    1. Evaluated via If-Else Statements:
                </div>
                <div style="font-size: 14px; margin-bottom: 4px;">Maximum: <strong style="color: var(--navy); font-size: 18px;">{{ $max }}</strong></div>
                <div style="font-size: 14px;">Minimum: <strong style="color: var(--saffron-dark); font-size: 18px;">{{ $min }}</strong></div>
            </div>

            <!-- Ternary Method -->
            <div style="background: #F0FDF4; border: 1px solid #86EFAC; border-radius: 8px; padding: 16px;">
                <div style="font-size: 12px; font-weight: 700; color: #166534; text-transform: uppercase; margin-bottom: 8px;">
                    2. Evaluated via Ternary Operator (<code>?:</code>):
                </div>
                <div style="font-size: 14px; margin-bottom: 4px;">Ternary Max: <strong style="color: #15803D; font-size: 18px;">{{ $ternaryMax }}</strong></div>
                <div style="font-size: 14px;">Ternary Min: <strong style="color: #B45309; font-size: 18px;">{{ $ternaryMin }}</strong></div>
            </div>
        </div>
    </div>

</div>
@endsection
