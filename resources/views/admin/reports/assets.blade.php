@extends('layouts.adminlte')

@section('title', 'รายงานทรัพย์สิน')
@section('page-title', 'รายงานทรัพย์สิน')

@section('breadcrumb')
<li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
<li class="breadcrumb-item active">รายงานทรัพย์สิน</li>
@endsection

@section('content')
<!-- Statistics -->
<div class="row">
    <div class="col-md-3">
        <div class="info-box bg-info">
            <span class="info-box-icon"><i class="fas fa-box"></i></span>
            <div class="info-box-content">
                <span class="info-box-text">ทรัพย์สินทั้งหมด</span>
                <span class="info-box-number">{{ $stats['total'] }}</span>
            </div>
        </div>
    </div>
    @foreach($stats['by_status'] as $status)
    <div class="col-md-3">
        <div class="info-box" style="background-color: {{ $status->color }}20;">
            <span class="info-box-icon" style="background-color: {{ $status->color }}; color: #fff;">
                <i class="fas fa-tag"></i>
            </span>
            <div class="info-box-content">
                <span class="info-box-text">{{ $status->name }}</span>
                <span class="info-box-number">{{ $status->assets_count }}</span>
            </div>
        </div>
    </div>
    @endforeach
</div>

<!-- Filter -->
<div class="card">
    <div class="card-header">
        <h3 class="card-title">กรองข้อมูล</h3>
    </div>
    <div class="card-body">
        <form method="GET" action="{{ route('admin.reports.assets') }}" class="row">
            <div class="col-md-2 mb-2">
                <select name="type" class="form-control">
                    <option value="">-- ประเภททั้งหมด --</option>
                    @foreach($assetTypes as $type)
                    <option value="{{ $type->id }}" {{ request('type') == $type->id ? 'selected' : '' }}>{{ $type->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-2 mb-2">
                <select name="status" class="form-control">
                    <option value="">-- สถานะทั้งหมด --</option>
                    @foreach($assetStatuses as $status)
                    <option value="{{ $status->id }}" {{ request('status') == $status->id ? 'selected' : '' }}>{{ $status->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-2 mb-2">
                <input type="date" name="date_from" class="form-control" placeholder="จากวันที่" value="{{ request('date_from') }}">
            </div>
            <div class="col-md-2 mb-2">
                <input type="date" name="date_to" class="form-control" placeholder="ถึงวันที่" value="{{ request('date_to') }}">
            </div>
            <div class="col-md-2 mb-2">
                <select name="warranty" class="form-control">
                    <option value="">-- ประกันทั้งหมด --</option>
                    <option value="valid" {{ request('warranty') == 'valid' ? 'selected' : '' }}>ยังไม่หมดประกัน</option>
                    <option value="expiring_soon" {{ request('warranty') == 'expiring_soon' ? 'selected' : '' }}>ใกล้หมดประกัน</option>
                    <option value="expired" {{ request('warranty') == 'expired' ? 'selected' : '' }}>หมดประกันแล้ว</option>
                </select>
            </div>
            <div class="col-md-1 mb-2">
                <button type="submit" class="btn btn-info btn-block"><i class="fas fa-search"></i></button>
            </div>
            <div class="col-md-2 mb-2">
                <a href="{{ route('admin.reports.assets.export', request()->all()) }}" class="btn btn-success btn-block">
                    <i class="fas fa-file-excel mr-1"></i> Export Excel
                </a>
            </div>
        </form>
    </div>
</div>

<!-- Data Table -->
<div class="card">
    <div class="card-header">
        <h3 class="card-title">รายการทรัพย์สิน ({{ $assets->total() }} รายการ)</h3>
    </div>
    <div class="card-body table-responsive p-0">
        <table class="table table-hover table-striped">
            <thead>
                <tr>
                    <th>#</th>
                    <th>รหัสทรัพย์สิน</th>
                    <th>ประเภท</th>
                    <th>ยี่ห้อ/รุ่น</th>
                    <th>Serial Number</th>
                    <th>เจ้าของ</th>
                    <th>สถานะ</th>
                    <th>วันหมดประกัน</th>
                    <th>ประกัน</th>
                </tr>
            </thead>
            <tbody>
                @forelse($assets as $index => $asset)
                <tr>
                    <td>{{ $assets->firstItem() + $index }}</td>
                    <td><a href="{{ route('assets.show', $asset) }}">{{ $asset->asset_id }}</a></td>
                    <td>{{ $asset->assetType->name ?? '-' }}</td>
                    <td>{{ $asset->brand }} {{ $asset->model }}</td>
                    <td>{{ $asset->serial_number ?? '-' }}</td>
                    <td>{{ $asset->owner_name ?? '-' }}</td>
                    <td>
                        <span class="badge" style="background-color: {{ $asset->status->color ?? '#6c757d' }}; color: #fff;">
                            {{ $asset->status->name ?? '-' }}
                        </span>
                    </td>
                    <td>{{ $asset->warranty_expiry_date ? $asset->warranty_expiry_date->format('d/m/Y') : '-' }}</td>
                    <td>
                        @if($asset->warranty_expiry_date)
                            @switch($asset->warranty_status)
                                @case('valid')
                                    <span class="badge badge-success">ปกติ</span>
                                    @break
                                @case('expiring_soon')
                                    <span class="badge badge-warning">ใกล้หมด</span>
                                    @break
                                @case('expired')
                                    <span class="badge badge-danger">หมดแล้ว</span>
                                    @break
                            @endswitch
                        @else
                            -
                        @endif
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="9" class="text-center text-muted py-4">ไม่พบข้อมูล</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="card-footer">
        {{ $assets->links() }}
    </div>
</div>
@endsection
