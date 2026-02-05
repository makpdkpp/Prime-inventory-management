<?php

namespace App\Exports;

use App\Models\Asset;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class AssetsExport implements FromCollection, WithHeadings, WithMapping
{
    protected $request;

    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    public function collection()
    {
        $query = Asset::with(['assetType', 'status', 'creator']);

        if ($this->request->filled('type')) {
            $query->where('asset_type_id', $this->request->type);
        }

        if ($this->request->filled('status')) {
            $query->where('status_id', $this->request->status);
        }

        if ($this->request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $this->request->date_from);
        }

        if ($this->request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $this->request->date_to);
        }

        if ($this->request->filled('warranty')) {
            switch ($this->request->warranty) {
                case 'valid':
                    $query->warrantyValid();
                    break;
                case 'expiring_soon':
                    $query->warrantyExpiringSoon();
                    break;
                case 'expired':
                    $query->warrantyExpired();
                    break;
            }
        }

        return $query->latest()->get();
    }

    public function headings(): array
    {
        return [
            'รหัสทรัพย์สิน',
            'ประเภท',
            'ยี่ห้อ',
            'รุ่น',
            'Serial Number',
            'สถานะ',
            'เจ้าของ',
            'วันที่ซื้อ',
            'วันหมดประกัน',
            'สถานะประกัน',
            'ผู้บันทึก',
            'วันที่บันทึก',
        ];
    }

    public function map($asset): array
    {
        $warrantyStatus = '-';
        if ($asset->warranty_expiry_date) {
            switch ($asset->warranty_status) {
                case 'valid':
                    $warrantyStatus = 'ยังไม่หมดประกัน';
                    break;
                case 'expiring_soon':
                    $warrantyStatus = 'ใกล้หมดประกัน';
                    break;
                case 'expired':
                    $warrantyStatus = 'หมดประกันแล้ว';
                    break;
            }
        }

        return [
            $asset->asset_id,
            $asset->assetType->name ?? '-',
            $asset->brand ?? '-',
            $asset->model ?? '-',
            $asset->serial_number ?? '-',
            $asset->status->name ?? '-',
            $asset->owner_name ?? '-',
            $asset->purchase_date ? $asset->purchase_date->format('d/m/Y') : '-',
            $asset->warranty_expiry_date ? $asset->warranty_expiry_date->format('d/m/Y') : '-',
            $warrantyStatus,
            $asset->creator->name ?? '-',
            $asset->created_at->format('d/m/Y H:i'),
        ];
    }
}
