<?php

namespace App\Http\Controllers;

use App\Models\Asset;
use App\Models\Ticket;
use App\Models\User;
use Illuminate\Http\Request;

class TicketController extends Controller
{
    public function index(Request $request)
    {
        $query = Ticket::with(['asset', 'assignee', 'creator']);

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('ticket_number', 'like', "%{$search}%")
                  ->orWhere('issue_description', 'like', "%{$search}%")
                  ->orWhereHas('asset', function($q2) use ($search) {
                      $q2->where('asset_id', 'like', "%{$search}%");
                  });
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $tickets = $query->latest()->paginate(15)->withQueryString();

        return view('tickets.index', compact('tickets'));
    }

    public function create(Request $request)
    {
        $assets = Asset::orderBy('asset_id')->get();
        $selectedAsset = $request->asset_id ? Asset::find($request->asset_id) : null;

        return view('tickets.create', compact('assets', 'selectedAsset'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'asset_id' => 'required|exists:assets,id',
            'issue_description' => 'required|string',
        ]);

        $validated['ticket_number'] = Ticket::generateTicketNumber();
        $validated['status'] = 'pending';
        $validated['created_by'] = auth()->id();

        Ticket::create($validated);

        return redirect()->route('tickets.index')
            ->with('success', 'สร้าง Ticket เรียบร้อยแล้ว');
    }

    public function show(Ticket $ticket)
    {
        $ticket->load(['asset.assetType', 'asset.status', 'assignee', 'creator']);
        return view('tickets.show', compact('ticket'));
    }

    public function edit(Ticket $ticket)
    {
        $assets = Asset::orderBy('asset_id')->get();
        $users = User::all();

        return view('tickets.edit', compact('ticket', 'assets', 'users'));
    }

    public function update(Request $request, Ticket $ticket)
    {
        $validated = $request->validate([
            'asset_id' => 'required|exists:assets,id',
            'issue_description' => 'required|string',
            'status' => 'required|in:pending,in_progress,resolved,closed',
            'assigned_to' => 'nullable|exists:users,id',
            'resolution_notes' => 'nullable|string',
        ]);

        if ($validated['status'] === 'resolved' && $ticket->status !== 'resolved') {
            $validated['resolved_at'] = now();
        }

        $ticket->update($validated);

        return redirect()->route('tickets.index')
            ->with('success', 'อัพเดท Ticket เรียบร้อยแล้ว');
    }

    public function destroy(Ticket $ticket)
    {
        $ticket->delete();

        return redirect()->route('tickets.index')
            ->with('success', 'ลบ Ticket เรียบร้อยแล้ว');
    }

    public function assign(Request $request, Ticket $ticket)
    {
        $validated = $request->validate([
            'assigned_to' => 'required|exists:users,id',
        ]);

        $ticket->update([
            'assigned_to' => $validated['assigned_to'],
            'status' => 'in_progress',
        ]);

        return back()->with('success', 'รับเคสเรียบร้อยแล้ว');
    }

    public function resolve(Request $request, Ticket $ticket)
    {
        $validated = $request->validate([
            'resolution_notes' => 'required|string',
        ]);

        $ticket->update([
            'status' => 'resolved',
            'resolution_notes' => $validated['resolution_notes'],
            'resolved_at' => now(),
        ]);

        return back()->with('success', 'แก้ไข Ticket เรียบร้อยแล้ว');
    }
}
