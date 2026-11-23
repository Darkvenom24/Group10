@extends('layouts.app')

@section('title', 'Practical 12 — Database Connection & MySQL/SQLite CRUD | GMCA Laravel')

@section('content')
<div style="display: flex; flex-direction: column; gap: 20px;">
    
    <div style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 10px;">
        <div>
            <h1 style="font-size: 24px; font-weight: 800; color: var(--navy); margin-bottom: 4px;">
                Practical 12: Database Connection & CRUD Operations
            </h1>
            <p style="color: var(--text-muted); font-size: 13.5px; margin: 0;">
                Demonstrates authentic relational database connectivity, prepared statements, and CRUD execution in Laravel.
            </p>
        </div>

        <a href="{{ route('php.db_explorer') }}" class="btn btn-saffron">
            <span>🗄️</span> phpMyAdmin Database Explorer →
        </a>
    </div>

    <!-- DB Connection Diagnostic Card -->
    <div style="background: #EFF6FF; border: 1.5px solid #BFDBFE; border-radius: 8px; padding: 14px 20px; display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 12px; font-size: 12.5px;">
        <div>
            <span style="color: var(--text-muted); display: block;">DATABASE DRIVER:</span>
            <strong style="color: var(--navy);">{{ config('database.default') }} (SQLite / PDO MySQL)</strong>
        </div>
        <div>
            <span style="color: var(--text-muted); display: block;">ACTIVE CONNECTION:</span>
            <strong style="color: #15803D;">● Connected & Querying Successfully</strong>
        </div>
        <div>
            <span style="color: var(--text-muted); display: block;">CRUD TABLE MAPPED:</span>
            <code>practical7_students</code> & <code>railway_bookings</code>
        </div>
        <div>
            <span style="color: var(--text-muted); display: block;">TRANSACTION INTEGRITY:</span>
            <strong>ACID Compliant</strong>
        </div>
    </div>

    <!-- Student Records Table -->
    <div class="card" style="padding: 0; overflow: hidden;">
        <div class="card-header" style="margin: 0; padding: 16px 20px;">
            <div class="card-title">
                <span>📋</span> CRUD Records from <code>practical7_students</code> Table
            </div>
            <a href="{{ route('practicals.p7') }}" class="btn btn-primary btn-sm">
                + Insert New Student Record
            </a>
        </div>

        <div class="table-responsive">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Enrollment</th>
                        <th>Full Name</th>
                        <th>Department</th>
                        <th>Semester</th>
                        <th>Project Title</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($students as $row)
                        <tr>
                            <td><strong>#{{ $row->id }}</strong></td>
                            <td><strong style="color: var(--navy);">{{ $row->enrollment }}</strong></td>
                            <td>{{ $row->full_name }}</td>
                            <td>{{ $row->department }}</td>
                            <td><span class="badge badge-navy">{{ $row->semester }}</span></td>
                            <td>{{ $row->project_title }}</td>
                            <td><span class="badge badge-green">{{ $row->status }}</span></td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" style="text-align: center; padding: 30px; color: var(--text-muted);">
                                No records found in database.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Pagination -->
    <div class="card" style="padding: 12px 20px; margin-bottom: 0;">
        <div style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap;">
            <div style="font-size: 13px; color: var(--text-muted);">
                Showing {{ $students->firstItem() ?? 0 }} to {{ $students->lastItem() ?? 0 }} of {{ $students->total() }} records
            </div>
            <div>{{ $students->links() }}</div>
        </div>
    </div>

</div>
@endsection
