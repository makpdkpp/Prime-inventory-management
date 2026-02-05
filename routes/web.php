<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\AssetController;
use App\Http\Controllers\TicketController;
use App\Http\Controllers\QrCodeController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\AssetTypeController;
use App\Http\Controllers\Admin\AssetStatusController;
use App\Http\Controllers\Admin\BackupController;
use App\Http\Controllers\Admin\ReportController;

Route::get('/', function () {
    return redirect()->route('login');
});

Auth::routes();

Route::middleware(['auth'])->group(function () {
    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/home', [DashboardController::class, 'index'])->name('home');

    // Assets
    Route::resource('assets', AssetController::class);
    Route::get('/assets/{asset}/qrcode', [AssetController::class, 'qrcode'])->name('assets.qrcode');
    Route::get('/assets/{asset}/print-label', [AssetController::class, 'printLabel'])->name('assets.print-label');

    // Tickets
    Route::resource('tickets', TicketController::class);
    Route::post('/tickets/{ticket}/assign', [TicketController::class, 'assign'])->name('tickets.assign');
    Route::post('/tickets/{ticket}/resolve', [TicketController::class, 'resolve'])->name('tickets.resolve');

    // QR Code
    Route::get('/qrcode/scanner', [QrCodeController::class, 'scanner'])->name('qrcode.scanner');
    Route::get('/qrcode/generate/{asset}', [QrCodeController::class, 'generate'])->name('qrcode.generate');
    Route::post('/qrcode/lookup', [QrCodeController::class, 'lookup'])->name('qrcode.lookup');

    // Admin Routes
    Route::middleware(['admin'])->prefix('admin')->name('admin.')->group(function () {
        // User Management
        Route::resource('users', UserController::class);
        Route::post('/users/{user}/reset-password', [UserController::class, 'resetPassword'])->name('users.reset-password');

        // Asset Types
        Route::resource('asset-types', AssetTypeController::class);

        // Asset Statuses
        Route::resource('asset-statuses', AssetStatusController::class);

        // Backup/Restore
        Route::get('/backup', [BackupController::class, 'index'])->name('backup.index');
        Route::post('/backup/run', [BackupController::class, 'backup'])->name('backup.run');
        Route::post('/backup/restore', [BackupController::class, 'restore'])->name('backup.restore');

        // Reports
        Route::get('/reports/assets', [ReportController::class, 'assets'])->name('reports.assets');
        Route::get('/reports/tickets', [ReportController::class, 'tickets'])->name('reports.tickets');
        Route::get('/reports/assets/export', [ReportController::class, 'exportAssets'])->name('reports.assets.export');
        Route::get('/reports/tickets/export', [ReportController::class, 'exportTickets'])->name('reports.tickets.export');
    });
});
