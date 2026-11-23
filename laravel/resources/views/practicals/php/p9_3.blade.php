@extends('layouts.app')

@section('title', 'Practical 9.3 — 3 Types of Pyramid Patterns | GMCA Laravel')

@section('content')
<div style="max-width: 800px; margin: 10px auto; display: flex; flex-direction: column; gap: 20px;">
    
    <div>
        <a href="{{ route('php.index') }}" class="btn btn-outline">← Back to PHP Practicals</a>
    </div>

    <div class="card">
        <div class="card-header">
            <div class="card-title">
                <span>🔺</span> Practical 9.3: 3 Types of Pyramid Patterns
            </div>
            <span class="badge badge-navy">Nested Loops</span>
        </div>

        <form method="POST" action="{{ route('php.9_3') }}" style="display: flex; gap: 12px; align-items: flex-end; margin-bottom: 24px;">
            @csrf
            <div style="flex: 1;">
                <label class="form-label">Number of Rows (2 to 15):</label>
                <input type="number" name="rows" class="form-control" value="{{ $rows }}" min="2" max="15" required>
            </div>
            <button type="submit" class="btn btn-primary">Generate Pyramids</button>
        </form>

        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(220px, 1fr)); gap: 16px;">
            <!-- Pattern 1 -->
            <div style="background: #0F172A; border-radius: 8px; padding: 16px; color: #38BDF8;">
                <div style="font-size: 12px; font-weight: 700; color: #94A3B8; margin-bottom: 10px; text-transform: uppercase;">
                    1. Right-Angled Triangle
                </div>
                <pre style="font-family: 'Fira Code', monospace; font-size: 13px; line-height: 1.4; margin: 0;">@foreach($pattern1 as $line){{ $line }}
@endforeach</pre>
            </div>

            <!-- Pattern 2 -->
            <div style="background: #0F172A; border-radius: 8px; padding: 16px; color: #F59E0B; text-align: center;">
                <div style="font-size: 12px; font-weight: 700; color: #94A3B8; margin-bottom: 10px; text-transform: uppercase;">
                    2. Equilateral Centered
                </div>
                <pre style="font-family: 'Fira Code', monospace; font-size: 13px; line-height: 1.4; margin: 0; display: inline-block; text-align: left;">@foreach($pattern2 as $line){{ $line }}
@endforeach</pre>
            </div>

            <!-- Pattern 3 -->
            <div style="background: #0F172A; border-radius: 8px; padding: 16px; color: #34D399; text-align: center;">
                <div style="font-size: 12px; font-weight: 700; color: #94A3B8; margin-bottom: 10px; text-transform: uppercase;">
                    3. Inverted Pyramid
                </div>
                <pre style="font-family: 'Fira Code', monospace; font-size: 13px; line-height: 1.4; margin: 0; display: inline-block; text-align: left;">@foreach($pattern3 as $line){{ $line }}
@endforeach</pre>
            </div>
        </div>
    </div>

</div>
@endsection
