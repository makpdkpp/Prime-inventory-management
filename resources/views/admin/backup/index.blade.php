@extends('layouts.adminlte')

@section('title', 'Backup/Restore')
@section('page-title', 'Backup/Restore ฐานข้อมูล')

@section('breadcrumb')
<li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
<li class="breadcrumb-item active">Backup/Restore</li>
@endsection

@section('content')
<div class="row">
    <div class="col-md-6">
        <div class="card card-primary">
            <div class="card-header">
                <h3 class="card-title"><i class="fas fa-download mr-2"></i>สำรองข้อมูล (Backup)</h3>
            </div>
            <div class="card-body">
                <p>คลิกปุ่มด้านล่างเพื่อสำรองฐานข้อมูลปัจจุบัน</p>
                <form action="{{ route('admin.backup.run') }}" method="POST">
                    @csrf
                    <button type="submit" class="btn btn-primary btn-lg">
                        <i class="fas fa-database mr-2"></i> สำรองข้อมูลเดี๋ยวนี้
                    </button>
                </form>
            </div>
        </div>
    </div>
    
    <div class="col-md-6">
        <div class="card card-warning">
            <div class="card-header">
                <h3 class="card-title"><i class="fas fa-upload mr-2"></i>กู้คืนข้อมูล (Restore)</h3>
            </div>
            <div class="card-body">
                <p class="text-danger"><strong>คำเตือน:</strong> การกู้คืนจะแทนที่ข้อมูลปัจจุบันทั้งหมด</p>
                <form action="{{ route('admin.backup.restore') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="form-group">
                        <label for="backup_file">เลือกไฟล์ Backup</label>
                        <input type="file" name="backup_file" id="backup_file" class="form-control-file" accept=".sql,.zip" required>
                    </div>
                    <button type="submit" class="btn btn-warning" onclick="return confirm('ยืนยันการกู้คืนข้อมูล? ข้อมูลปัจจุบันจะถูกแทนที่')">
                        <i class="fas fa-undo mr-2"></i> กู้คืนข้อมูล
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

<div class="card">
    <div class="card-header">
        <h3 class="card-title"><i class="fas fa-history mr-2"></i>ประวัติการ Backup/Restore</h3>
    </div>
    <div class="card-body table-responsive p-0">
        <table class="table table-hover table-striped">
            <thead>
                <tr>
                    <th style="width: 50px">#</th>
                    <th>ชื่อไฟล์</th>
                    <th>ประเภท</th>
                    <th>ผู้ดำเนินการ</th>
                    <th>วันที่</th>
                </tr>
            </thead>
            <tbody>
                @forelse($backups as $index => $backup)
                <tr>
                    <td>{{ $backups->firstItem() + $index }}</td>
                    <td>{{ $backup->filename }}</td>
                    <td>
                        @if($backup->type === 'backup')
                        <span class="badge badge-primary">Backup</span>
                        @else
                        <span class="badge badge-warning">Restore</span>
                        @endif
                    </td>
                    <td>{{ $backup->performer->name ?? '-' }}</td>
                    <td>{{ $backup->created_at->format('d/m/Y H:i') }}</td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="text-center text-muted py-4">ไม่มีประวัติ</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="card-footer">
        {{ $backups->links() }}
    </div>
</div>
@endsection
