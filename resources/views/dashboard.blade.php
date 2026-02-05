@extends('layouts.adminlte')

@section('title', 'Dashboard')
@section('page-title', 'Dashboard')

@section('breadcrumb')
<li class="breadcrumb-item active">Dashboard</li>
@endsection

@section('content')
<!-- Info boxes -->
<div class="row">
    <div class="col-12 col-sm-6 col-md-3">
        <div class="info-box">
            <span class="info-box-icon bg-info elevation-1"><i class="fas fa-box"></i></span>
            <div class="info-box-content">
                <span class="info-box-text">ทรัพย์สินทั้งหมด</span>
                <span class="info-box-number">{{ $totalAssets ?? 0 }}</span>
            </div>
        </div>
    </div>
    
    <div class="col-12 col-sm-6 col-md-3">
        <div class="info-box">
            <span class="info-box-icon bg-success elevation-1"><i class="fas fa-check-circle"></i></span>
            <div class="info-box-content">
                <span class="info-box-text">ใช้งานปกติ</span>
                <span class="info-box-number">{{ $activeAssets ?? 0 }}</span>
            </div>
        </div>
    </div>
    
    <div class="col-12 col-sm-6 col-md-3">
        <div class="info-box">
            <span class="info-box-icon bg-warning elevation-1"><i class="fas fa-tools"></i></span>
            <div class="info-box-content">
                <span class="info-box-text">กำลังซ่อม</span>
                <span class="info-box-number">{{ $repairingAssets ?? 0 }}</span>
            </div>
        </div>
    </div>
    
    <div class="col-12 col-sm-6 col-md-3">
        <div class="info-box">
            <span class="info-box-icon bg-danger elevation-1"><i class="fas fa-ticket-alt"></i></span>
            <div class="info-box-content">
                <span class="info-box-text">Ticket รอดำเนินการ</span>
                <span class="info-box-number">{{ $pendingTickets ?? 0 }}</span>
            </div>
        </div>
    </div>
</div>

<!-- Warranty Status -->
<div class="row">
    <div class="col-12 col-sm-6 col-md-3">
        <div class="info-box">
            <span class="info-box-icon bg-warning elevation-1"><i class="fas fa-clock"></i></span>
            <div class="info-box-content">
                <span class="info-box-text">ใกล้หมดประกัน (30 วัน)</span>
                <span class="info-box-number">{{ $expiringWarrantyCount ?? 0 }}</span>
            </div>
        </div>
    </div>
    
    <div class="col-12 col-sm-6 col-md-3">
        <div class="info-box">
            <span class="info-box-icon bg-secondary elevation-1"><i class="fas fa-exclamation-triangle"></i></span>
            <div class="info-box-content">
                <span class="info-box-text">หมดประกันแล้ว</span>
                <span class="info-box-number">{{ $expiredWarrantyCount ?? 0 }}</span>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <!-- Recent Assets -->
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title"><i class="fas fa-box mr-2"></i>ทรัพย์สินล่าสุด</h3>
                <div class="card-tools">
                    <a href="{{ route('assets.index') }}" class="btn btn-tool">
                        <i class="fas fa-list"></i> ดูทั้งหมด
                    </a>
                </div>
            </div>
            <div class="card-body p-0">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>รหัส</th>
                            <th>ประเภท</th>
                            <th>ยี่ห้อ/รุ่น</th>
                            <th>สถานะ</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($recentAssets ?? [] as $asset)
                        <tr>
                            <td>{{ $asset->asset_id }}</td>
                            <td>{{ $asset->assetType->name ?? '-' }}</td>
                            <td>{{ $asset->brand }} {{ $asset->model }}</td>
                            <td>
                                <span class="badge" style="background-color: {{ $asset->status->color ?? '#6c757d' }}">
                                    {{ $asset->status->name ?? '-' }}
                                </span>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="4" class="text-center text-muted">ไม่มีข้อมูล</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    
    <!-- Recent Tickets -->
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title"><i class="fas fa-ticket-alt mr-2"></i>Ticket ล่าสุด</h3>
                <div class="card-tools">
                    <a href="{{ route('tickets.index') }}" class="btn btn-tool">
                        <i class="fas fa-list"></i> ดูทั้งหมด
                    </a>
                </div>
            </div>
            <div class="card-body p-0">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>เลข Ticket</th>
                            <th>รหัสทรัพย์สิน</th>
                            <th>สถานะ</th>
                            <th>วันที่แจ้ง</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($recentTickets ?? [] as $ticket)
                        <tr>
                            <td>{{ $ticket->ticket_number }}</td>
                            <td>{{ $ticket->asset->asset_id ?? '-' }}</td>
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
                                    @default
                                        <span class="badge badge-secondary">{{ $ticket->status }}</span>
                                @endswitch
                            </td>
                            <td>{{ $ticket->created_at->format('d/m/Y') }}</td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="4" class="text-center text-muted">ไม่มีข้อมูล</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Expiring Warranty Assets -->
@if(isset($expiringWarrantyAssets) && $expiringWarrantyAssets->count() > 0)
<div class="row">
    <div class="col-12">
        <div class="card card-warning">
            <div class="card-header">
                <h3 class="card-title"><i class="fas fa-exclamation-triangle mr-2"></i>อุปกรณ์ใกล้หมดประกัน (30 วัน)</h3>
                <div class="card-tools">
                    <a href="{{ route('assets.index', ['warranty' => 'expiring_soon']) }}" class="btn btn-tool">
                        <i class="fas fa-list"></i> ดูทั้งหมด
                    </a>
                </div>
            </div>
            <div class="card-body p-0">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>รหัส</th>
                            <th>ประเภท</th>
                            <th>ยี่ห้อ/รุ่น</th>
                            <th>วันหมดประกัน</th>
                            <th>เหลือ</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($expiringWarrantyAssets as $asset)
                        <tr>
                            <td><a href="{{ route('assets.show', $asset) }}">{{ $asset->asset_id }}</a></td>
                            <td>{{ $asset->assetType->name ?? '-' }}</td>
                            <td>{{ $asset->brand }} {{ $asset->model }}</td>
                            <td>{{ $asset->warranty_expiry_date->format('d/m/Y') }}</td>
                            <td>
                                <span class="badge badge-warning">
                                    {{ $asset->warranty_expiry_date->diffInDays(now()) }} วัน
                                </span>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endif

<!-- Quick Actions -->
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title"><i class="fas fa-bolt mr-2"></i>ทางลัด</h3>
            </div>
            <div class="card-body">
                <a href="{{ route('assets.create') }}" class="btn btn-primary mr-2 mb-2">
                    <i class="fas fa-plus mr-1"></i> เพิ่มทรัพย์สิน
                </a>
                <a href="{{ route('tickets.create') }}" class="btn btn-warning mr-2 mb-2">
                    <i class="fas fa-ticket-alt mr-1"></i> สร้าง Ticket
                </a>
                <a href="{{ route('qrcode.scanner') }}" class="btn btn-info mr-2 mb-2">
                    <i class="fas fa-qrcode mr-1"></i> Scan QR Code
                </a>
                @if(Auth::user() && Auth::user()->role === 'admin')
                <a href="{{ route('admin.reports.assets') }}" class="btn btn-success mr-2 mb-2">
                    <i class="fas fa-file-excel mr-1"></i> Export รายงาน
                </a>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
