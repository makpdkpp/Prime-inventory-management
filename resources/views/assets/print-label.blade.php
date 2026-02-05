<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <title>พิมพ์สติ๊กเกอร์ - {{ $asset->asset_id }}</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        body {
            font-family: 'Sarabun', sans-serif;
            font-size: 12px;
        }
        .label {
            width: 60mm;
            height: 40mm;
            padding: 3mm;
            border: 1px solid #000;
            display: flex;
            align-items: center;
        }
        .qr-section {
            width: 35mm;
            text-align: center;
        }
        .qr-section svg {
            width: 30mm;
            height: 30mm;
        }
        .info-section {
            flex: 1;
            padding-left: 2mm;
            font-size: 10px;
        }
        .asset-id {
            font-size: 14px;
            font-weight: bold;
            margin-bottom: 2mm;
        }
        .info-row {
            margin-bottom: 1mm;
        }
        @media print {
            body {
                -webkit-print-color-adjust: exact;
                print-color-adjust: exact;
            }
            .no-print {
                display: none;
            }
            .label {
                border: 1px solid #000;
            }
        }
    </style>
</head>
<body>
    <div class="no-print" style="padding: 20px; margin-bottom: 20px; background: #f5f5f5;">
        <button onclick="window.print()" style="padding: 10px 20px; font-size: 16px; cursor: pointer;">
            🖨️ พิมพ์สติ๊กเกอร์
        </button>
        <button onclick="window.close()" style="padding: 10px 20px; font-size: 16px; cursor: pointer; margin-left: 10px;">
            ✕ ปิด
        </button>
        <p style="margin-top: 10px; color: #666;">ขนาดสติ๊กเกอร์: 60mm x 40mm</p>
    </div>
    
    <div class="label">
        <div class="qr-section">
            {!! QrCode::size(100)->generate(route('assets.show', $asset)) !!}
        </div>
        <div class="info-section">
            <div class="asset-id">{{ $asset->asset_id }}</div>
            <div class="info-row"><strong>ประเภท:</strong> {{ $asset->assetType->name ?? '-' }}</div>
            <div class="info-row"><strong>ยี่ห้อ:</strong> {{ $asset->brand ?? '-' }}</div>
            <div class="info-row"><strong>รุ่น:</strong> {{ $asset->model ?? '-' }}</div>
            <div class="info-row"><strong>S/N:</strong> {{ Str::limit($asset->serial_number, 15) ?? '-' }}</div>
        </div>
    </div>
</body>
</html>
