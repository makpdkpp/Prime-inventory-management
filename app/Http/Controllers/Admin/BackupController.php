<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\BackupLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Storage;

class BackupController extends Controller
{
    public function index()
    {
        $backups = BackupLog::with('performer')
            ->latest()
            ->paginate(15);
            
        return view('admin.backup.index', compact('backups'));
    }

    public function backup()
    {
        try {
            $filename = 'backup-' . date('Y-m-d-His') . '.sql';
            
            Artisan::call('backup:run', ['--only-db' => true]);
            
            BackupLog::create([
                'filename' => $filename,
                'type' => 'backup',
                'performed_by' => auth()->id(),
            ]);

            return back()->with('success', 'สำรองข้อมูลเรียบร้อยแล้ว');
        } catch (\Exception $e) {
            return back()->with('error', 'เกิดข้อผิดพลาด: ' . $e->getMessage());
        }
    }

    public function restore(Request $request)
    {
        $request->validate([
            'backup_file' => 'required|file|mimes:sql,zip',
        ]);

        try {
            $file = $request->file('backup_file');
            $filename = $file->getClientOriginalName();
            
            BackupLog::create([
                'filename' => $filename,
                'type' => 'restore',
                'performed_by' => auth()->id(),
            ]);

            return back()->with('success', 'กู้คืนข้อมูลเรียบร้อยแล้ว');
        } catch (\Exception $e) {
            return back()->with('error', 'เกิดข้อผิดพลาด: ' . $e->getMessage());
        }
    }
}
