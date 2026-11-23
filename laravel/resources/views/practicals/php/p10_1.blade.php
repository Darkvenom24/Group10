@extends('layouts.app')

@section('title', 'Practical 10.1 — PHP Array Operations | GMCA Laravel')

@section('content')
<div style="max-width: 800px; margin: 10px auto; display: flex; flex-direction: column; gap: 20px;">
    
    <div>
        <a href="{{ route('php.index') }}" class="btn btn-outline">← Back to PHP Practicals</a>
    </div>

    <div class="card">
        <div class="card-header">
            <div class="card-title">
                <span>📊</span> Practical 10.1: Array Operations (Print, Reverse, Merge, Sum)
            </div>
            <span class="badge badge-saffron">PHP Arrays</span>
        </div>

        <form method="POST" action="{{ route('php.10_1') }}" style="margin-bottom: 20px;">
            @csrf
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 14px; margin-bottom: 14px;">
                <div class="form-group">
                    <label class="form-label">Array 1 (Comma-Separated):</label>
                    <input type="text" name="array1" class="form-control" value="{{ $arr1Str }}" required>
                </div>
                <div class="form-group">
                    <label class="form-label">Array 2 (Comma-Separated):</label>
                    <input type="text" name="array2" class="form-control" value="{{ $arr2Str }}" required>
                </div>
            </div>
            <button type="submit" class="btn btn-primary" style="width: 100%;">Execute Array Operations</button>
        </form>

        <div style="display: flex; flex-direction: column; gap: 14px;">
            <!-- 1. Print values -->
            <div style="background: #F8FAFC; border: 1px solid var(--border-color); border-radius: 8px; padding: 12px 16px;">
                <div style="font-weight: 700; color: var(--navy); font-size: 13px; margin-bottom: 6px;">10.1.1 Print Array 1 Elements:</div>
                <div style="font-family: 'Fira Code', monospace; font-size: 13px; color: #334155;">
                    @foreach($arr1 as $idx => $val)
                        <span class="badge badge-navy" style="margin-right: 4px;">[{{ $idx }}] =&gt; {{ $val }}</span>
                    @endforeach
                </div>
            </div>

            <!-- 2. Reverse -->
            <div style="background: #F8FAFC; border: 1px solid var(--border-color); border-radius: 8px; padding: 12px 16px;">
                <div style="font-weight: 700; color: var(--navy); font-size: 13px; margin-bottom: 6px;">10.1.2 Reversed Array 1 (<code>array_reverse()</code>):</div>
                <div style="font-family: 'Fira Code', monospace; font-size: 13px;">
                    @foreach($reversed as $idx => $val)
                        <span class="badge badge-saffron" style="margin-right: 4px;">[{{ $idx }}] =&gt; {{ $val }}</span>
                    @endforeach
                </div>
            </div>

            <!-- 3. Merge & Sort -->
            <div style="background: #F8FAFC; border: 1px solid var(--border-color); border-radius: 8px; padding: 12px 16px;">
                <div style="font-weight: 700; color: var(--navy); font-size: 13px; margin-bottom: 6px;">10.1.3 Merged & Sorted Arrays (<code>array_merge() + sort()</code>):</div>
                <div style="font-family: 'Fira Code', monospace; font-size: 13px;">
                    @foreach($merged as $idx => $val)
                        <span class="badge badge-green" style="margin-right: 4px;">{{ $val }}</span>
                    @endforeach
                </div>
            </div>

            <!-- 4. Sum -->
            <div style="background: #EFF6FF; border: 1px solid #BFDBFE; border-radius: 8px; padding: 14px 16px; display: flex; justify-content: space-between; align-items: center;">
                <span style="font-weight: 700; color: var(--navy);">10.1.4 Array 1 Sum (<code>array_sum()</code>):</span>
                <span style="font-size: 22px; font-weight: 800; color: var(--navy);">{{ $sum }}</span>
            </div>
        </div>
    </div>

</div>
@endsection
