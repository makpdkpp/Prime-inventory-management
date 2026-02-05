@extends('layouts.adminlte')

@section('title', 'ประเภททรัพย์สิน')
@section('page-title', 'ประเภททรัพย์สิน')

@section('breadcrumb')
<li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
<li class="breadcrumb-item active">ประเภททรัพย์สิน</li>
@endsection

@section('content')
<div class="card">
    <div class="card-header">
        <h3 class="card-title">รายการประเภททรัพย์สิน</h3>
        <div class="card-tools">
            <a href="{{ route('admin.asset-types.create') }}" class="btn btn-primary btn-sm">
                <i class="fas fa-plus mr-1"></i> เพิ่มประเภท
            </a>
        </div>
    </div>
    <div class="card-body table-responsive p-0">
        <table class="table table-hover table-striped">
            <thead>
                <tr>
                    <th style="width: 50px">#</th>
                    <th>ชื่อประเภท</th>
                    <th>รายละเอียด</th>
                    <th>จำนวนทรัพย์สิน</th>
                    <th style="width: 120px">จัดการ</th>
                </tr>
            </thead>
            <tbody>
                @forelse($assetTypes as $index => $type)
                <tr>
                    <td>{{ $assetTypes->firstItem() + $index }}</td>
                    <td><strong>{{ $type->name }}</strong></td>
                    <td>{{ $type->description ?? '-' }}</td>
                    <td><span class="badge badge-info">{{ $type->assets_count }}</span></td>
                    <td>
                        <div class="btn-group btn-group-sm">
                            <a href="{{ route('admin.asset-types.edit', $type) }}" class="btn btn-warning" title="แก้ไข">
                                <i class="fas fa-edit"></i>
                            </a>
                            <form action="{{ route('admin.asset-types.destroy', $type) }}" method="POST" class="d-inline" onsubmit="return confirm('ยืนยันการลบ?')">
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
                    <td colspan="5" class="text-center text-muted py-4">ไม่พบข้อมูล</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="card-footer">
        {{ $assetTypes->links() }}
    </div>
</div>
@endsection
