<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ticket extends Model
{
    use HasFactory;

    protected $fillable = [
        'ticket_number',
        'asset_id',
        'issue_description',
        'status',
        'assigned_to',
        'resolved_at',
        'resolution_notes',
        'created_by',
    ];

    protected $casts = [
        'resolved_at' => 'datetime',
    ];

    public function asset()
    {
        return $this->belongsTo(Asset::class, 'asset_id');
    }

    public function assignee()
    {
        return $this->belongsTo(User::class, 'assigned_to');
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public static function generateTicketNumber(): string
    {
        $yearMonth = date('Ym');
        $lastTicket = self::where('ticket_number', 'like', 'TKT-' . $yearMonth . '-%')
            ->orderBy('id', 'desc')
            ->first();
        
        if ($lastTicket) {
            $lastNumber = (int) substr($lastTicket->ticket_number, -4);
            $newNumber = $lastNumber + 1;
        } else {
            $newNumber = 1;
        }
        
        return 'TKT-' . $yearMonth . '-' . str_pad($newNumber, 4, '0', STR_PAD_LEFT);
    }
}
