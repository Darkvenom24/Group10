@extends('layouts.app')

@section('title', 'PHP Practicals Hub (9 to 12) — Full Curriculum | GMCA Laravel')

@section('content')
<div style="display: flex; flex-direction: column; gap: 24px;">

    <!-- Top Banner -->
    <div style="background: linear-gradient(135deg, #1E1B4B 0%, #000080 100%); color: #FFFFFF; border-radius: 12px; padding: 24px 28px; border-left: 6px solid var(--saffron);">
        <span class="badge badge-saffron" style="margin-bottom: 8px;">PHP Practicals (9.1 to 12.0) Converted to Laravel MVC</span>
        <h1 style="font-size: 26px; font-weight: 800; color: #FFFFFF; margin: 4px 0 8px;">
            Interactive PHP Practicals Hub
        </h1>
        <p style="color: #CBD5E1; font-size: 14px; margin: 0; max-width: 800px;">
            Complete collection of PHP control structures, array algorithms, string parsers, database PDO connection, 
            and authentic phpMyAdmin relational table explorer running live inside Laravel!
        </p>
    </div>

    <!-- Practicals Grid -->
    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(320px, 1fr)); gap: 18px;">
        
        <!-- 9.1 -->
        <a href="{{ route('php.9_1') }}" style="text-decoration: none; color: inherit;">
            <div class="card" style="margin-bottom: 0; height: 100%; transition: all 0.2s;" onmouseover="this.style.borderColor='var(--navy)'" onmouseout="this.style.borderColor='var(--border-color)'">
                <span class="badge badge-navy" style="margin-bottom: 8px;">Practical 9.1</span>
                <h3 style="font-size: 16px; font-weight: 700; color: var(--navy); margin-bottom: 4px;">Max Value of 3 Numbers</h3>
                <p style="font-size: 12.5px; color: var(--text-muted); margin-bottom: 12px;">Evaluates the highest of three numbers using comparison branches and PHP logic.</p>
                <span class="btn btn-outline btn-sm">Launch 9.1 →</span>
            </div>
        </a>

        <!-- 9.2 -->
        <a href="{{ route('php.9_2') }}" style="text-decoration: none; color: inherit;">
            <div class="card" style="margin-bottom: 0; height: 100%; transition: all 0.2s;" onmouseover="this.style.borderColor='var(--navy)'" onmouseout="this.style.borderColor='var(--border-color)'">
                <span class="badge badge-navy" style="margin-bottom: 8px;">Practical 9.2</span>
                <h3 style="font-size: 16px; font-weight: 700; color: var(--navy); margin-bottom: 4px;">Print 1 to N Numbers</h3>
                <p style="font-size: 12.5px; color: var(--text-muted); margin-bottom: 12px;">Dynamic loop with odd/even color badges, cumulative sum, and arithmetic analysis.</p>
                <span class="btn btn-outline btn-sm">Launch 9.2 →</span>
            </div>
        </a>

        <!-- 9.3 -->
        <a href="{{ route('php.9_3') }}" style="text-decoration: none; color: inherit;">
            <div class="card" style="margin-bottom: 0; height: 100%; transition: all 0.2s;" onmouseover="this.style.borderColor='var(--navy)'" onmouseout="this.style.borderColor='var(--border-color)'">
                <span class="badge badge-navy" style="margin-bottom: 8px;">Practical 9.3</span>
                <h3 style="font-size: 16px; font-weight: 700; color: var(--navy); margin-bottom: 4px;">3 Types of Pyramid Patterns</h3>
                <p style="font-size: 12.5px; color: var(--text-muted); margin-bottom: 12px;">Right-angled, centered equilateral, and inverted pyramids for customizable row counts.</p>
                <span class="btn btn-outline btn-sm">Launch 9.3 →</span>
            </div>
        </a>

        <!-- 10.1 -->
        <a href="{{ route('php.10_1') }}" style="text-decoration: none; color: inherit;">
            <div class="card" style="margin-bottom: 0; height: 100%; transition: all 0.2s;" onmouseover="this.style.borderColor='var(--navy)'" onmouseout="this.style.borderColor='var(--border-color)'">
                <span class="badge badge-saffron" style="margin-bottom: 8px;">Practical 10.1</span>
                <h3 style="font-size: 16px; font-weight: 700; color: var(--navy); margin-bottom: 4px;">Array Operations</h3>
                <p style="font-size: 12.5px; color: var(--text-muted); margin-bottom: 12px;">Print values, reverse array, merge two arrays in sorted order, and compute cumulative sum.</p>
                <span class="btn btn-outline btn-sm">Launch 10.1 →</span>
            </div>
        </a>

        <!-- 10.2 -->
        <a href="{{ route('php.10_2') }}" style="text-decoration: none; color: inherit;">
            <div class="card" style="margin-bottom: 0; height: 100%; transition: all 0.2s;" onmouseover="this.style.borderColor='var(--navy)'" onmouseout="this.style.borderColor='var(--border-color)'">
                <span class="badge badge-saffron" style="margin-bottom: 8px;">Practical 10.2</span>
                <h3 style="font-size: 16px; font-weight: 700; color: var(--navy); margin-bottom: 4px;">String Name & Size Passed As Arg</h3>
                <p style="font-size: 12.5px; color: var(--text-muted); margin-bottom: 12px;">Passes name into PHP function, computing byte length, multibyte length, and word count.</p>
                <span class="btn btn-outline btn-sm">Launch 10.2 →</span>
            </div>
        </a>

        <!-- 11.1 -->
        <a href="{{ route('php.11_1') }}" style="text-decoration: none; color: inherit;">
            <div class="card" style="margin-bottom: 0; height: 100%; transition: all 0.2s;" onmouseover="this.style.borderColor='var(--navy)'" onmouseout="this.style.borderColor='var(--border-color)'">
                <span class="badge badge-green" style="margin-bottom: 8px;">Practical 11.1</span>
                <h3 style="font-size: 16px; font-weight: 700; color: var(--navy); margin-bottom: 4px;">Max and Min (If-Else & Ternary)</h3>
                <p style="font-size: 12.5px; color: var(--text-muted); margin-bottom: 12px;">Evaluates both maximum and minimum numbers using both branching and compact ternary operators.</p>
                <span class="btn btn-outline btn-sm">Launch 11.1 →</span>
            </div>
        </a>

        <!-- 11.2 -->
        <a href="{{ route('php.11_2') }}" style="text-decoration: none; color: inherit;">
            <div class="card" style="margin-bottom: 0; height: 100%; transition: all 0.2s;" onmouseover="this.style.borderColor='var(--navy)'" onmouseout="this.style.borderColor='var(--border-color)'">
                <span class="badge badge-green" style="margin-bottom: 8px;">Practical 11.2</span>
                <h3 style="font-size: 16px; font-weight: 700; color: var(--navy); margin-bottom: 4px;">Date/Time & Contextual Greeting</h3>
                <p style="font-size: 12.5px; color: var(--text-muted); margin-bottom: 12px;">Outputs Good Morning, Afternoon, Evening, or Night based on system time or interactive slider.</p>
                <span class="btn btn-outline btn-sm">Launch 11.2 →</span>
            </div>
        </a>

        <!-- 11.3 -->
        <a href="{{ route('php.11_3') }}" style="text-decoration: none; color: inherit;">
            <div class="card" style="margin-bottom: 0; height: 100%; transition: all 0.2s;" onmouseover="this.style.borderColor='var(--navy)'" onmouseout="this.style.borderColor='var(--border-color)'">
                <span class="badge badge-green" style="margin-bottom: 8px;">Practical 11.3</span>
                <h3 style="font-size: 16px; font-weight: 700; color: var(--navy); margin-bottom: 4px;">User Profile Web Page & ID Card</h3>
                <p style="font-size: 12.5px; color: var(--text-muted); margin-bottom: 12px;">Submits candidate parameters and dynamically renders official GMCA photo ID badge.</p>
                <span class="btn btn-outline btn-sm">Launch 11.3 →</span>
            </div>
        </a>

        <!-- 11.4 -->
        <a href="{{ route('php.11_4') }}" style="text-decoration: none; color: inherit;">
            <div class="card" style="margin-bottom: 0; height: 100%; transition: all 0.2s;" onmouseover="this.style.borderColor='var(--navy)'" onmouseout="this.style.borderColor='var(--border-color)'">
                <span class="badge badge-green" style="margin-bottom: 8px;">Practical 11.4</span>
                <h3 style="font-size: 16px; font-weight: 700; color: var(--navy); margin-bottom: 4px;">5 PHP String Operations</h3>
                <p style="font-size: 12.5px; color: var(--text-muted); margin-bottom: 12px;">Print name, strlen, string concatenation, uppercase/lowercase/titlecase, and strpos.</p>
                <span class="btn btn-outline btn-sm">Launch 11.4 →</span>
            </div>
        </a>

        <!-- 12 -->
        <a href="{{ route('php.12') }}" style="text-decoration: none; color: inherit;">
            <div class="card" style="margin-bottom: 0; height: 100%; border: 2px solid var(--navy); background: #EFF6FF; transition: all 0.2s;">
                <span class="badge badge-admin" style="margin-bottom: 8px;">Practical 12 (Core)</span>
                <h3 style="font-size: 16px; font-weight: 700; color: var(--navy); margin-bottom: 4px;">Database Connection & CRUD</h3>
                <p style="font-size: 12.5px; color: #1E3A8A; margin-bottom: 12px;">Full Create, Read, Update, and Delete operations linking Practical 7 and Railway tables.</p>
                <span class="btn btn-primary btn-sm">Launch Practical 12 →</span>
            </div>
        </a>

        <!-- Database Explorer -->
        <a href="{{ route('php.db_explorer') }}" style="text-decoration: none; color: inherit;">
            <div class="card" style="margin-bottom: 0; height: 100%; border: 2px solid var(--saffron); background: #FFFBEB; transition: all 0.2s;">
                <span class="badge badge-saffron" style="margin-bottom: 8px;">phpMyAdmin Explorer</span>
                <h3 style="font-size: 16px; font-weight: 700; color: #9A3412; margin-bottom: 4px;">Relational Table Inspector</h3>
                <p style="font-size: 12.5px; color: #78350F; margin-bottom: 12px;">Inspect database tables, records, schemas, and live SQL statements directly in Laravel.</p>
                <span class="btn btn-saffron btn-sm">Open Explorer →</span>
            </div>
        </a>
    </div>

</div>
@endsection
