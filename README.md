# 🏠 Hoonian - Property Marketplace & Bidding Platform

Hoonian adalah platform marketplace properti dengan sistem bidding yang memungkinkan pengguna untuk menawar properti secara real-time dan menghasilkan invoice otomatis untuk transaksi yang berhasil.

## ✨ Features

### 🏘️ Property Management
- **Property Listings**: Browse dan search properti
- **Property Details**: Informasi lengkap properti dengan galeri foto
- **Property Types**: Rumah, Apartemen, Tanah, dll
- **Owner Management**: Kelola properti yang Anda miliki

### 💰 Bidding System
- **Real-time Bidding**: Sistem penawaran real-time
- **Bid Status**: Leading, Outbid, Accepted
- **Bid Management**: Kelola bid masuk dan keluar
- **Auto Status Update**: Status otomatis berubah saat ada bid baru

### 📄 Invoice System
- **Auto Generate**: Invoice otomatis untuk transaksi accepted
- **PDF Export**: Download invoice dalam format PDF professional
- **Tax Calculation**: Perhitungan pajak otomatis (10%)
- **Payment Tracking**: Track status pembayaran
- **Invoice Status**: Draft, Sent, Paid, Overdue

### 👥 User Management
- **User Registration**: Daftar dengan email atau Google OAuth
- **User Profiles**: Kelola profil pengguna
- **Role Management**: Admin dan User roles
- **Google OAuth**: Login cepat dengan Google

### 🎛️ Admin Dashboard
- **Dashboard Analytics**: Statistik transaksi dan properti
- **User Management**: Kelola semua user
- **Property Management**: Kelola semua properti
- **Transaction Management**: Monitor semua transaksi
- **Invoice Management**: Generate dan kelola invoice
- **DataTables**: Export data ke Excel, PDF, Print

## 🛠️ Tech Stack

### Backend
- **Laravel 10**: PHP Framework
- **PHP 8.2**: Programming Language
- **MariaDB 10.11**: Database
- **Redis**: Cache & Session Storage

### Frontend
- **Blade Templates**: Laravel templating engine
- **Bootstrap 5**: CSS Framework
- **jQuery**: JavaScript library
- **DataTables**: Advanced tables
- **SweetAlert**: Beautiful alerts

### Libraries
- **Laravel Socialite**: Google OAuth
- **DomPDF**: PDF generation
- **Laravel Sanctum**: API authentication

### DevOps
- **Docker**: Containerization
- **Docker Compose**: Multi-container orchestration
- **Nginx**: Web server
- **PHP-FPM**: PHP processor

## 📦 Installation

### Local Development

#### Prerequisites
- PHP 8.2+
- Composer
- Node.js & NPM
- MySQL/MariaDB
- Redis (optional)

#### Steps
```bash
# Clone repository
git clone <repository-url>
cd hoonian

# Install dependencies
composer install
npm install

# Setup environment
cp .env.example .env
php artisan key:generate

# Configure database in .env
# DB_DATABASE=hoonian
# DB_USERNAME=root
# DB_PASSWORD=your-password

# Run migrations
php artisan migrate

# Seed database (optional)
php artisan db:seed

# Create storage link
php artisan storage:link

# Start development server
php artisan serve
```

Access: http://localhost:8000

### Docker Deployment

Lihat dokumentasi lengkap di [DOCKER_DEPLOYMENT.md](DOCKER_DEPLOYMENT.md)

#### Quick Start
```bash
# Setup environment
cp .env.example .env

# Edit .env dengan konfigurasi Docker
# DB_HOST=db
# REDIS_HOST=redis

# Build and start
docker-compose build
docker-compose up -d

# Run migrations
docker-compose exec app php artisan migrate --force
```

Access: http://localhost:8004

## 📚 Documentation

- **[Invoice System](INVOICE_SYSTEM.md)**: Dokumentasi lengkap sistem invoice
- **[Docker Deployment](DOCKER_DEPLOYMENT.md)**: Panduan deployment dengan Docker

## 🔑 Default Credentials

Setelah seeding database:

**Admin Account:**
- Email: admin@hoonian.com
- Password: password

**User Account:**
- Email: user@hoonian.com
- Password: password

⚠️ **Penting**: Ubah password default di production!

## 🎯 Usage Guide

### Untuk User

1. **Register/Login**
   - Daftar dengan email atau Google OAuth
   - Verifikasi email (jika diperlukan)

2. **Browse Properties**
   - Lihat daftar properti
   - Filter berdasarkan type, harga, lokasi
   - Lihat detail properti

3. **Place Bid**
   - Klik "Place Bid" pada properti
   - Bid otomatis menggunakan harga properti
   - Status bid: Leading/Outbid

4. **Manage Bids**
   - Lihat bid yang masuk (selling)
   - Lihat bid yang keluar (buying)
   - Accept/Decline bid

### Untuk Admin

1. **Dashboard**
   - Lihat statistik keseluruhan
   - Monitor aktivitas terbaru

2. **Manage Properties**
   - CRUD properti
   - Upload gambar properti
   - Set status properti

3. **Manage Transactions**
   - Monitor semua transaksi
   - Filter berdasarkan status, tanggal
   - Export data

4. **Generate Invoice**
   - Pilih transaksi accepted
   - Klik "Generate Invoice"
   - PDF otomatis ter-download
   - Mark as paid setelah pembayaran

## 🔧 Configuration

### Environment Variables

```env
# App
APP_NAME=Hoonian
APP_ENV=production
APP_DEBUG=false
APP_URL=http://localhost

# Database
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=hoonian
DB_USERNAME=root
DB_PASSWORD=

# Google OAuth
GOOGLE_CLIENT_ID=
GOOGLE_CLIENT_SECRET=
GOOGLE_REDIRECT_URI=
```

### Invoice Tax Rate

Edit di `app/Http/Controllers/InvoiceController.php`:
```php
$taxRate = 10; // 10% tax
```

## 🚀 Deployment

### Production Checklist

- [ ] Set `APP_ENV=production`
- [ ] Set `APP_DEBUG=false`
- [ ] Generate strong `APP_KEY`
- [ ] Configure database
- [ ] Setup mail server
- [ ] Configure Google OAuth
- [ ] Setup SSL/TLS
- [ ] Configure backups
- [ ] Setup monitoring
- [ ] Change default passwords

### Optimization

```bash
# Clear caches
php artisan optimize:clear

# Cache config, routes, views
php artisan optimize

# Generate autoload
composer dump-autoload --optimize
```

## 📸 Screenshots

(Add screenshots here)

## 🤝 Contributing

1. Fork the repository
2. Create feature branch (`git checkout -b feature/AmazingFeature`)
3. Commit changes (`git commit -m 'Add some AmazingFeature'`)
4. Push to branch (`git push origin feature/AmazingFeature`)
5. Open Pull Request

## 📝 License

This project is proprietary software.

## 👨‍💻 Developer

Developed by [Your Name]

## 📧 Support

For support, email support@hoonian.com

---

**Made with ❤️ using Laravel**
