@extends('layouts.adminlte')

@section('title', 'แก้ไขประเภททรัพย์สิน')
@section('page-title', 'แก้ไขประเภททรัพย์สิน')

@section('breadcrumb')
<li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
<li class="breadcrumb-item"><a href="{{ route('admin.asset-types.index') }}">ประเภททรัพย์สิน</a></li>
<li class="breadcrumb-item active">แก้ไข</li>
@endsection

@section('content')
<div class="row">
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">แก้ไขประเภท: {{ $assetType->name }}</h3>
            </div>
            <form action="{{ route('admin.asset-types.update', $assetType) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="card-body">
                    <div class="form-group">
                        <label for="name">ชื่อประเภท <span class="text-danger">*</span></label>
                        <input type="text" name="name" id="name" class="form-control @error('name') is-invalid @enderror" 
                               value="{{ old('name', $assetType->name) }}" required>
                        @error('name')
                        <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>
                    
                    <div class="form-group">
                        <label for="description">รายละเอียด</label>
                        <textarea name="description" id="description" rows="3" class="form-control @error('description') is-invalid @enderror">{{ old('description', $assetType->description) }}</textarea>
                        @error('description')
                        <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>
                </div>
                <div class="card-footer">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save mr-1"></i> บันทึก
                    </button>
                    <a href="{{ route('admin.asset-types.index') }}" class="btn btn-secondary">
                        <i class="fas fa-times mr-1"></i> ยกเลิก
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
