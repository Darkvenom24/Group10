@extends('layouts.app')

@section('title', 'Practical 7 — Student Academic & Project Registration | GMCA Laravel')

@section('content')
<div style="max-width: 800px; margin: 10px auto; display: flex; flex-direction: column; gap: 20px;">

    <div style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 10px;">
        <div>
            <span class="badge badge-saffron" style="margin-bottom: 6px;">Practical 7 Submission</span>
            <h1 style="font-size: 24px; font-weight: 800; color: var(--navy); margin: 0 0 4px;">
                Student Academic & Project Registration Form
            </h1>
            <p style="color: var(--text-muted); font-size: 13.5px; margin: 0;">
                Stores student credentials, technical stack, and capstone project directly into SQLite/MySQL database.
            </p>
        </div>

        <a href="{{ route('practicals.p7.data') }}" class="btn btn-outline">
            <span>📋</span> View Submitted Records
        </a>
    </div>

    <div class="card">
        <div class="card-header">
            <div class="card-title">
                <span>📝</span> Candidate Registration Details
            </div>
            <span class="badge badge-navy">Logged In: {{ Auth::user()->name }}</span>
        </div>

        <form action="{{ route('practicals.p7.submit') }}" method="POST">
            @csrf

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 16px;">
                <!-- Enrollment -->
                <div class="form-group">
                    <label class="form-label" for="enrollment">Enrollment Number: *</label>
                    <input type="text" name="enrollment" id="enrollment" class="form-control" 
                           value="{{ old('enrollment', Auth::user()->enrollment) }}" required>
                </div>

                <!-- Full Name -->
                <div class="form-group">
                    <label class="form-label" for="full_name">Student Full Name: *</label>
                    <input type="text" name="full_name" id="full_name" class="form-control" 
                           value="{{ old('full_name', Auth::user()->name) }}" required>
                </div>

                <!-- Email -->
                <div class="form-group">
                    <label class="form-label" for="email">Student Email Address: *</label>
                    <input type="email" name="email" id="email" class="form-control" 
                           value="{{ old('email', Auth::user()->email) }}" required>
                </div>

                <!-- Phone -->
                <div class="form-group">
                    <label class="form-label" for="phone">Contact Number: *</label>
                    <input type="tel" name="phone" id="phone" class="form-control" 
                           value="{{ old('phone', '9825012345') }}" pattern="[0-9]{10}" placeholder="10-digit mobile" required>
                </div>

                <!-- Department -->
                <div class="form-group">
                    <label class="form-label" for="department">Department / Course: *</label>
                    <input type="text" name="department" id="department" class="form-control" 
                           value="Master of Computer Applications (MCA)" required>
                </div>

                <!-- Semester -->
                <div class="form-group">
                    <label class="form-label" for="semester">Current Academic Semester: *</label>
                    <select name="semester" id="semester" class="form-select" required>
                        <option value="Semester 1">Semester 1</option>
                        <option value="Semester 2" selected>Semester 2</option>
                        <option value="Semester 3">Semester 3</option>
                        <option value="Semester 4">Semester 4</option>
                    </select>
                </div>

                <!-- Gender -->
                <div class="form-group">
                    <label class="form-label" for="gender">Gender:</label>
                    <select name="gender" id="gender" class="form-select">
                        <option value="Male">Male</option>
                        <option value="Female">Female</option>
                        <option value="Not Specified">Not Specified</option>
                    </select>
                </div>

                <!-- DOB -->
                <div class="form-group">
                    <label class="form-label" for="dob">Date of Birth:</label>
                    <input type="date" name="dob" id="dob" class="form-control" value="2003-05-15">
                </div>

                <!-- Project Title -->
                <div class="form-group" style="grid-column: span 2;">
                    <label class="form-label" for="project_title">Chosen Project Title: *</label>
                    <input type="text" name="project_title" id="project_title" class="form-control" 
                           value="{{ old('project_title', 'Multi-System Indian Railways Laravel Portal & Management System') }}" required>
                </div>

                <!-- Skills -->
                <div class="form-group" style="grid-column: span 2;">
                    <label class="form-label" for="skills">Technical Stack & Skills (Comma Separated):</label>
                    <input type="text" name="skills" id="skills" class="form-control" 
                           value="Laravel 11, PHP 8.3, Blade, SQLite, MySQL, JavaScript, HTML5, CSS3">
                </div>

                <!-- Project Description -->
                <div class="form-group" style="grid-column: span 2;">
                    <label class="form-label" for="project_desc">Project Scope & Abstract:</label>
                    <textarea name="project_desc" id="project_desc" class="form-control" rows="3">Architected and deployed full-featured Indian Railways ticketing portal across 5 railway systems with Station Master role-based admin and 100+ entries pagination.</textarea>
                </div>

                <!-- Address -->
                <div class="form-group" style="grid-column: span 2;">
                    <label class="form-label" for="address">Residential / Postal Address:</label>
                    <input type="text" name="address" id="address" class="form-control" value="Maninagar, Ahmedabad, Gujarat">
                </div>
            </div>

            <div style="margin-top: 20px; padding-top: 16px; border-top: 1px solid var(--border-color); display: flex; justify-content: flex-end; gap: 12px;">
                <button type="reset" class="btn btn-outline">Clear Form</button>
                <button type="submit" class="btn btn-primary" style="padding: 10px 24px;">
                    <span>✔</span> Save Practical 7 Academic Record
                </button>
            </div>
        </form>
    </div>

</div>
@endsection
