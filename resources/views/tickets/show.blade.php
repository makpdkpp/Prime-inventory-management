@extends('layouts.adminlte')

@section('title', 'รายละเอียด Ticket')
@section('page-title', 'รายละเอียด Ticket')

@section('breadcrumb')
<li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
<li class="breadcrumb-item"><a href="{{ route('tickets.index') }}">Ticket</a></li>
<li class="breadcrumb-item active">{{ $ticket->ticket_number }}</li>
@endsection

@section('content')
<div class="row">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">ข้อมูล Ticket: {{ $ticket->ticket_number }}</h3>
                <div class="card-tools">
                    <a href="{{ route('tickets.edit', $ticket) }}" class="btn btn-warning btn-sm">
                        <i class="fas fa-edit mr-1"></i> แก้ไข
                    </a>
                </div>
            </div>
            <div class="card-body">
                <table class="table table-bordered">
                    <tr>
                        <th style="width: 200px">เลข Ticket</th>
                        <td><strong>{{ $ticket->ticket_number }}</strong></td>
                    </tr>
                    <tr>
                        <th>ทรัพย์สิน</th>
                        <td>
                            <a href="{{ route('assets.show', $ticket->asset_id) }}">
                                {{ $ticket->asset->asset_id }} - {{ $ticket->asset->brand }} {{ $ticket->asset->model }}
                            </a>
                        </td>
                    </tr>
                    <tr>
                        <th>สถานะ</th>
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
                    </tr>
                    <tr>
                        <th>รายละเอียดปัญหา</th>
                        <td>{{ $ticket->issue_description }}</td>
                    </tr>
                    <tr>
                        <th>ผู้แจ้ง</th>
                        <td>{{ $ticket->creator->name ?? '-' }}</td>
                    </tr>
                    <tr>
                        <th>วันที่แจ้ง</th>
                        <td>{{ $ticket->created_at->format('d/m/Y H:i') }}</td>
                    </tr>
                    <tr>
                        <th>ผู้รับผิดชอบ</th>
                        <td>{{ $ticket->assignee->name ?? '-' }}</td>
                    </tr>
                    @if($ticket->resolved_at)
                    <tr>
                        <th>วันที่แก้ไขสำเร็จ</th>
                        <td>{{ $ticket->resolved_at->format('d/m/Y H:i') }}</td>
                    </tr>
                    @endif
                    @if($ticket->resolution_notes)
                    <tr>
                        <th>บันทึกการแก้ไข</th>
                        <td>{{ $ticket->resolution_notes }}</td>
                    </tr>
                    @endif
                </table>
            </div>
        </div>
        
        <!-- Actions -->
        @if($ticket->status === 'pending')
        <div class="card">
            <div class="card-header bg-success">
                <h3 class="card-title text-white">รับเคส</h3>
            </div>
            <div class="card-body">
                <form action="{{ route('tickets.assign', $ticket) }}" method="POST">
                    @csrf
                    <input type="hidden" name="assigned_to" value="{{ auth()->id() }}">
                    <button type="submit" class="btn btn-success">
                        <i class="fas fa-hand-paper mr-1"></i> รับเคสนี้
                    </button>
                </form>
            </div>
        </div>
        @endif
        
        @if($ticket->status === 'in_progress' && $ticket->assigned_to === auth()->id())
        <div class="card">
            <div class="card-header bg-primary">
                <h3 class="card-title text-white">บันทึกการแก้ไข</h3>
            </div>
            <div class="card-body">
                <form action="{{ route('tickets.resolve', $ticket) }}" method="POST">
                    @csrf
                    <div class="form-group">
                        <label for="resolution_notes">รายละเอียดการแก้ไข <span class="text-danger">*</span></label>
                        <textarea name="resolution_notes" id="resolution_notes" rows="4" class="form-control" 
                                  placeholder="อธิบายวิธีการแก้ไข..." required></textarea>
                    </div>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-check mr-1"></i> แก้ไขสำเร็จ
                    </button>
                </form>
            </div>
        </div>
        @endif
    </div>
    
    <div class="col-md-4">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title"><i class="fas fa-box mr-2"></i>ข้อมูลทรัพย์สิน</h3>
            </div>
            <div class="card-body">
                <p><strong>รหัส:</strong> {{ $ticket->asset->asset_id }}</p>
                <p><strong>ประเภท:</strong> {{ $ticket->asset->assetType->name ?? '-' }}</p>
                <p><strong>ยี่ห้อ:</strong> {{ $ticket->asset->brand ?? '-' }}</p>
                <p><strong>รุ่น:</strong> {{ $ticket->asset->model ?? '-' }}</p>
                <p><strong>สถานะ:</strong> 
                    <span class="badge" style="background-color: {{ $ticket->asset->status->color ?? '#6c757d' }}; color: #fff;">
                        {{ $ticket->asset->status->name ?? '-' }}
                    </span>
                </p>
                <a href="{{ route('assets.show', $ticket->asset_id) }}" class="btn btn-info btn-sm btn-block">
                    <i class="fas fa-eye mr-1"></i> ดูรายละเอียดทรัพย์สิน
                </a>
            </div>
        </div>
    </div>
</div>
@endsection
