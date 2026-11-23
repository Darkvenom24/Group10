@extends('layouts.app')

@section('title', 'Practical 11.4 — 5 PHP String Functions | GMCA Laravel')

@section('content')
<div style="max-width: 800px; margin: 10px auto; display: flex; flex-direction: column; gap: 20px;">
    
    <div>
        <a href="{{ route('php.index') }}" class="btn btn-outline">← Back to PHP Practicals</a>
    </div>

    <div class="card">
        <div class="card-header">
            <div class="card-title">
                <span>🔤</span> Practical 11.4: 5 PHP String Functions
            </div>
            <span class="badge badge-green">String Functions</span>
        </div>

        <form method="POST" action="{{ route('php.11_4') }}" style="margin-bottom: 20px;">
            @csrf
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 14px; margin-bottom: 14px;">
                <div class="form-group">
                    <label class="form-label">String 1 (Target):</label>
                    <input type="text" name="str1" class="form-control" value="{{ $str1 }}" required>
                </div>
                <div class="form-group">
                    <label class="form-label">String 2 (Append / Concat):</label>
                    <input type="text" name="str2" class="form-control" value="{{ $str2 }}" required>
                </div>
            </div>
            <div class="form-group">
                <label class="form-label">Substring to search within String 1:</label>
                <input type="text" name="search" class="form-control" value="{{ $search }}" required>
            </div>
            <button type="submit" class="btn btn-primary" style="width: 100%;">Execute 5 String Functions</button>
        </form>

        <div style="display: flex; flex-direction: column; gap: 12px; font-size: 13px;">
            <!-- 1. Print Name -->
            <div style="background: #F8FAFC; border: 1px solid var(--border-color); border-radius: 6px; padding: 10px 14px;">
                <strong>11.4.1 Print String Name:</strong> <code>{{ $str1 }}</code>
            </div>

            <!-- 2. String Size -->
            <div style="background: #F8FAFC; border: 1px solid var(--border-color); border-radius: 6px; padding: 10px 14px; display: flex; justify-content: space-between;">
                <strong>11.4.2 String Length (<code>strlen()</code>):</strong>
                <span class="badge badge-navy">{{ $len }} characters</span>
            </div>

            <!-- 3. Concatenate -->
            <div style="background: #F8FAFC; border: 1px solid var(--border-color); border-radius: 6px; padding: 10px 14px;">
                <strong>11.4.3 Concatenated Strings (<code>$str1 . ' — ' . $str2</code>):</strong>
                <div style="color: var(--navy); font-weight: 700; margin-top: 2px;">{{ $concat }}</div>
            </div>

            <!-- 4. Case Conversions -->
            <div style="background: #F8FAFC; border: 1px solid var(--border-color); border-radius: 6px; padding: 10px 14px;">
                <strong>11.4.4 Case Conversions:</strong>
                <ul style="margin: 4px 0 0 20px; line-height: 1.6;">
                    <li><code>strtoupper()</code>: <strong>{{ $upper }}</strong></li>
                    <li><code>strtolower()</code>: <strong>{{ $lower }}</strong></li>
                    <li><code>ucwords()</code>: <strong>{{ $ucwords }}</strong></li>
                </ul>
            </div>

            <!-- 5. Substring Search -->
            <div style="background: #F8FAFC; border: 1px solid var(--border-color); border-radius: 6px; padding: 10px 14px; display: flex; justify-content: space-between;">
                <strong>11.4.5 Substring Position (<code>strpos('{{ $search }}')</code>):</strong>
                <span class="badge {{ $pos !== false ? 'badge-green' : 'badge-saffron' }}">
                    {{ $pos !== false ? "Found at Zero-Indexed Character #$pos" : 'Not Found (-1)' }}
                </span>
            </div>
        </div>
    </div>

</div>
@endsection
