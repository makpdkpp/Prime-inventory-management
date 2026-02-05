<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AssetStatus;
use Illuminate\Http\Request;

class AssetStatusController extends Controller
{
    public function index()
    {
        $assetStatuses = AssetStatus::withCount('assets')->latest()->paginate(15);
        return view('admin.asset-statuses.index', compact('assetStatuses'));
    }

    public function create()
    {
        return view('admin.asset-statuses.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:50',
            'color' => 'required|string|max:20',
        ]);

        AssetStatus::create($validated);

        return redirect()->route('admin.asset-statuses.index')
            ->with('success', 'เพิ่มสถานะทรัพย์สินเรียบร้อยแล้ว');
    }

    public function edit(AssetStatus $assetStatus)
    {
        return view('admin.asset-statuses.edit', compact('assetStatus'));
    }

    public function update(Request $request, AssetStatus $assetStatus)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:50',
            'color' => 'required|string|max:20',
        ]);

        $assetStatus->update($validated);

        return redirect()->route('admin.asset-statuses.index')
            ->with('success', 'แก้ไขสถานะทรัพย์สินเรียบร้อยแล้ว');
    }

    public function destroy(AssetStatus $assetStatus)
    {
        if ($assetStatus->assets()->exists()) {
            return back()->with('error', 'ไม่สามารถลบได้ เนื่องจากมีทรัพย์สินที่ใช้สถานะนี้');
        }

        $assetStatus->delete();

        return redirect()->route('admin.asset-statuses.index')
            ->with('success', 'ลบสถานะทรัพย์สินเรียบร้อยแล้ว');
    }
}
