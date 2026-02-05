<?php

namespace App\Http\Controllers;

use App\Models\Asset;
use App\Models\AssetType;
use App\Models\AssetStatus;
use Illuminate\Http\Request;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class AssetController extends Controller
{
    public function index(Request $request)
    {
        $query = Asset::with(['assetType', 'status', 'creator']);

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('asset_id', 'like', "%{$search}%")
                  ->orWhere('brand', 'like', "%{$search}%")
                  ->orWhere('model', 'like', "%{$search}%")
                  ->orWhere('serial_number', 'like', "%{$search}%")
                  ->orWhere('owner_name', 'like', "%{$search}%");
            });
        }

        if ($request->filled('type')) {
            $query->where('asset_type_id', $request->type);
        }

        if ($request->filled('status')) {
            $query->where('status_id', $request->status);
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

        $assets = $query->latest()->paginate(15)->withQueryString();
        $assetTypes = AssetType::all();
        $assetStatuses = AssetStatus::all();

        return view('assets.index', compact('assets', 'assetTypes', 'assetStatuses'));
    }

    public function create()
    {
        $assetTypes = AssetType::all();
        $assetStatuses = AssetStatus::all();
        $nextAssetId = Asset::generateAssetId();

        return view('assets.create', compact('assetTypes', 'assetStatuses', 'nextAssetId'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'asset_id' => 'required|string|max:50|unique:assets',
            'asset_type_id' => 'required|exists:asset_types,id',
            'brand' => 'nullable|string|max:100',
            'model' => 'nullable|string|max:100',
            'serial_number' => 'nullable|string|max:100',
            'status_id' => 'required|exists:asset_statuses,id',
            'owner_name' => 'nullable|string|max:255',
            'purchase_date' => 'nullable|date',
            'warranty_expiry_date' => 'nullable|date|after_or_equal:purchase_date',
        ]);

        $validated['created_by'] = auth()->id();

        Asset::create($validated);

        return redirect()->route('assets.index')
            ->with('success', 'เพิ่มทรัพย์สินเรียบร้อยแล้ว');
    }

    public function show(Asset $asset)
    {
        $asset->load(['assetType', 'status', 'creator', 'tickets.assignee']);
        return view('assets.show', compact('asset'));
    }

    public function edit(Asset $asset)
    {
        $assetTypes = AssetType::all();
        $assetStatuses = AssetStatus::all();

        return view('assets.edit', compact('asset', 'assetTypes', 'assetStatuses'));
    }

    public function update(Request $request, Asset $asset)
    {
        $validated = $request->validate([
            'asset_id' => 'required|string|max:50|unique:assets,asset_id,' . $asset->id,
            'asset_type_id' => 'required|exists:asset_types,id',
            'brand' => 'nullable|string|max:100',
            'model' => 'nullable|string|max:100',
            'serial_number' => 'nullable|string|max:100',
            'status_id' => 'required|exists:asset_statuses,id',
            'owner_name' => 'nullable|string|max:255',
            'purchase_date' => 'nullable|date',
            'warranty_expiry_date' => 'nullable|date|after_or_equal:purchase_date',
        ]);

        $asset->update($validated);

        return redirect()->route('assets.index')
            ->with('success', 'แก้ไขทรัพย์สินเรียบร้อยแล้ว');
    }

    public function destroy(Asset $asset)
    {
        if ($asset->tickets()->exists()) {
            return back()->with('error', 'ไม่สามารถลบได้ เนื่องจากมี Ticket ที่เกี่ยวข้อง');
        }

        $asset->delete();

        return redirect()->route('assets.index')
            ->with('success', 'ลบทรัพย์สินเรียบร้อยแล้ว');
    }

    public function qrcode(Asset $asset)
    {
        return view('assets.qrcode', compact('asset'));
    }

    public function printLabel(Asset $asset)
    {
        return view('assets.print-label', compact('asset'));
    }
}
