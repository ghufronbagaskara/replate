<div align="left">

# 🍽️ Replate

### _Platform Berbagi Makanan Berlebih untuk Mengurangi Food Waste_

[![PHP](https://img.shields.io/badge/PHP-8.2+-777BB4?style=for-the-badge&logo=php&logoColor=white)](https://www.php.net/)
[![Laravel](https://img.shields.io/badge/Laravel-12.x-FF2D20?style=for-the-badge&logo=laravel&logoColor=white)](https://laravel.com/)
[![MySQL](https://img.shields.io/badge/MySQL-8.0+-4479A1?style=for-the-badge&logo=mysql&logoColor=white)](https://www.mysql.com/)
[![Tailwind CSS](https://img.shields.io/badge/Tailwind-3.4-38B2AC?style=for-the-badge&logo=tailwind-css&logoColor=white)](https://tailwindcss.com/)

_Selamatkan Makanan, Bantu Sesama_

</div>

---

## 📖 Tentang Project

**Replate** adalah aplikasi web yang menghubungkan pemilik makanan berlebih dengan orang-orang yang membutuhkan.  
Project ini dibuat sebagai solusi untuk **mengurangi food waste** sekaligus mendorong budaya berbagi makanan secara aman dan terorganisir.

Aplikasi ini memungkinkan pengguna untuk:

-   Membagikan atau menjual makanan layak konsumsi
-   Menemukan makanan terdekat dengan harga terjangkau (bahkan gratis)
-   Melakukan pemesanan dan review secara langsung melalui platform

---

## 🎯 Tujuan

-   Mengurangi makanan layak konsumsi yang terbuang
-   Membantu akses pangan yang lebih merata
-   Menyediakan platform berbagi makanan yang aman dan modern
-   Mengimplementasikan praktik pengembangan web yang clean dan terstruktur

---

## ✨ Fitur Utama

### 🔐 Autentikasi Pengguna

-   Registrasi & login user
-   Proteksi route menggunakan middleware
-   Password hashing & session security

### 🍱 Manajemen Postingan Makanan

-   Upload foto dan detail makanan
-   Atur harga (gratis / berbayar)
-   Tentukan lokasi dan stok
-   Edit & hapus postingan sendiri

### 🛒 Sistem Pemesanan

-   Pemesanan langsung dari postingan
-   Manajemen status pesanan
-   Riwayat transaksi pengguna

### ⭐ Review & Rating

-   Rating dan ulasan setelah transaksi
-   Meningkatkan kepercayaan antar pengguna

### 🛡️ Admin Panel

-   Moderasi postingan
-   Manajemen user
-   Penanganan laporan konten bermasalah

---

## 🏗️ Arsitektur Singkat

Project ini menggunakan pendekatan **Clean Architecture** untuk menjaga kode tetap rapi dan mudah dikembangkan.

-   **Controller** → menangani HTTP request
-   **Service Layer** → business logic
-   **Repository** → akses data
-   **Model (Eloquent)** → representasi database
-   **Form Request** → validasi input
-   **Middleware** → keamanan & kontrol akses

```
replate/
│
├── 📂 app/
│   │
│   ├── 📂 Http/
│   │   ├── Controllers/           # Request handlers
│   │   │   ├── AuthController.php
│   │   │   ├── FoodPostController.php
│   │   │   ├── TransactionController.php
│   │   │   ├── ReviewController.php
│   │   │   ├── ReportController.php
│   │   │   └── Admin/             # Admin controllers
│   │   │
│   │   ├── Requests/              # Form validation
│   │   │   ├── RegisterRequest.php
│   │   │   ├── StoreFoodPostRequest.php
│   │   │   └── ...
│   │   │
│   │   └── Middleware/            # Security & logging
│   │       ├── CheckSuspended.php
│   │       ├── LogUserActivity.php
│   │       └── SecurityHeaders.php
│   │
│   ├── 📂 Models/                 # Eloquent models
│   │   ├── User.php
│   │   ├── FoodPost.php
│   │   ├── Transaction.php
│   │   ├── Review.php
│   │   ├── Report.php
│   │   └── ActivityLog.php
│   │
│   ├── 📂 Repositories/           # Data access layer
│   │   ├── UserRepository.php
│   │   ├── FoodPostRepository.php
│   │   ├── TransactionRepository.php
│   │   ├── ReviewRepository.php
│   │   └── ReportRepository.php
│   │
│   ├── 📂 Services/               # Business logic
│   │   ├── AuthService.php
│   │   ├── FoodPostService.php
│   │   ├── TransactionService.php
│   │   ├── ReviewService.php
│   │   └── ReportService.php
│   │
│   ├── 📂 Traits/                 # Reusable model behaviors
│   │   ├── HasUuid.php           # UUID generation
│   │   ├── LogsActivity.php      # Auto activity logging
│   │   └── Cacheable.php         # Cache management
│   │
│   ├── 📂 Exceptions/             # Custom exceptions
│   │   ├── BusinessException.php
│   │   └── Handler.php
│   │
│   └── 📂 Console/Commands/       # Artisan commands
│       ├── CleanupOldData.php
│       └── SystemHealthCheck.php
│
├── 📂 database/
│   ├── migrations/                # Database schema
│   └── seeders/                   # Sample data
│
├── 📂 resources/
│   ├── views/                     # Blade templates
│   │   ├── auth/
│   │   ├── posts/
│   │   ├── orders/
│   │   ├── reviews/
│   │   └── admin/
│   │
│   ├── css/app.css               # Tailwind styles
│   └── js/app.js                 # Frontend JS
│
├── 📂 routes/
│   ├── web.php                   # Web routes
│   └── auth.php                  # Auth routes
│
├── 📂 config/                    # Configuration files
│   ├── security.php              # Security settings
│   ├── replate.php               # App settings
│   └── cache_custom.php          # Cache TTL
│
├── 📄 README.md
└── 📄 composer.json

```

---

## 🛠️ Tech Stack

### Backend

-   PHP 8.2+
-   Laravel 12
-   MySQL
-   UUID untuk identifikasi data

### Frontend

-   Blade Templates
-   Tailwind CSS
-   Vite

### Keamanan & Kualitas

-   CSRF Protection
-   XSS Protection
-   Soft Deletes
-   Activity Logging

---

## 🚀 Menjalankan Project (Singkat)

```bash
composer install
npm install
cp .env.example .env
php artisan key:generate
php artisan migrate
php artisan storage:link
npm run dev
```

Akses aplikasi melalui:

-   `http://localhost:8000`
-   atau `http://replate.test` (jika menggunakan Laravel Herd)

---

## 📄 Lisensi

Project ini dibuat untuk **Tugas Mata Kuliah Pemrograman Aplikasi Web**.

Boleh digunakan untuk:

-   Pembelajaran
-   Referensi
-   Portfolio

Tidak boleh:

-   Dikomersialkan
-   Diklaim sebagai karya pribadi tanpa izin
-   Digunakan untuk plagiarisme

---

<div align="left">

**"Satu makanan yang diselamatkan, satu senyuman yang tercipta."**

© 2026 Replate

</div>
```
