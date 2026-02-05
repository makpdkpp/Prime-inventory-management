@extends('layouts.adminlte')

@section('title', 'QR Code')
@section('page-title', 'QR Code - ' . $asset->asset_id)

@section('breadcrumb')
<li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
<li class="breadcrumb-item"><a href="{{ route('assets.index') }}">ทรัพย์สิน</a></li>
<li class="breadcrumb-item active">QR Code</li>
@endsection

@section('content')
<div class="row justify-content-center">
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">QR Code สำหรับทรัพย์สิน</h3>
            </div>
            <div class="card-body text-center">
                <div class="mb-3">
                    {!! QrCode::size(250)->generate(route('assets.show', $asset)) !!}
                </div>
                <h4>{{ $asset->asset_id }}</h4>
                <p class="text-muted">{{ $asset->brand }} {{ $asset->model }}</p>
                <hr>
                <a href="{{ route('assets.print-label', $asset) }}" class="btn btn-primary" target="_blank">
                    <i class="fas fa-print mr-1"></i> พิมพ์สติ๊กเกอร์
                </a>
                <a href="{{ route('assets.show', $asset) }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left mr-1"></i> กลับ
                </a>
            </div>
        </div>
    </div>
</div>
@endsection
