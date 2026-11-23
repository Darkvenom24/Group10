@extends('layouts.app')

@section('title', 'Practical 7 Submitted Records — Student Registry | GMCA Laravel')

@section('content')
<div style="display: flex; flex-direction: column; gap: 20px;">

    <!-- Top Header -->
    <div style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 10px;">
        <div>
            <h1 style="font-size: 24px; font-weight: 800; color: var(--navy); margin-bottom: 4px;">
                Practical 7 & 12: Student Academic Database Registry
            </h1>
            <p style="color: var(--text-muted); font-size: 13.5px; margin: 0;">
                Submitted academic dossier records with Laravel Eloquent pagination.
            </p>
        </div>

        <a href="{{ route('practicals.p7') }}" class="btn btn-primary">
            <span>+</span> Submit New Academic Record
        </a>
    </div>

    <!-- Search Form -->
    <div class="card" style="padding: 16px; margin-bottom: 0;">
        <form method="GET" action="{{ route('practicals.p7.data') }}" style="display: flex; gap: 10px; align-items: center;">
            <input type="text" name="search" class="form-control" placeholder="Search by Enrollment, Name, or Project Title..." value="{{ $search }}" style="flex: 1;">
            <button type="submit" class="btn btn-primary">
                <span>🔍</span> Search
            </button>
            @if($search)
                <a href="{{ route('practicals.p7.data') }}" class="btn btn-outline">Clear</a>
            @endif
        </form>
    </div>

    <!-- Student Records Table with Laravel Pagination -->
    <div class="card" style="padding: 0; overflow: hidden; margin-bottom: 0;">
        <div class="table-responsive">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Enrollment</th>
                        <th>Student Name</th>
                        <th>Email & Phone</th>
                        <th>Semester</th>
                        <th>Project Title</th>
                        <th>Skills</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($students as $s)
                        <tr>
                            <td><strong>#{{ $s->id }}</strong></td>
                            <td><strong style="color: var(--navy);">{{ $s->enrollment }}</strong></td>
                            <td>{{ $s->full_name }}</td>
                            <td>
                                <div>{{ $s->email }}</div>
                                <div style="font-size: 11px; color: var(--text-muted);">{{ $s->phone }}</div>
                            </td>
                            <td><span class="badge badge-navy">{{ $s->semester }}</span></td>
                            <td>
                                <strong>{{ $s->project_title }}</strong>
                                <div style="font-size: 11.5px; color: var(--text-muted); max-width: 300px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;">
                                    {{ $s->project_desc }}
                                </div>
                            </td>
                            <td style="font-size: 11.5px; max-width: 180px;">{{ $s->skills }}</td>
                            <td><span class="badge badge-green">{{ $s->status }}</span></td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" style="text-align: center; padding: 30px; color: var(--text-muted);">
                                No Student Academic Records Found.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Pagination -->
    <div class="card" style="padding: 14px 20px; margin-bottom: 0;">
        <div style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 10px;">
            <div style="font-size: 13px; color: var(--text-muted);">
                Showing {{ $students->firstItem() ?? 0 }} to {{ $students->lastItem() ?? 0 }} of {{ $students->total() }} student records
            </div>
            <div>
                {{ $students->links() }}
            </div>
        </div>
    </div>

</div>
@endsection
