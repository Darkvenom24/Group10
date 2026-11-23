@extends('layouts.app')

@section('title', 'phpMyAdmin Relational Database Explorer | GMCA Laravel')

@section('content')
<div style="display: flex; flex-direction: column; gap: 20px;">

    <!-- phpMyAdmin Branding Top Bar -->
    <div style="background: #F8FAFC; border: 1.5px solid var(--border-color); border-radius: 8px; padding: 14px 20px; display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 12px;">
        <div style="display: flex; align-items: center; gap: 12px;">
            <div style="width: 38px; height: 38px; background: #EA580C; border-radius: 6px; color: #FFFFFF; display: flex; align-items: center; justify-content: center; font-size: 20px; font-weight: 800;">
                🗄️
            </div>
            <div>
                <h1 style="font-size: 18px; font-weight: 800; color: #1E293B; margin: 0;">
                    phpMyAdmin 5.x Live Table & SQL Explorer
                </h1>
                <div style="font-size: 12px; color: var(--text-muted);">
                    Server: 127.0.0.1 • Database: <strong>gmca_railway_laravel</strong> • PHP {{ PHP_VERSION }}
                </div>
            </div>
        </div>

        <div style="display: flex; gap: 8px;">
            <span class="badge badge-green" style="font-size: 12px;">Server Up & Running</span>
            <span class="badge badge-navy" style="font-size: 12px;">Database Driver: {{ config('database.default') }}</span>
        </div>
    </div>

    <!-- 2-Column Table Selector & Data Inspector -->
    <div style="display: grid; grid-template-columns: 240px 1fr; gap: 20px;">
        
        <!-- Left: Database Tables Sidebar -->
        <div class="card" style="padding: 14px; margin-bottom: 0;">
            <div style="font-size: 12px; font-weight: 700; color: var(--navy); text-transform: uppercase; margin-bottom: 10px; border-bottom: 1px solid var(--border-color); padding-bottom: 6px;">
                Database Tables ({{ count($tablesInfo) }}):
            </div>

            <div style="display: flex; flex-direction: column; gap: 6px;">
                @foreach($tablesInfo as $tbl => $cnt)
                    <a href="{{ route('php.db_explorer', ['table' => $tbl]) }}" 
                       style="display: flex; justify-content: space-between; align-items: center; padding: 8px 10px; border-radius: 6px; font-size: 12.5px; text-decoration: none; border: 1px solid {{ $table === $tbl ? 'var(--navy)' : 'transparent' }}; background: {{ $table === $tbl ? '#EFF6FF' : '#FFFFFF' }}; color: {{ $table === $tbl ? 'var(--navy)' : '#334155' }}; font-weight: {{ $table === $tbl ? '700' : '500' }};">
                        <span>📁 {{ $tbl }}</span>
                        <span class="badge {{ $table === $tbl ? 'badge-navy' : 'badge-outline' }}" style="font-size: 10px;">{{ $cnt }}</span>
                    </a>
                @endforeach
            </div>
        </div>

        <!-- Right: Table Data Grid with Pagination -->
        <div style="display: flex; flex-direction: column; gap: 16px;">
            
            <!-- SQL Console Simulator Strip -->
            <div style="background: #0F172A; color: #38BDF8; padding: 12px 16px; border-radius: 8px; font-family: 'Fira Code', monospace; font-size: 12.5px; display: flex; justify-content: space-between; align-items: center;">
                <div>
                    <span style="color: #94A3B8;">SQL:</span> <code>SELECT * FROM `{{ $table }}` ORDER BY `id` DESC LIMIT 15;</code>
                </div>
                <span class="badge badge-green" style="font-size: 10px;">2.4ms</span>
            </div>

            <div class="card" style="padding: 0; overflow: hidden; margin-bottom: 0;">
                <div class="card-header" style="margin: 0; padding: 14px 18px;">
                    <div class="card-title" style="font-size: 15px;">
                        <span>📋</span> Browsing Table: <code>{{ $table }}</code>
                    </div>
                    <span class="badge badge-navy">{{ $records->total() }} Total Rows</span>
                </div>

                <div class="table-responsive">
                    <table class="data-table">
                        <thead>
                            <tr>
                                @foreach($columns as $col)
                                    <th>{{ $col }}</th>
                                @endforeach
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($records as $row)
                                <tr>
                                    @foreach($columns as $col)
                                        <td style="font-size: 12px; max-width: 250px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;">
                                            @php
                                                $val = $row->$col ?? '';
                                            @endphp
                                            @if($col === 'id' || $col === 'pnr')
                                                <strong>{{ $val }}</strong>
                                            @elseif(is_string($val) && strlen($val) > 40)
                                                {{ substr($val, 0, 37) }}...
                                            @else
                                                {{ $val }}
                                            @endif
                                        </td>
                                    @endforeach
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="{{ count($columns) }}" style="text-align: center; padding: 30px; color: var(--text-muted);">
                                        No rows found in table <code>{{ $table }}</code>.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Table Pagination -->
            <div class="card" style="padding: 12px 18px; margin-bottom: 0;">
                <div style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap;">
                    <div style="font-size: 12.5px; color: var(--text-muted);">
                        Showing {{ $records->firstItem() ?? 0 }} to {{ $records->lastItem() ?? 0 }} of {{ $records->total() }} rows in <code>{{ $table }}</code>
                    </div>
                    <div>{{ $records->links() }}</div>
                </div>
            </div>

        </div>

    </div>

</div>
@endsection
