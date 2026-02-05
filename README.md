# Prime Inventory Management

ระบบจัดการทรัพย์สินและครุภัณฑ์ (IT Asset Management System) พัฒนาด้วย Laravel 12

## ✨ Features

### Asset Management
- **จัดการทรัพย์สิน** - เพิ่ม แก้ไข ลบ และค้นหาทรัพย์สิน
- **Auto Generate Asset ID** - สร้างรหัสทรัพย์สินอัตโนมัติ (AST-YYYY-XXXX)
- **QR Code** - สร้างและสแกน QR Code สำหรับทรัพย์สิน
- **Print Label** - พิมพ์ป้ายติดทรัพย์สิน
- **Warranty Tracking** - ติดตามสถานะการรับประกัน (หมดอายุ, ใกล้หมดอายุ, ยังใช้ได้)

### Ticket System
- **แจ้งซ่อม/แจ้งปัญหา** - สร้าง Ticket เชื่อมโยงกับทรัพย์สิน
- **Assign & Resolve** - มอบหมายงานและบันทึกการแก้ไข
- **Auto Generate Ticket Number** - สร้างเลข Ticket อัตโนมัติ (TKT-YYYYMM-XXXX)

### Admin Features
- **User Management** - จัดการผู้ใช้งานและ Reset Password
- **Asset Types** - จัดการประเภททรัพย์สิน
- **Asset Statuses** - จัดการสถานะทรัพย์สิน
- **Backup/Restore** - สำรองและกู้คืนข้อมูล
- **Reports** - รายงานทรัพย์สินและ Ticket พร้อม Export Excel

## 🛠 Tech Stack

- **Framework:** Laravel 12
- **PHP:** ^8.2
- **Database:** SQLite (default) / MySQL
- **Frontend:** Blade + TailwindCSS 4
- **Build Tool:** Vite 7
- **Authentication:** Laravel UI

### Key Packages
- `barryvdh/laravel-dompdf` - สร้าง PDF
- `maatwebsite/excel` - Export Excel
- `simplesoftwareio/simple-qrcode` - สร้าง QR Code
- `spatie/laravel-backup` - Backup ระบบ

## 📦 Installation

### Requirements
- PHP >= 8.2
- Composer
- Node.js & NPM

### Quick Setup

```bash
# Clone repository
git clone <repository-url>
cd Prime-inventory-management

# Install dependencies & setup
composer setup
```

### Manual Setup

```bash
# Install PHP dependencies
composer install

# Copy environment file
cp .env.example .env

# Generate application key
php artisan key:generate

# Run migrations
php artisan migrate

# Install Node dependencies
npm install

# Build assets
npm run build
```

## 🚀 Development

```bash
# Start development server (all services)
composer dev
```

คำสั่งนี้จะรัน:
- Laravel development server
- Queue listener
- Laravel Pail (logs)
- Vite dev server

หรือรันแยกแต่ละ service:

```bash
# Laravel server only
php artisan serve

# Vite dev server
npm run dev

# Queue worker
php artisan queue:listen
```

## 🧪 Testing

```bash
composer test
```

## 📁 Project Structure

```
├── app/
│   ├── Exports/          # Excel exports
│   ├── Http/
│   │   ├── Controllers/
│   │   │   ├── Admin/    # Admin controllers
│   │   │   ├── Auth/     # Authentication
│   │   │   └── ...       # Main controllers
│   │   └── Middleware/
│   └── Models/           # Eloquent models
├── database/
│   ├── migrations/       # Database migrations
│   └── seeders/          # Database seeders
├── resources/
│   └── views/            # Blade templates
├── routes/
│   └── web.php           # Web routes
└── storage/              # Logs, cache, backups
```

## 🔐 Default Credentials

หลังจาก migrate แล้ว สามารถสร้าง user ผ่าน:

```bash
php artisan tinker
```

```php
User::create([
    'name' => 'Admin',
    'email' => 'admin@example.com',
    'password' => bcrypt('password'),
    'role' => 'admin'
]);
```

## 📄 License

MIT License
