@extends('layouts.app')

@section('title', 'Practical 1 — Basic HTML Institutional Webpage | GMCA Laravel')

@section('content')
<div style="display: flex; flex-direction: column; gap: 20px;">
    
    <div style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 10px;">
        <div>
            <h1 style="font-size: 24px; font-weight: 800; color: var(--navy); margin-bottom: 4px;">
                Practical 1: Basic HTML Webpage (Table Layout & Elements)
            </h1>
            <p style="color: var(--text-muted); font-size: 13.5px;">
                MCA Semester 1/2 • Government MCA College, Maninagar Institutional Showcase
            </p>
        </div>
        <span class="badge badge-navy">Lead: Ridham Bambhaniya (26GMCA61)</span>
    </div>

    <!-- Practical 1 Institutional Table-Based Showcase Card -->
    <div class="card" style="padding: 0; overflow: hidden; border: 2px solid var(--navy);">
        
        <!-- College Header Table -->
        <table width="100%" cellpadding="14" cellspacing="0" style="background: var(--navy); color: #FFFFFF; border-collapse: collapse;">
            <tr>
                <td width="70" align="center" style="font-size: 34px;">🏛️</td>
                <td>
                    <h2 style="font-size: 20px; font-weight: 800; margin: 0; color: #FFFFFF;">GOVERNMENT MCA COLLEGE, MANINAGAR</h2>
                    <p style="font-size: 12.5px; margin: 4px 0 0; color: #E2E8F0;">
                        K.K. Shastri Educational Campus, Khokhra Road, Maninagar (E), Ahmedabad - 380008, Gujarat
                    </p>
                </td>
                <td width="160" align="right">
                    <span style="background: var(--saffron); color: #FFFFFF; font-size: 11px; font-weight: 700; padding: 4px 10px; border-radius: 4px;">
                        ESTD. 2012
                    </span>
                </td>
            </tr>
        </table>

        <!-- Navigation Bar Row in HTML Table Format -->
        <table width="100%" cellpadding="10" cellspacing="0" style="background: #F1F5F9; border-bottom: 2px solid var(--border-color); font-size: 13px; font-weight: 600;">
            <tr align="center">
                <td><a href="#about" style="color: var(--navy); text-decoration: none;">About College</a></td>
                <td><a href="#courses" style="color: var(--navy); text-decoration: none;">Academic Programs</a></td>
                <td><a href="#faculty" style="color: var(--navy); text-decoration: none;">Faculty & Labs</a></td>
                <td><a href="#admissions" style="color: var(--navy); text-decoration: none;">Admissions & Eligibility</a></td>
                <td><a href="#contact" style="color: var(--navy); text-decoration: none;">Contact Information</a></td>
            </tr>
        </table>

        <!-- Body Content Table (2-Column Classical HTML Layout) -->
        <table width="100%" cellpadding="20" cellspacing="0" style="background: #FFFFFF;">
            <tr valign="top">
                <!-- Left Main Content -->
                <td width="70%" style="border-right: 1px solid var(--border-color); line-height: 1.6; font-size: 13.5px; color: #334155;">
                    <h3 id="about" style="color: var(--navy); font-size: 17px; margin-bottom: 8px;">About The Institution</h3>
                    <p style="margin-bottom: 14px;">
                        Government MCA College, Maninagar is Gujarat's premier state institution dedicated exclusively to postgraduate education in Computer Applications. Affiliated with Gujarat Technological University (GTU) and approved by AICTE, New Delhi.
                    </p>

                    <h3 id="courses" style="color: var(--navy); font-size: 17px; margin-bottom: 8px;">Academic Programs</h3>
                    <table width="100%" cellpadding="8" cellspacing="0" border="1" style="border-collapse: collapse; border-color: var(--border-color); margin-bottom: 16px; font-size: 13px;">
                        <tr bgcolor="#000080" style="color: #FFFFFF;">
                            <th>Course Name</th>
                            <th>Intake</th>
                            <th>Duration</th>
                            <th>Affiliation</th>
                        </tr>
                        <tr>
                            <td><strong>Master of Computer Applications (MCA)</strong></td>
                            <td>60 Seats</td>
                            <td>2 Years (4 Semesters)</td>
                            <td>GTU / AICTE</td>
                        </tr>
                        <tr>
                            <td><strong>Integrated MCA (Lateral Entry)</strong></td>
                            <td>30 Seats</td>
                            <td>2 Years</td>
                            <td>GTU / ACPC</td>
                        </tr>
                    </table>

                    <h3 id="faculty" style="color: var(--navy); font-size: 17px; margin-bottom: 8px;">Computing Infrastructure</h3>
                    <ul style="padding-left: 20px; line-height: 1.8;">
                        <li>High-performance computing laboratory with Gigabit LAN network.</li>
                        <li>Dedicated Web Technology and Open Source Software development cell.</li>
                        <li>Air-conditioned seminar hall and central digital library access.</li>
                    </ul>
                </td>

                <!-- Right Sidebar Table Column -->
                <td width="30%" style="background: #F8FAFC; line-height: 1.6; font-size: 13px;">
                    <div style="background: #EFF6FF; border: 1px solid #BFDBFE; border-radius: 6px; padding: 12px; margin-bottom: 16px;">
                        <h4 style="color: var(--navy); margin-bottom: 6px; font-size: 14px;">Group 10 Lab Credentials</h4>
                        <p style="font-size: 12px; color: #1E40AF; margin: 0;">
                            <strong>Team Lead:</strong> Ridham Bambhaniya (26GMCA61)<br>
                            <strong>Member:</strong> Riya Thakkar (26GMCA33)<br>
                            <strong>Member:</strong> Vaibhav Senjaliya (26GMCA52)
                        </p>
                    </div>

                    <h4 id="contact" style="color: var(--navy); margin-bottom: 6px; font-size: 14px;">Contact Office</h4>
                    <p style="font-size: 12.5px; color: #475569;">
                        📍 K.K. Shastri Campus, Maninagar (E), Ahmedabad<br>
                        📞 Phone: +91 79 2293 0084<br>
                        ✉️ Email: info@gmca.ac.in<br>
                        🌐 Web: www.gmca.ac.in
                    </p>
                </td>
            </tr>
        </table>

        <!-- Footer Table -->
        <table width="100%" cellpadding="12" cellspacing="0" style="background: #0F172A; color: #94A3B8; font-size: 12px; text-align: center;">
            <tr>
                <td>Practical 1 HTML Institutional Layout Submission • Group 10 Laravel Portal</td>
            </tr>
        </table>

    </div>

</div>
@endsection
