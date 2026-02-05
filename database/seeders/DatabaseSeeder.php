<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\AssetType;
use App\Models\AssetStatus;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Create Admin User
        User::create([
            'name' => 'Administrator',
            'email' => 'admin@example.com',
            'password' => Hash::make('password'),
            'role' => 'admin',
        ]);

        // Create Normal User
        User::create([
            'name' => 'User',
            'email' => 'user@example.com',
            'password' => Hash::make('password'),
            'role' => 'user',
        ]);

        // Create Asset Types
        $assetTypes = [
            ['name' => 'คอมพิวเตอร์', 'description' => 'คอมพิวเตอร์ตั้งโต๊ะ, โน้ตบุ๊ค'],
            ['name' => 'เครื่องพิมพ์', 'description' => 'Printer, Scanner'],
            ['name' => 'อุปกรณ์เครือข่าย', 'description' => 'Router, Switch, Access Point'],
            ['name' => 'โทรศัพท์', 'description' => 'โทรศัพท์สำนักงาน, มือถือ'],
            ['name' => 'จอภาพ', 'description' => 'Monitor, TV'],
            ['name' => 'อื่นๆ', 'description' => 'อุปกรณ์อื่นๆ'],
        ];

        foreach ($assetTypes as $type) {
            AssetType::create($type);
        }

        // Create Asset Statuses
        $assetStatuses = [
            ['name' => 'ใช้งานปกติ', 'color' => '#28a745'],
            ['name' => 'กำลังซ่อม', 'color' => '#ffc107'],
            ['name' => 'ชำรุด', 'color' => '#dc3545'],
            ['name' => 'รอจำหน่าย', 'color' => '#6c757d'],
            ['name' => 'จำหน่ายแล้ว', 'color' => '#343a40'],
            ['name' => 'สูญหาย', 'color' => '#17a2b8'],
        ];

        foreach ($assetStatuses as $status) {
            AssetStatus::create($status);
        }
    }
}
