@extends('layouts.app')

@section('title', 'Practical 2 — HTML & CSS Responsive Styling | GMCA Laravel')

@section('content')
<div style="display: flex; flex-direction: column; gap: 20px;">
    
    <div style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 10px;">
        <div>
            <h1 style="font-size: 24px; font-weight: 800; color: var(--navy); margin-bottom: 4px;">
                Practical 2: HTML & CSS Box Model and Tricolor Layout
            </h1>
            <p style="color: var(--text-muted); font-size: 13.5px;">
                Indian National Flag Colors (Saffron, White, Emerald Green, Ashoka Chakra Navy)
            </p>
        </div>
        <span class="badge badge-saffron">Practical 2 Submission</span>
    </div>

    <!-- Practical 2 Demonstration Card -->
    <div class="card">
        <div class="card-header">
            <div class="card-title">
                <span>🎨</span> Indian Tricolor CSS Token Showcase
            </div>
            <span class="badge badge-green">CSS3 Box Model & Grid</span>
        </div>

        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(260px, 1fr)); gap: 16px; margin-bottom: 24px;">
            <div style="background: #FFF7ED; border-top: 6px solid #FF9933; border-radius: 8px; padding: 18px; box-shadow: var(--card-shadow);">
                <div style="font-size: 13px; font-weight: 700; color: #C2410C; text-transform: uppercase;">Indian Saffron (Kesari)</div>
                <div style="font-size: 22px; font-weight: 800; color: #9A3412; margin: 4px 0;">#FF9933</div>
                <p style="font-size: 12.5px; color: #7C2D12;">Signifies courage, strength, and dynamic energy of the railway network.</p>
            </div>

            <div style="background: #F8FAFC; border-top: 6px solid #000080; border-radius: 8px; padding: 18px; box-shadow: var(--card-shadow);">
                <div style="font-size: 13px; font-weight: 700; color: var(--navy); text-transform: uppercase;">Ashoka Chakra Navy</div>
                <div style="font-size: 22px; font-weight: 800; color: var(--navy); margin: 4px 0;">#000080</div>
                <p style="font-size: 12.5px; color: #1E3A8A;">Signifies truth, vigilance, institutional integrity, and technological progress.</p>
            </div>

            <div style="background: #F0FDF4; border-top: 6px solid #138808; border-radius: 8px; padding: 18px; box-shadow: var(--card-shadow);">
                <div style="font-size: 13px; font-weight: 700; color: #15803D; text-transform: uppercase;">Indian Green</div>
                <div style="font-size: 22px; font-weight: 800; color: #166534; margin: 4px 0;">#138808</div>
                <p style="font-size: 12.5px; color: #14532D;">Signifies growth, auspiciousness, and ecological railway electrification.</p>
            </div>
        </div>

        <!-- Group 10 Member Cards with Practical 2 CSS Styling -->
        <h3 style="font-size: 17px; font-weight: 700; color: var(--navy); margin-bottom: 14px;">
            Structured Profile Cards (Practical 2 Layout):
        </h3>

        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(280px, 1fr)); gap: 18px;">
            <div style="border: 1px solid var(--border-color); border-radius: 10px; padding: 18px; background: #FFFFFF; box-shadow: var(--card-shadow);">
                <div style="display: flex; align-items: center; gap: 12px; margin-bottom: 12px;">
                    <img src="{{ asset('images/ridham.jpeg') }}" onerror="this.src='{{ asset('images/temp.jpeg') }}'" alt="Ridham" style="width: 50px; height: 50px; border-radius: 50%; object-fit: cover; border: 2px solid var(--navy);">
                    <div>
                        <div style="font-weight: 800; font-size: 15px; color: var(--navy);">Ridham Bambhaniya</div>
                        <div style="font-size: 12px; color: var(--saffron-dark); font-weight: 700;">26GMCA61 • Team Lead</div>
                    </div>
                </div>
                <p style="font-size: 12.5px; color: #475569;">Specialized in CSS box models, navigation bars, and responsive media query stacking.</p>
            </div>

            <div style="border: 1px solid var(--border-color); border-radius: 10px; padding: 18px; background: #FFFFFF; box-shadow: var(--card-shadow);">
                <div style="display: flex; align-items: center; gap: 12px; margin-bottom: 12px;">
                    <img src="{{ asset('images/riya.jpeg') }}" onerror="this.src='{{ asset('images/temp.jpeg') }}'" alt="Riya" style="width: 50px; height: 50px; border-radius: 50%; object-fit: cover; border: 2px solid var(--navy);">
                    <div>
                        <div style="font-weight: 800; font-size: 15px; color: var(--navy);">Riya Thakkar</div>
                        <div style="font-size: 12px; color: var(--saffron-dark); font-weight: 700;">26GMCA33 • Core Member</div>
                    </div>
                </div>
                <p style="font-size: 12.5px; color: #475569;">Specialized in color palette consistency, UI form elements, and button states.</p>
            </div>

            <div style="border: 1px solid var(--border-color); border-radius: 10px; padding: 18px; background: #FFFFFF; box-shadow: var(--card-shadow);">
                <div style="display: flex; align-items: center; gap: 12px; margin-bottom: 12px;">
                    <img src="{{ asset('images/vaibhav.jpeg') }}" onerror="this.src='{{ asset('images/temp.jpeg') }}'" alt="Vaibhav" style="width: 50px; height: 50px; border-radius: 50%; object-fit: cover; border: 2px solid var(--navy);">
                    <div>
                        <div style="font-weight: 800; font-size: 15px; color: var(--navy);">Vaibhav Senjaliya</div>
                        <div style="font-size: 12px; color: var(--saffron-dark); font-weight: 700;">26GMCA52 • Developer</div>
                    </div>
                </div>
                <p style="font-size: 12.5px; color: #475569;">Specialized in responsive layout grids, flexbox containers, and Laravel Blade layouts.</p>
            </div>
        </div>
    </div>

</div>
@endsection
