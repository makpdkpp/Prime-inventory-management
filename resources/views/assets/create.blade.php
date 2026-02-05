@extends('layouts.adminlte')

@section('title', 'เพิ่มทรัพย์สิน')
@section('page-title', 'เพิ่มทรัพย์สิน')

@section('breadcrumb')
<li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
<li class="breadcrumb-item"><a href="{{ route('assets.index') }}">ทรัพย์สิน</a></li>
<li class="breadcrumb-item active">เพิ่มทรัพย์สิน</li>
@endsection

@section('content')
<div class="row">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">ข้อมูลทรัพย์สิน</h3>
            </div>
            <form action="{{ route('assets.store') }}" method="POST">
                @csrf
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="asset_id">รหัสทรัพย์สิน <span class="text-danger">*</span></label>
                                <input type="text" name="asset_id" id="asset_id" class="form-control @error('asset_id') is-invalid @enderror" 
                                       value="{{ old('asset_id', $nextAssetId) }}" required>
                                @error('asset_id')
                                <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="asset_type_id">ประเภท <span class="text-danger">*</span></label>
                                <select name="asset_type_id" id="asset_type_id" class="form-control @error('asset_type_id') is-invalid @enderror" required>
                                    <option value="">-- เลือกประเภท --</option>
                                    @foreach($assetTypes as $type)
                                    <option value="{{ $type->id }}" {{ old('asset_type_id') == $type->id ? 'selected' : '' }}>{{ $type->name }}</option>
                                    @endforeach
                                </select>
                                @error('asset_type_id')
                                <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="brand">ยี่ห้อ</label>
                                <input type="text" name="brand" id="brand" class="form-control @error('brand') is-invalid @enderror" 
                                       value="{{ old('brand') }}">
                                @error('brand')
                                <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="model">รุ่น</label>
                                <input type="text" name="model" id="model" class="form-control @error('model') is-invalid @enderror" 
                                       value="{{ old('model') }}">
                                @error('model')
                                <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="serial_number">Serial Number</label>
                                <input type="text" name="serial_number" id="serial_number" class="form-control @error('serial_number') is-invalid @enderror" 
                                       value="{{ old('serial_number') }}">
                                @error('serial_number')
                                <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="status_id">สถานะ <span class="text-danger">*</span></label>
                                <select name="status_id" id="status_id" class="form-control @error('status_id') is-invalid @enderror" required>
                                    <option value="">-- เลือกสถานะ --</option>
                                    @foreach($assetStatuses as $status)
                                    <option value="{{ $status->id }}" {{ old('status_id') == $status->id ? 'selected' : '' }}>{{ $status->name }}</option>
                                    @endforeach
                                </select>
                                @error('status_id')
                                <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label for="owner_name">เจ้าของทรัพย์สิน</label>
                        <input type="text" name="owner_name" id="owner_name" class="form-control @error('owner_name') is-invalid @enderror" 
                               value="{{ old('owner_name') }}">
                        @error('owner_name')
                        <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="purchase_date">วันที่ซื้อ</label>
                                <input type="date" name="purchase_date" id="purchase_date" class="form-control @error('purchase_date') is-invalid @enderror" 
                                       value="{{ old('purchase_date') }}">
                                @error('purchase_date')
                                <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="warranty_expiry_date">วันหมดประกัน</label>
                                <input type="date" name="warranty_expiry_date" id="warranty_expiry_date" class="form-control @error('warranty_expiry_date') is-invalid @enderror" 
                                       value="{{ old('warranty_expiry_date') }}">
                                @error('warranty_expiry_date')
                                <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-footer">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save mr-1"></i> บันทึก
                    </button>
                    <a href="{{ route('assets.index') }}" class="btn btn-secondary">
                        <i class="fas fa-times mr-1"></i> ยกเลิก
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
