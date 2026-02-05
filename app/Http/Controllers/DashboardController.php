<?php

namespace App\Http\Controllers;

use App\Models\Asset;
use App\Models\Ticket;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $totalAssets = Asset::count();
        $activeAssets = Asset::whereHas('status', function($q) {
            $q->where('name', 'ใช้งานปกติ');
        })->count();
        $repairingAssets = Asset::whereHas('status', function($q) {
            $q->where('name', 'กำลังซ่อม');
        })->count();
        $pendingTickets = Ticket::where('status', 'pending')->count();
        
        $expiringWarrantyCount = Asset::warrantyExpiringSoon()->count();
        $expiredWarrantyCount = Asset::warrantyExpired()->count();
        
        $recentAssets = Asset::with(['assetType', 'status'])
            ->latest()
            ->take(5)
            ->get();
            
        $recentTickets = Ticket::with(['asset'])
            ->latest()
            ->take(5)
            ->get();
        
        $expiringWarrantyAssets = Asset::with(['assetType', 'status'])
            ->warrantyExpiringSoon()
            ->orderBy('warranty_expiry_date')
            ->take(5)
            ->get();

        return view('dashboard', compact(
            'totalAssets',
            'activeAssets',
            'repairingAssets',
            'pendingTickets',
            'expiringWarrantyCount',
            'expiredWarrantyCount',
            'recentAssets',
            'recentTickets',
            'expiringWarrantyAssets'
        ));
    }
}
