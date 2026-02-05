@extends('layouts.adminlte')

@section('title', 'Scan QR Code')
@section('page-title', 'Scan QR Code')

@section('breadcrumb')
<li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
<li class="breadcrumb-item active">Scan QR Code</li>
@endsection

@push('styles')
<style>
    #reader {
        width: 100%;
        max-width: 500px;
        margin: 0 auto;
    }
    #reader video {
        border-radius: 8px;
    }
    .scan-result {
        padding: 15px;
        background: #d4edda;
        border-radius: 8px;
        margin-top: 15px;
    }
</style>
@endpush

@section('content')
<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title"><i class="fas fa-qrcode mr-2"></i>สแกน QR Code ทรัพย์สิน</h3>
            </div>
            <div class="card-body">
                <div class="text-center mb-3">
                    <p class="text-muted">กรุณาหันกล้องไปที่ QR Code บนทรัพย์สิน</p>
                </div>
                
                <div id="reader"></div>
                
                <div id="scan-result" class="scan-result d-none">
                    <h5><i class="fas fa-check-circle text-success mr-2"></i>พบทรัพย์สิน!</h5>
                    <p id="result-text" class="mb-2"></p>
                    <a id="result-link" href="#" class="btn btn-primary">
                        <i class="fas fa-eye mr-1"></i> ดูรายละเอียด
                    </a>
                </div>
                
                <div id="scan-error" class="alert alert-danger d-none mt-3">
                    <i class="fas fa-exclamation-circle mr-2"></i>
                    <span id="error-text"></span>
                </div>
                
                <hr>
                
                <div class="text-center">
                    <p class="text-muted mb-2">หรือค้นหาด้วยรหัสทรัพย์สิน</p>
                    <form id="manual-search" class="form-inline justify-content-center">
                        <div class="input-group" style="max-width: 300px;">
                            <input type="text" id="asset-id-input" class="form-control" placeholder="รหัสทรัพย์สิน เช่น AST-2024-0001">
                            <div class="input-group-append">
                                <button type="submit" class="btn btn-info">
                                    <i class="fas fa-search"></i>
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://unpkg.com/html5-qrcode@2.3.8/html5-qrcode.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const html5QrCode = new Html5Qrcode("reader");
    const config = { fps: 10, qrbox: { width: 250, height: 250 } };
    
    function onScanSuccess(decodedText, decodedResult) {
        html5QrCode.stop();
        
        // Check if it's a URL from our system
        if (decodedText.includes('/assets/')) {
            window.location.href = decodedText;
        } else {
            // Try to lookup by asset ID
            lookupAsset(decodedText);
        }
    }
    
    function onScanFailure(error) {
        // Ignore scan failures
    }
    
    // Start scanning
    html5QrCode.start(
        { facingMode: "environment" },
        config,
        onScanSuccess,
        onScanFailure
    ).catch((err) => {
        document.getElementById('scan-error').classList.remove('d-none');
        document.getElementById('error-text').textContent = 'ไม่สามารถเปิดกล้องได้: ' + err;
    });
    
    // Manual search
    document.getElementById('manual-search').addEventListener('submit', function(e) {
        e.preventDefault();
        const assetId = document.getElementById('asset-id-input').value.trim();
        if (assetId) {
            lookupAsset(assetId);
        }
    });
    
    function lookupAsset(assetId) {
        fetch('{{ route("qrcode.lookup") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({ asset_id: assetId })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                window.location.href = data.redirect;
            } else {
                document.getElementById('scan-error').classList.remove('d-none');
                document.getElementById('error-text').textContent = data.message || 'ไม่พบทรัพย์สินนี้';
            }
        })
        .catch(error => {
            document.getElementById('scan-error').classList.remove('d-none');
            document.getElementById('error-text').textContent = 'เกิดข้อผิดพลาดในการค้นหา';
        });
    }
});
</script>
@endpush
