<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Asset extends Model
{
    use HasFactory;

    protected $fillable = [
        'asset_id',
        'asset_type_id',
        'brand',
        'model',
        'serial_number',
        'status_id',
        'owner_name',
        'purchase_date',
        'warranty_expiry_date',
        'created_by',
    ];

    protected $casts = [
        'purchase_date' => 'date',
        'warranty_expiry_date' => 'date',
    ];

    public function isWarrantyExpired(): bool
    {
        if (!$this->warranty_expiry_date) {
            return false;
        }
        return $this->warranty_expiry_date->isPast();
    }

    public function isWarrantyExpiringSoon(int $days = 30): bool
    {
        if (!$this->warranty_expiry_date) {
            return false;
        }
        if ($this->isWarrantyExpired()) {
            return false;
        }
        return $this->warranty_expiry_date->diffInDays(Carbon::now()) <= $days;
    }

    public function getWarrantyStatusAttribute(): string
    {
        if (!$this->warranty_expiry_date) {
            return 'none';
        }
        if ($this->isWarrantyExpired()) {
            return 'expired';
        }
        if ($this->isWarrantyExpiringSoon(30)) {
            return 'expiring_soon';
        }
        return 'valid';
    }

    public function scopeWarrantyExpired($query)
    {
        return $query->whereNotNull('warranty_expiry_date')
                     ->whereDate('warranty_expiry_date', '<', Carbon::now());
    }

    public function scopeWarrantyExpiringSoon($query, int $days = 30)
    {
        return $query->whereNotNull('warranty_expiry_date')
                     ->whereDate('warranty_expiry_date', '>=', Carbon::now())
                     ->whereDate('warranty_expiry_date', '<=', Carbon::now()->addDays($days));
    }

    public function scopeWarrantyValid($query)
    {
        return $query->whereNotNull('warranty_expiry_date')
                     ->whereDate('warranty_expiry_date', '>', Carbon::now()->addDays(30));
    }

    public function assetType()
    {
        return $this->belongsTo(AssetType::class, 'asset_type_id');
    }

    public function status()
    {
        return $this->belongsTo(AssetStatus::class, 'status_id');
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function tickets()
    {
        return $this->hasMany(Ticket::class, 'asset_id');
    }

    public static function generateAssetId(): string
    {
        $year = date('Y');
        $lastAsset = self::whereYear('created_at', $year)
            ->orderBy('id', 'desc')
            ->first();
        
        if ($lastAsset) {
            $lastNumber = (int) substr($lastAsset->asset_id, -4);
            $newNumber = $lastNumber + 1;
        } else {
            $newNumber = 1;
        }
        
        return 'AST-' . $year . '-' . str_pad($newNumber, 4, '0', STR_PAD_LEFT);
    }
}
