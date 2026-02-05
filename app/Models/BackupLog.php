<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BackupLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'filename',
        'type',
        'performed_by',
    ];

    public function performer()
    {
        return $this->belongsTo(User::class, 'performed_by');
    }
}
