@extends('layouts.adminlte')

@section('title', 'รายละเอียดทรัพย์สิน')
@section('page-title', 'รายละเอียดทรัพย์สิน')

@section('breadcrumb')
<li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
<li class="breadcrumb-item"><a href="{{ route('assets.index') }}">ทรัพย์สิน</a></li>
<li class="breadcrumb-item active">{{ $asset->asset_id }}</li>
@endsection

@section('content')
<div class="row">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">ข้อมูลทรัพย์สิน</h3>
                <div class="card-tools">
                    <a href="{{ route('assets.edit', $asset) }}" class="btn btn-warning btn-sm">
                        <i class="fas fa-edit mr-1"></i> แก้ไข
                    </a>
                    <a href="{{ route('assets.qrcode', $asset) }}" class="btn btn-secondary btn-sm">
                        <i class="fas fa-qrcode mr-1"></i> QR Code
                    </a>
                </div>
            </div>
            <div class="card-body">
                <table class="table table-bordered">
                    <tr>
                        <th style="width: 200px">รหัสทรัพย์สิน</th>
                        <td><strong>{{ $asset->asset_id }}</strong></td>
                    </tr>
                    <tr>
                        <th>ประเภท</th>
                        <td>{{ $asset->assetType->name ?? '-' }}</td>
                    </tr>
                    <tr>
                        <th>ยี่ห้อ</th>
                        <td>{{ $asset->brand ?? '-' }}</td>
                    </tr>
                    <tr>
                        <th>รุ่น</th>
                        <td>{{ $asset->model ?? '-' }}</td>
                    </tr>
                    <tr>
                        <th>Serial Number</th>
                        <td>{{ $asset->serial_number ?? '-' }}</td>
                    </tr>
                    <tr>
                        <th>สถานะ</th>
                        <td>
                            <span class="badge" style="background-color: {{ $asset->status->color ?? '#6c757d' }}; color: #fff;">
                                {{ $asset->status->name ?? '-' }}
                            </span>
                        </td>
                    </tr>
                    <tr>
                        <th>เจ้าของทรัพย์สิน</th>
                        <td>{{ $asset->owner_name ?? '-' }}</td>
                    </tr>
                    <tr>
                        <th>วันที่ซื้อ</th>
                        <td>{{ $asset->purchase_date ? $asset->purchase_date->format('d/m/Y') : '-' }}</td>
                    </tr>
                    <tr>
                        <th>วันหมดประกัน</th>
                        <td>
                            @if($asset->warranty_expiry_date)
                                {{ $asset->warranty_expiry_date->format('d/m/Y') }}
                                @switch($asset->warranty_status)
                                    @case('valid')
                                        <span class="badge badge-success ml-2">ยังไม่หมดประกัน</span>
                                        @break
                                    @case('expiring_soon')
                                        <span class="badge badge-warning ml-2">ใกล้หมดประกัน</span>
                                        @break
                                    @case('expired')
                                        <span class="badge badge-danger ml-2">หมดประกันแล้ว</span>
                                        @break
                                @endswitch
                            @else
                                -
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <th>ผู้บันทึก</th>
                        <td>{{ $asset->creator->name ?? '-' }}</td>
                    </tr>
                    <tr>
                        <th>วันที่บันทึก</th>
                        <td>{{ $asset->created_at->format('d/m/Y H:i') }}</td>
                    </tr>
                    <tr>
                        <th>วันที่แก้ไขล่าสุด</th>
                        <td>{{ $asset->updated_at->format('d/m/Y H:i') }}</td>
                    </tr>
                </table>
            </div>
        </div>
        
        <!-- Ticket History -->
        <div class="card">
            <div class="card-header">
                <h3 class="card-title"><i class="fas fa-ticket-alt mr-2"></i>ประวัติ Ticket แจ้งซ่อม</h3>
                <div class="card-tools">
                    <a href="{{ route('tickets.create', ['asset_id' => $asset->id]) }}" class="btn btn-warning btn-sm">
                        <i class="fas fa-plus mr-1"></i> สร้าง Ticket
                    </a>
                </div>
            </div>
            <div class="card-body table-responsive p-0">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>เลข Ticket</th>
                            <th>รายละเอียด</th>
                            <th>สถานะ</th>
                            <th>ผู้รับผิดชอบ</th>
                            <th>วันที่แจ้ง</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($asset->tickets as $ticket)
                        <tr>
                            <td>
                                <a href="{{ route('tickets.show', $ticket) }}">{{ $ticket->ticket_number }}</a>
                            </td>
                            <td>{{ Str::limit($ticket->issue_description, 50) }}</td>
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
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="text-center text-muted">ไม่มีประวัติ Ticket</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    
    <div class="col-md-4">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title"><i class="fas fa-qrcode mr-2"></i>QR Code</h3>
            </div>
            <div class="card-body text-center">
                <div id="qrcode-container">
                    {!! QrCode::size(180)->generate(route('assets.show', $asset)) !!}
                </div>
                <p class="mt-2 text-muted">{{ $asset->asset_id }}</p>
                <a href="{{ route('assets.print-label', $asset) }}" class="btn btn-primary btn-sm" target="_blank">
                    <i class="fas fa-print mr-1"></i> พิมพ์สติ๊กเกอร์
                </a>
            </div>
        </div>
    </div>
</div>
@endsection
