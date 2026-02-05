@extends('layouts.adminlte')

@section('title', 'รายงาน Ticket')
@section('page-title', 'รายงาน Ticket แจ้งซ่อม')

@section('breadcrumb')
<li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
<li class="breadcrumb-item active">รายงาน Ticket</li>
@endsection

@section('content')
<!-- Statistics -->
<div class="row">
    <div class="col-md-2">
        <div class="info-box bg-info">
            <span class="info-box-icon"><i class="fas fa-ticket-alt"></i></span>
            <div class="info-box-content">
                <span class="info-box-text">ทั้งหมด</span>
                <span class="info-box-number">{{ $stats['total'] }}</span>
            </div>
        </div>
    </div>
    <div class="col-md-2">
        <div class="info-box bg-warning">
            <span class="info-box-icon"><i class="fas fa-clock"></i></span>
            <div class="info-box-content">
                <span class="info-box-text">รอดำเนินการ</span>
                <span class="info-box-number">{{ $stats['pending'] }}</span>
            </div>
        </div>
    </div>
    <div class="col-md-2">
        <div class="info-box bg-primary">
            <span class="info-box-icon"><i class="fas fa-spinner"></i></span>
            <div class="info-box-content">
                <span class="info-box-text">กำลังดำเนินการ</span>
                <span class="info-box-number">{{ $stats['in_progress'] }}</span>
            </div>
        </div>
    </div>
    <div class="col-md-2">
        <div class="info-box bg-success">
            <span class="info-box-icon"><i class="fas fa-check"></i></span>
            <div class="info-box-content">
                <span class="info-box-text">แก้ไขแล้ว</span>
                <span class="info-box-number">{{ $stats['resolved'] }}</span>
            </div>
        </div>
    </div>
    <div class="col-md-2">
        <div class="info-box bg-secondary">
            <span class="info-box-icon"><i class="fas fa-times"></i></span>
            <div class="info-box-content">
                <span class="info-box-text">ปิดแล้ว</span>
                <span class="info-box-number">{{ $stats['closed'] }}</span>
            </div>
        </div>
    </div>
</div>

<!-- Filter -->
<div class="card">
    <div class="card-header">
        <h3 class="card-title">กรองข้อมูล</h3>
    </div>
    <div class="card-body">
        <form method="GET" action="{{ route('admin.reports.tickets') }}" class="row">
            <div class="col-md-3 mb-2">
                <select name="status" class="form-control">
                    <option value="">-- สถานะทั้งหมด --</option>
                    <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>รอดำเนินการ</option>
                    <option value="in_progress" {{ request('status') == 'in_progress' ? 'selected' : '' }}>กำลังดำเนินการ</option>
                    <option value="resolved" {{ request('status') == 'resolved' ? 'selected' : '' }}>แก้ไขแล้ว</option>
                    <option value="closed" {{ request('status') == 'closed' ? 'selected' : '' }}>ปิดแล้ว</option>
                </select>
            </div>
            <div class="col-md-2 mb-2">
                <input type="date" name="date_from" class="form-control" placeholder="จากวันที่" value="{{ request('date_from') }}">
            </div>
            <div class="col-md-2 mb-2">
                <input type="date" name="date_to" class="form-control" placeholder="ถึงวันที่" value="{{ request('date_to') }}">
            </div>
            <div class="col-md-2 mb-2">
                <button type="submit" class="btn btn-info btn-block"><i class="fas fa-search mr-1"></i> กรอง</button>
            </div>
            <div class="col-md-3 mb-2">
                <a href="{{ route('admin.reports.tickets.export', request()->all()) }}" class="btn btn-success btn-block">
                    <i class="fas fa-file-excel mr-1"></i> Export Excel
                </a>
            </div>
        </form>
    </div>
</div>

<!-- Data Table -->
<div class="card">
    <div class="card-header">
        <h3 class="card-title">รายการ Ticket ({{ $tickets->total() }} รายการ)</h3>
    </div>
    <div class="card-body table-responsive p-0">
        <table class="table table-hover table-striped">
            <thead>
                <tr>
                    <th>#</th>
                    <th>เลข Ticket</th>
                    <th>รหัสทรัพย์สิน</th>
                    <th>รายละเอียด</th>
                    <th>สถานะ</th>
                    <th>ผู้รับผิดชอบ</th>
                    <th>วันที่แจ้ง</th>
                    <th>วันที่แก้ไข</th>
                </tr>
            </thead>
            <tbody>
                @forelse($tickets as $index => $ticket)
                <tr>
                    <td>{{ $tickets->firstItem() + $index }}</td>
                    <td><a href="{{ route('tickets.show', $ticket) }}">{{ $ticket->ticket_number }}</a></td>
                    <td><a href="{{ route('assets.show', $ticket->asset_id) }}">{{ $ticket->asset->asset_id ?? '-' }}</a></td>
                    <td>{{ Str::limit($ticket->issue_description, 30) }}</td>
                    <td>
                        @switch($ticket->status)
                            @case('pending')
                                <span class="badge badge-warning">รอดำเนินการ</span>
                                @break
                            @case('in_progress')
                                <span class="badge badge-info">กำลังดำเนินการ</span>
                                @break
                            @case('resolved')
                                <span class="badge badge-success">แก้ไขแล้ว</span>
                                @break
                            @case('closed')
                                <span class="badge badge-secondary">ปิดแล้ว</span>
                                @break
                        @endswitch
                    </td>
                    <td>{{ $ticket->assignee->name ?? '-' }}</td>
                    <td>{{ $ticket->created_at->format('d/m/Y') }}</td>
                    <td>{{ $ticket->resolved_at ? $ticket->resolved_at->format('d/m/Y') : '-' }}</td>
                </tr>
                @empty
                <tr>
                    <td colspan="8" class="text-center text-muted py-4">ไม่พบข้อมูล</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="card-footer">
        {{ $tickets->links() }}
    </div>
</div>
@endsection
