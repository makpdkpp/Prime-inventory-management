@extends('layouts.adminlte')

@section('title', 'รายการทรัพย์สิน')
@section('page-title', 'รายการทรัพย์สิน')

@section('breadcrumb')
<li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
<li class="breadcrumb-item active">ทรัพย์สิน</li>
@endsection

@section('content')
<div class="card">
    <div class="card-header">
        <h3 class="card-title">ค้นหาและกรอง</h3>
        <div class="card-tools">
            <a href="{{ route('assets.create') }}" class="btn btn-primary btn-sm">
                <i class="fas fa-plus mr-1"></i> เพิ่มทรัพย์สิน
            </a>
        </div>
    </div>
    <div class="card-body">
        <form method="GET" action="{{ route('assets.index') }}" class="row">
            <div class="col-md-4 mb-2">
                <input type="text" name="search" class="form-control" placeholder="ค้นหา รหัส, ยี่ห้อ, รุ่น, Serial..." value="{{ request('search') }}">
            </div>
            <div class="col-md-3 mb-2">
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
        </form>
    </div>
</div>

<div class="card">
    <div class="card-header">
        <h3 class="card-title">รายการทรัพย์สิน ({{ $assets->total() }} รายการ)</h3>
    </div>
    <div class="card-body table-responsive p-0">
        <table class="table table-hover table-striped">
            <thead>
                <tr>
                    <th style="width: 50px">#</th>
                    <th>รหัสทรัพย์สิน</th>
                    <th>ประเภท</th>
                    <th>ยี่ห้อ/รุ่น</th>
                    <th>Serial Number</th>
                    <th>เจ้าของ</th>
                    <th>สถานะ</th>
                    <th>ประกัน</th>
                    <th style="width: 150px">จัดการ</th>
                </tr>
            </thead>
            <tbody>
                @forelse($assets as $index => $asset)
                <tr>
                    <td>{{ $assets->firstItem() + $index }}</td>
                    <td><strong>{{ $asset->asset_id }}</strong></td>
                    <td>{{ $asset->assetType->name ?? '-' }}</td>
                    <td>{{ $asset->brand }} {{ $asset->model }}</td>
                    <td>{{ $asset->serial_number ?? '-' }}</td>
                    <td>{{ $asset->owner_name ?? '-' }}</td>
                    <td>
                        <span class="badge" style="background-color: {{ $asset->status->color ?? '#6c757d' }}; color: #fff;">
                            {{ $asset->status->name ?? '-' }}
                        </span>
                    </td>
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
                            <span class="text-muted">-</span>
                        @endif
                    </td>
                    <td>
                        <div class="btn-group btn-group-sm">
                            <a href="{{ route('assets.show', $asset) }}" class="btn btn-info" title="ดู">
                                <i class="fas fa-eye"></i>
                            </a>
                            <a href="{{ route('assets.edit', $asset) }}" class="btn btn-warning" title="แก้ไข">
                                <i class="fas fa-edit"></i>
                            </a>
                            <a href="{{ route('assets.qrcode', $asset) }}" class="btn btn-secondary" title="QR Code">
                                <i class="fas fa-qrcode"></i>
                            </a>
                            <form action="{{ route('assets.destroy', $asset) }}" method="POST" class="d-inline" onsubmit="return confirm('ยืนยันการลบ?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-sm" title="ลบ">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="9" class="text-center text-muted py-4">ไม่พบข้อมูลทรัพย์สิน</td>
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
