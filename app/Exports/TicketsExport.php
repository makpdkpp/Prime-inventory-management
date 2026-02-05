<?php

namespace App\Exports;

use App\Models\Ticket;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class TicketsExport implements FromCollection, WithHeadings, WithMapping
{
    protected $request;

    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    public function collection()
    {
        $query = Ticket::with(['asset', 'assignee', 'creator']);

        if ($this->request->filled('status')) {
            $query->where('status', $this->request->status);
        }

        if ($this->request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $this->request->date_from);
        }

        if ($this->request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $this->request->date_to);
        }

        return $query->latest()->get();
    }

    public function headings(): array
    {
        return [
            'เลข Ticket',
            'รหัสทรัพย์สิน',
            'รายละเอียดปัญหา',
            'สถานะ',
            'ผู้รับผิดชอบ',
            'ผู้แจ้ง',
            'วันที่แจ้ง',
            'วันที่แก้ไขสำเร็จ',
            'บันทึกการแก้ไข',
        ];
    }

    public function map($ticket): array
    {
        $statusMap = [
            'pending' => 'รอดำเนินการ',
            'in_progress' => 'กำลังดำเนินการ',
            'resolved' => 'แก้ไขแล้ว',
            'closed' => 'ปิดแล้ว',
        ];

        return [
            $ticket->ticket_number,
            $ticket->asset->asset_id ?? '-',
            $ticket->issue_description,
            $statusMap[$ticket->status] ?? $ticket->status,
            $ticket->assignee->name ?? '-',
            $ticket->creator->name ?? '-',
            $ticket->created_at->format('d/m/Y H:i'),
            $ticket->resolved_at ? $ticket->resolved_at->format('d/m/Y H:i') : '-',
            $ticket->resolution_notes ?? '-',
        ];
    }
}
