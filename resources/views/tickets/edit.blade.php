@extends('layouts.adminlte')

@section('title', 'แก้ไข Ticket')
@section('page-title', 'แก้ไข Ticket')

@section('breadcrumb')
<li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
<li class="breadcrumb-item"><a href="{{ route('tickets.index') }}">Ticket</a></li>
<li class="breadcrumb-item active">แก้ไข</li>
@endsection

@section('content')
<div class="row">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">แก้ไข Ticket: {{ $ticket->ticket_number }}</h3>
            </div>
            <form action="{{ route('tickets.update', $ticket) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="card-body">
                    <div class="form-group">
                        <label for="asset_id">ทรัพย์สิน <span class="text-danger">*</span></label>
                        <select name="asset_id" id="asset_id" class="form-control @error('asset_id') is-invalid @enderror" required>
                            <option value="">-- เลือกทรัพย์สิน --</option>
                            @foreach($assets as $asset)
                            <option value="{{ $asset->id }}" {{ old('asset_id', $ticket->asset_id) == $asset->id ? 'selected' : '' }}>
                                {{ $asset->asset_id }} - {{ $asset->brand }} {{ $asset->model }}
                            </option>
                            @endforeach
                        </select>
                        @error('asset_id')
                        <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>
                    
                    <div class="form-group">
                        <label for="issue_description">รายละเอียดปัญหา <span class="text-danger">*</span></label>
                        <textarea name="issue_description" id="issue_description" rows="4" 
                                  class="form-control @error('issue_description') is-invalid @enderror" required>{{ old('issue_description', $ticket->issue_description) }}</textarea>
                        @error('issue_description')
                        <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="status">สถานะ <span class="text-danger">*</span></label>
                                <select name="status" id="status" class="form-control @error('status') is-invalid @enderror" required>
                                    <option value="pending" {{ old('status', $ticket->status) == 'pending' ? 'selected' : '' }}>รอดำเนินการ</option>
                                    <option value="in_progress" {{ old('status', $ticket->status) == 'in_progress' ? 'selected' : '' }}>กำลังดำเนินการ</option>
                                    <option value="resolved" {{ old('status', $ticket->status) == 'resolved' ? 'selected' : '' }}>แก้ไขแล้ว</option>
                                    <option value="closed" {{ old('status', $ticket->status) == 'closed' ? 'selected' : '' }}>ปิดแล้ว</option>
                                </select>
                                @error('status')
                                <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="assigned_to">ผู้รับผิดชอบ</label>
                                <select name="assigned_to" id="assigned_to" class="form-control @error('assigned_to') is-invalid @enderror">
                                    <option value="">-- ไม่ระบุ --</option>
                                    @foreach($users as $user)
                                    <option value="{{ $user->id }}" {{ old('assigned_to', $ticket->assigned_to) == $user->id ? 'selected' : '' }}>{{ $user->name }}</option>
                                    @endforeach
                                </select>
                                @error('assigned_to')
                                <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label for="resolution_notes">บันทึกการแก้ไข</label>
                        <textarea name="resolution_notes" id="resolution_notes" rows="3" 
                                  class="form-control @error('resolution_notes') is-invalid @enderror">{{ old('resolution_notes', $ticket->resolution_notes) }}</textarea>
                        @error('resolution_notes')
                        <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>
                </div>
                <div class="card-footer">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save mr-1"></i> บันทึก
                    </button>
                    <a href="{{ route('tickets.index') }}" class="btn btn-secondary">
                        <i class="fas fa-times mr-1"></i> ยกเลิก
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
