@extends('layouts.adminlte')

@section('title', 'รายการ Ticket')
@section('page-title', 'รายการ Ticket แจ้งซ่อม')

@section('breadcrumb')
<li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
<li class="breadcrumb-item active">Ticket</li>
@endsection

@section('content')
<div class="card">
    <div class="card-header">
        <h3 class="card-title">ค้นหาและกรอง</h3>
        <div class="card-tools">
            <a href="{{ route('tickets.create') }}" class="btn btn-primary btn-sm">
                <i class="fas fa-plus mr-1"></i> สร้าง Ticket
            </a>
        </div>
    </div>
    <div class="card-body">
        <form method="GET" action="{{ route('tickets.index') }}" class="row">
            <div class="col-md-6 mb-2">
                <input type="text" name="search" class="form-control" placeholder="ค้นหา เลข Ticket, รหัสทรัพย์สิน..." value="{{ request('search') }}">
            </div>
            <div class="col-md-4 mb-2">
                <select name="status" class="form-control">
                    <option value="">-- สถานะทั้งหมด --</option>
                    <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>รอดำเนินการ</option>
                    <option value="in_progress" {{ request('status') == 'in_progress' ? 'selected' : '' }}>กำลังดำเนินการ</option>
                    <option value="resolved" {{ request('status') == 'resolved' ? 'selected' : '' }}>แก้ไขแล้ว</option>
                    <option value="closed" {{ request('status') == 'closed' ? 'selected' : '' }}>ปิดแล้ว</option>
                </select>
            </div>
            <div class="col-md-2 mb-2">
                <button type="submit" class="btn btn-info btn-block"><i class="fas fa-search mr-1"></i> ค้นหา</button>
            </div>
        </form>
    </div>
</div>

<div class="card">
    <div class="card-header">
        <h3 class="card-title">รายการ Ticket ({{ $tickets->total() }} รายการ)</h3>
    </div>
    <div class="card-body table-responsive p-0">
        <table class="table table-hover table-striped">
            <thead>
                <tr>
                    <th style="width: 50px">#</th>
                    <th>เลข Ticket</th>
                    <th>รหัสทรัพย์สิน</th>
                    <th>รายละเอียด</th>
                    <th>สถานะ</th>
                    <th>ผู้รับผิดชอบ</th>
                    <th>วันที่แจ้ง</th>
                    <th style="width: 120px">จัดการ</th>
                </tr>
            </thead>
            <tbody>
                @forelse($tickets as $index => $ticket)
                <tr>
                    <td>{{ $tickets->firstItem() + $index }}</td>
                    <td><strong>{{ $ticket->ticket_number }}</strong></td>
                    <td>
                        <a href="{{ route('assets.show', $ticket->asset_id) }}">{{ $ticket->asset->asset_id ?? '-' }}</a>
                    </td>
                    <td>{{ Str::limit($ticket->issue_description, 40) }}</td>
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
                    <td>{{ $ticket->created_at->format('d/m/Y H:i') }}</td>
                    <td>
                        <div class="btn-group btn-group-sm">
                            <a href="{{ route('tickets.show', $ticket) }}" class="btn btn-info" title="ดู">
                                <i class="fas fa-eye"></i>
                            </a>
                            <a href="{{ route('tickets.edit', $ticket) }}" class="btn btn-warning" title="แก้ไข">
                                <i class="fas fa-edit"></i>
                            </a>
                            @if($ticket->status === 'pending')
                            <form action="{{ route('tickets.assign', $ticket) }}" method="POST" class="d-inline">
                                @csrf
                                <input type="hidden" name="assigned_to" value="{{ auth()->id() }}">
                                <button type="submit" class="btn btn-success btn-sm" title="รับเคส">
                                    <i class="fas fa-hand-paper"></i>
                                </button>
                            </form>
                            @endif
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="8" class="text-center text-muted py-4">ไม่พบข้อมูล Ticket</td>
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
