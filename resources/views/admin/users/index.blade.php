@extends('layouts.adminlte')

@section('title', 'จัดการผู้ใช้')
@section('page-title', 'จัดการผู้ใช้')

@section('breadcrumb')
<li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
<li class="breadcrumb-item active">ผู้ใช้</li>
@endsection

@section('content')
<div class="card">
    <div class="card-header">
        <h3 class="card-title">รายชื่อผู้ใช้ ({{ $users->total() }} คน)</h3>
        <div class="card-tools">
            <a href="{{ route('admin.users.create') }}" class="btn btn-primary btn-sm">
                <i class="fas fa-plus mr-1"></i> เพิ่มผู้ใช้
            </a>
        </div>
    </div>
    <div class="card-body table-responsive p-0">
        <table class="table table-hover table-striped">
            <thead>
                <tr>
                    <th style="width: 50px">#</th>
                    <th>ชื่อ</th>
                    <th>อีเมล</th>
                    <th>สิทธิ์</th>
                    <th>วันที่สร้าง</th>
                    <th style="width: 150px">จัดการ</th>
                </tr>
            </thead>
            <tbody>
                @forelse($users as $index => $user)
                <tr>
                    <td>{{ $users->firstItem() + $index }}</td>
                    <td>{{ $user->name }}</td>
                    <td>{{ $user->email }}</td>
                    <td>
                        @if($user->role === 'admin')
                        <span class="badge badge-danger">Admin</span>
                        @else
                        <span class="badge badge-info">User</span>
                        @endif
                    </td>
                    <td>{{ $user->created_at->format('d/m/Y H:i') }}</td>
                    <td>
                        <div class="btn-group btn-group-sm">
                            <a href="{{ route('admin.users.edit', $user) }}" class="btn btn-warning" title="แก้ไข">
                                <i class="fas fa-edit"></i>
                            </a>
                            <button type="button" class="btn btn-info" data-toggle="modal" data-target="#resetPasswordModal{{ $user->id }}" title="รีเซ็ตรหัสผ่าน">
                                <i class="fas fa-key"></i>
                            </button>
                            @if($user->id !== auth()->id())
                            <form action="{{ route('admin.users.destroy', $user) }}" method="POST" class="d-inline" onsubmit="return confirm('ยืนยันการลบ?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-sm" title="ลบ">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>
                            @endif
                        </div>
                        
                        <!-- Reset Password Modal -->
                        <div class="modal fade" id="resetPasswordModal{{ $user->id }}" tabindex="-1">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <form action="{{ route('admin.users.reset-password', $user) }}" method="POST">
                                        @csrf
                                        <div class="modal-header">
                                            <h5 class="modal-title">รีเซ็ตรหัสผ่าน: {{ $user->name }}</h5>
                                            <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
                                        </div>
                                        <div class="modal-body">
                                            <div class="form-group">
                                                <label>รหัสผ่านใหม่</label>
                                                <input type="password" name="password" class="form-control" required minlength="6">
                                            </div>
                                            <div class="form-group">
                                                <label>ยืนยันรหัสผ่าน</label>
                                                <input type="password" name="password_confirmation" class="form-control" required>
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-dismiss="modal">ยกเลิก</button>
                                            <button type="submit" class="btn btn-primary">รีเซ็ตรหัสผ่าน</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="text-center text-muted py-4">ไม่พบข้อมูลผู้ใช้</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="card-footer">
        {{ $users->links() }}
    </div>
</div>
@endsection
