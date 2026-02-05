@extends('layouts.adminlte')

@section('title', 'แก้ไขสถานะทรัพย์สิน')
@section('page-title', 'แก้ไขสถานะทรัพย์สิน')

@section('breadcrumb')
<li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
<li class="breadcrumb-item"><a href="{{ route('admin.asset-statuses.index') }}">สถานะทรัพย์สิน</a></li>
<li class="breadcrumb-item active">แก้ไข</li>
@endsection

@section('content')
<div class="row">
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">แก้ไขสถานะ: {{ $assetStatus->name }}</h3>
            </div>
            <form action="{{ route('admin.asset-statuses.update', $assetStatus) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="card-body">
                    <div class="form-group">
                        <label for="name">ชื่อสถานะ <span class="text-danger">*</span></label>
                        <input type="text" name="name" id="name" class="form-control @error('name') is-invalid @enderror" 
                               value="{{ old('name', $assetStatus->name) }}" required>
                        @error('name')
                        <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>
                    
                    <div class="form-group">
                        <label for="color">สี <span class="text-danger">*</span></label>
                        <input type="color" name="color" id="color" class="form-control @error('color') is-invalid @enderror" 
                               value="{{ old('color', $assetStatus->color) }}" style="height: 50px;" required>
                        @error('color')
                        <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>
                </div>
                <div class="card-footer">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save mr-1"></i> บันทึก
                    </button>
                    <a href="{{ route('admin.asset-statuses.index') }}" class="btn btn-secondary">
                        <i class="fas fa-times mr-1"></i> ยกเลิก
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
