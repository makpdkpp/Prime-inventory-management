<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Asset;
use App\Models\Ticket;
use App\Models\AssetType;
use App\Models\AssetStatus;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\AssetsExport;
use App\Exports\TicketsExport;

class ReportController extends Controller
{
    public function assets(Request $request)
    {
        $query = Asset::with(['assetType', 'status', 'creator']);

        if ($request->filled('type')) {
            $query->where('asset_type_id', $request->type);
        }

        if ($request->filled('status')) {
            $query->where('status_id', $request->status);
        }

        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        if ($request->filled('warranty')) {
            switch ($request->warranty) {
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

        $assets = $query->latest()->paginate(20)->withQueryString();
        $assetTypes = AssetType::all();
        $assetStatuses = AssetStatus::all();

        $stats = [
            'total' => Asset::count(),
            'by_type' => AssetType::withCount('assets')->get(),
            'by_status' => AssetStatus::withCount('assets')->get(),
        ];

        return view('admin.reports.assets', compact('assets', 'assetTypes', 'assetStatuses', 'stats'));
    }

    public function tickets(Request $request)
    {
        $query = Ticket::with(['asset', 'assignee', 'creator']);

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        $tickets = $query->latest()->paginate(20)->withQueryString();

        $stats = [
            'total' => Ticket::count(),
            'pending' => Ticket::where('status', 'pending')->count(),
            'in_progress' => Ticket::where('status', 'in_progress')->count(),
            'resolved' => Ticket::where('status', 'resolved')->count(),
            'closed' => Ticket::where('status', 'closed')->count(),
        ];

        return view('admin.reports.tickets', compact('tickets', 'stats'));
    }

    public function exportAssets(Request $request)
    {
        return Excel::download(new AssetsExport($request), 'assets-' . date('Y-m-d') . '.xlsx');
    }

    public function exportTickets(Request $request)
    {
        return Excel::download(new TicketsExport($request), 'tickets-' . date('Y-m-d') . '.xlsx');
    }
}
