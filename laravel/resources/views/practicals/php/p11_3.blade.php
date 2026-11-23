@extends('layouts.app')

@section('title', 'Practical 11.3 — User Profile Web Page & ID Card | GMCA Laravel')

@section('content')
<div style="max-width: 800px; margin: 10px auto; display: flex; flex-direction: column; gap: 20px;">
    
    <div>
        <a href="{{ route('php.index') }}" class="btn btn-outline">← Back to PHP Practicals</a>
    </div>

    <div class="card">
        <div class="card-header">
            <div class="card-title">
                <span>🪪</span> Practical 11.3: User Profile Web Page & Dynamic Identity Card
            </div>
            <span class="badge badge-green">Profile Generator</span>
        </div>

        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 24px;">
            <!-- Form -->
            <form method="POST" action="{{ route('php.11_3') }}">
                @csrf
                <div class="form-group">
                    <label class="form-label">Full Name:</label>
                    <input type="text" name="name" class="form-control" value="{{ $name }}" required>
                </div>
                <div class="form-group">
                    <label class="form-label">Enrollment No:</label>
                    <input type="text" name="enrollment" class="form-control" value="{{ $enrollment }}" required>
                </div>
                <div class="form-group">
                    <label class="form-label">Email Address:</label>
                    <input type="email" name="email" class="form-control" value="{{ $email }}" required>
                </div>
                <div class="form-group">
                    <label class="form-label">Course / Degree:</label>
                    <input type="text" name="course" class="form-control" value="{{ $course }}" required>
                </div>
                <div class="form-group">
                    <label class="form-label">Short Bio:</label>
                    <textarea name="bio" class="form-control" rows="2">{{ $bio }}</textarea>
                </div>
                <button type="submit" class="btn btn-primary" style="width: 100%;">Generate ID Card</button>
            </form>

            <!-- Rendered Official GMCA Photo ID Card -->
            <div style="background: #FFFFFF; border: 2px solid var(--navy); border-radius: 12px; overflow: hidden; box-shadow: 0 10px 20px rgba(0,0,0,0.1); height: fit-content;">
                <div style="background: var(--navy); color: #FFFFFF; padding: 12px; text-align: center;">
                    <div style="font-size: 11px; text-transform: uppercase; color: var(--saffron); font-weight: 700;">Government MCA College, Maninagar</div>
                    <div style="font-size: 14px; font-weight: 800;">STUDENT IDENTITY CARD</div>
                </div>

                <div style="padding: 18px; text-align: center;">
                    <img src="{{ asset('images/ridham.jpeg') }}" onerror="this.src='{{ asset('images/temp.jpeg') }}'" alt="" style="width: 80px; height: 80px; border-radius: 50%; object-fit: cover; border: 2px solid var(--navy); margin-bottom: 10px;">
                    <div style="font-size: 16px; font-weight: 800; color: var(--navy);">{{ $name }}</div>
                    <div style="font-size: 12.5px; font-weight: 700; color: var(--saffron-dark); margin-bottom: 6px;">{{ $enrollment }}</div>
                    <div style="font-size: 11.5px; color: var(--text-muted); margin-bottom: 10px;">{{ $course }}</div>

                    <div style="text-align: left; background: #F8FAFC; border: 1px solid var(--border-color); border-radius: 6px; padding: 8px 12px; font-size: 11.5px; color: #475569;">
                        <div>✉️ <strong>Email:</strong> {{ $email }}</div>
                        <div style="margin-top: 4px;">📝 <strong>Bio:</strong> {{ $bio }}</div>
                    </div>
                </div>

                <div style="background: #F1F5F9; border-top: 1px solid var(--border-color); padding: 8px; text-align: center; font-size: 10px; color: var(--text-muted);">
                    VALID FOR ACADEMIC YEAR 2025–2026 • GMCA WTP PRACTICAL 11.3
                </div>
            </div>
        </div>
    </div>

</div>
@endsection
