@extends('layouts.adminlte')

@section('title', 'สร้าง Ticket')
@section('page-title', 'สร้าง Ticket แจ้งซ่อม')

@section('breadcrumb')
<li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
<li class="breadcrumb-item"><a href="{{ route('tickets.index') }}">Ticket</a></li>
<li class="breadcrumb-item active">สร้าง Ticket</li>
@endsection

@section('content')
<div class="row">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">ข้อมูล Ticket</h3>
            </div>
            <form action="{{ route('tickets.store') }}" method="POST">
                @csrf
                <div class="card-body">
                    <div class="form-group">
                        <label for="asset_id">ทรัพย์สิน <span class="text-danger">*</span></label>
                        <select name="asset_id" id="asset_id" class="form-control @error('asset_id') is-invalid @enderror" required>
                            <option value="">-- เลือกทรัพย์สิน --</option>
                            @foreach($assets as $asset)
                            <option value="{{ $asset->id }}" {{ (old('asset_id') == $asset->id || ($selectedAsset && $selectedAsset->id == $asset->id)) ? 'selected' : '' }}>
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
                        <textarea name="issue_description" id="issue_description" rows="5" 
                                  class="form-control @error('issue_description') is-invalid @enderror" 
                                  placeholder="อธิบายปัญหาที่พบ..." required>{{ old('issue_description') }}</textarea>
                        @error('issue_description')
                        <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>
                </div>
                <div class="card-footer">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save mr-1"></i> สร้าง Ticket
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
