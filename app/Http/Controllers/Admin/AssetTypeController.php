<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AssetType;
use Illuminate\Http\Request;

class AssetTypeController extends Controller
{
    public function index()
    {
        $assetTypes = AssetType::withCount('assets')->latest()->paginate(15);
        return view('admin.asset-types.index', compact('assetTypes'));
    }

    public function create()
    {
        return view('admin.asset-types.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:100',
            'description' => 'nullable|string',
        ]);

        AssetType::create($validated);

        return redirect()->route('admin.asset-types.index')
            ->with('success', 'เพิ่มประเภททรัพย์สินเรียบร้อยแล้ว');
    }

    public function edit(AssetType $assetType)
    {
        return view('admin.asset-types.edit', compact('assetType'));
    }

    public function update(Request $request, AssetType $assetType)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:100',
            'description' => 'nullable|string',
        ]);

        $assetType->update($validated);

        return redirect()->route('admin.asset-types.index')
            ->with('success', 'แก้ไขประเภททรัพย์สินเรียบร้อยแล้ว');
    }

    public function destroy(AssetType $assetType)
    {
        if ($assetType->assets()->exists()) {
            return back()->with('error', 'ไม่สามารถลบได้ เนื่องจากมีทรัพย์สินที่ใช้ประเภทนี้');
        }

        $assetType->delete();

        return redirect()->route('admin.asset-types.index')
            ->with('success', 'ลบประเภททรัพย์สินเรียบร้อยแล้ว');
    }
}
