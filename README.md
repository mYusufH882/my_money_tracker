# Money Tracker - Laravel Livewire

Aplikasi manajemen keuangan personal yang dibangun dengan Laravel dan Livewire. Saya buat aplikasi ini untuk membantu tracking pemasukan, pengeluaran, dan melihat laporan keuangan pribadi dengan mudah.

## Tech Stack

Berikut rincian tech stack yang saya pilih :

### Backend

-   **Laravel 12+** - Framework PHP yang sudah terbukti solid dan dokumentasinya lengkap
-   **Livewire 3+** - Untuk membuat UI yang interaktif tanpa ribet dengan JavaScript framework
-   **MySQL** - Database utama, pilih salah satu sesuai preferensi
-   **Laravel Excel** - Untuk export data ke Excel dan PDF, sangat membantu untuk reporting

### Frontend

-   **Livewire 3+** - Reactive UI framework dengan built-in JavaScript, no need external JS framework
-   **Tailwind CSS** - Utility-first CSS framework, bikin styling jadi cepet
-   **Blade Templates** - Template engine Laravel yang familiar dan mudah
-   **Components Livewire** - Komponen yang membantu membuat kode parsial yang bisa digunakan dengan mudah di blade laravel ini

### Additional Tools

-   **Laravel Sanctum** - Untuk mengelola autentikasi API dan pembuatan seluruh API endpoint aplikasi ini
-   **Vite** - Modern build tool untuk asset bundling, lebih cepat dari webpack
-   **PhpSpreadsheet** - Library untuk handle Excel files di backend

## Kenapa Pilih Stack Ini?

1. **Livewire** - Bisa bikin aplikasi yang reactive tanpa perlu repot dengan API endpoints atau complex JavaScript
2. **Tailwind** - Development jadi jauh lebih cepat, nggak perlu mikirin nama class CSS
3. **Laravel** - Ecosystem yang lengkap, dari authentication sampai queue semuanya udah ada

Detail :

Saya memilih tech stack ini karena ingin membuat aplikasi yang cepat, mudah di-maintain, dan tetap powerful. Alasan lain saya memilih Laravel + Livewire ini karena mempertimbangkan waktu development yg singkat dengan memanfaatkan fullstack pada laravel yang mendukung livewire dengan kemudahan dan kecepatan membuat frontend dalam aplikasi backend sekaligus dan ini yang saya pilih karena saya mempertimbangkan juga untuk waktu deploy. Dan saya sudah membuat API nya juga karena tadinya saya akan memisah antara BE dan FE namun melihat kompleksitas fitur yg sederhana jadi saya memutuskan hal diatas.

## Requirements

-   PHP 8.1+ (recommended 8.2)
-   Composer
-   MySQL 8.0+
-   Node.js 20+ & NPM 10+
-   Git

## Installation

Ikuti langkah-langkah ini untuk setup aplikasi di local environment:

### 1. Clone Repository

```bash
git clone <repository-url>
cd money-tracker
```

### 2. Install Dependencies

```bash
# Install PHP dependencies dulu
composer install

# Terus install Node dependencies
npm install
```

### 3. Environment Setup

```bash
# Copy file environment
cp .env.example .env

# Generate application key Laravel
php artisan key:generate
```

### 4. Database Configuration

Edit file `.env` sesuai database yang kamu pakai:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=money_tracker
DB_USERNAME=your_username
DB_PASSWORD=your_password
```

**Note**: Pastikan database `money_tracker` sudah dibuat sebelum migrate.

### 5. Database Migration

```bash
# Jalanin migration untuk buat tabel
php artisan migrate

# Kalau mau sample data buat testing
php artisan db:seed
```

Pada saat menjalankan seeder saya sudah membuat data dummy berupa kategori, user dan transaksi nya.

### 6. Storage Link

```bash
# Buat symbolic link untuk storage (kalau ada upload file)
php artisan storage:link
```

### 7. Build Assets

```bash
# Untuk development (hot reload)
npm run dev

# Untuk production (minified)
npm run build
```

### 8. Start Application

```bash
# Jalanin development server
php artisan serve

npm run dev (jika development)
```

Buka browser ke `http://localhost:8000/login` dan aplikasi siap dipakai!

## Features

Yang udah bisa dilakukan sama aplikasi ini:

-   ✅ **Authentication** - Login/Register standard, pakai Laravel Breeze
-   ✅ **Dashboard** - Overview balance, pemasukan/pengeluaran bulan ini
-   ✅ **Transactions** - CRUD transaksi, filter by kategori dan tanggal
-   ✅ **Categories** - Bikin kategori custom untuk income/expense
-   ✅ **Reports** - Laporan bulanan dengan grafik sederhana
-   ✅ **Export** - Download data ke Excel/PDF untuk backup
-   ✅ **Responsive** - Mobile friendly, bisa dipakai di HP

## Struktur Livewire Components

Aplikasi ini full menggunakan Livewire untuk semua interactivity:

### Authentication Components

-   `App\Livewire\Auth\Login` - Handle login form dan validation
-   `App\Livewire\Auth\Register` - Registration dengan konfirmasi password

### Main Application Components

-   `App\Livewire\Dashboard` - Dashboard utama dengan summary balance
-   `App\Livewire\Transactions\TransactionList` - CRUD transaksi dengan pagination
-   `App\Livewire\Categories\CategoryManager` - Manage kategori income/expense
-   `App\Livewire\Reports\FinancialSummary` - Generate laporan dengan chart

Semua komponen ini reactive dan nggak perlu page refresh!

## API Routes

Aplikasi ini nggak pakai REST API tradisional karena semua handled by Livewire, tapi saya sudah menyediakan endpoint API Publik pada aplikasi ini full dan berikut endpoint yang tersedia untuk integrasi dengan platform lain:

### Authentication Routes

```http
GET  /login          # Halaman login
POST /login          # Process login (Livewire)
GET  /register       # Halaman register
POST /register       # Process registration (Livewire)
POST /logout         # Logout user
```

### Protected Routes (Need Login)

```http
GET /dashboard       # Dashboard utama
GET /transactions    # Halaman kelola transaksi
GET /reports         # Halaman laporan keuangan
GET /categories      # Halaman kelola kategori
```

### Export Routes

```http
GET /export/excel    # Download transactions dalam Excel
GET /export/pdf      # Download transactions dalam PDF
```

### REST API Endpoints

Untuk integrasi eksternal, tersedia full REST API dengan authentication:

```http
POST /api/register # Register user baru
POST /api/login # Login dan dapatkan token
GET /api/me # Get user profile
POST /api/logout # Logout dan hapus token
```

# Categories

```http
GET /api/categories # List semua kategori
POST /api/categories # Tambah kategori baru
GET /api/categories/{id} # Detail kategori
PUT /api/categories/{id} # Update kategori
DELETE /api/categories/{id} # Hapus kategori
```

# Transactions

```http
GET /api/transactions # List transaksi dengan filter
POST /api/transactions # Tambah transaksi baru
GET /api/transactions/{id} # Detail transaksi
PUT /api/transactions/{id} # Update transaksi
DELETE /api/transactions/{id} # Hapus transaksi
GET /api/transactions/report # Report summary
GET /api/transactions/chart # Data untuk chart
GET /api/transactions/export # Export Excel
GET /api/transactions/export-pdf # Export PDF
```

**Note**: Semua interactivity (CRUD, filtering, etc) handled by Livewire components, jadi nggak ada AJAX calls manual.

## Database Structure

Saya mendesain database ini sesederhana mungkin dan dengan efisiensi yang tepat tanpa adanya redundasi data ataupun skema pada tabel yg dirancang, ada 3 tabel yang saling terhubung dengan relasi one to one ataupun one to many.

Berikut skema database yang dipakai aplikasi ini:

### Users Table

Tabel user yang standar:

```sql
- id (bigint, primary key)
- name (varchar)
- email (varchar, unique)
- password (varchar, hashed)
- created_at, updated_at (timestamps)
```

### Categories Table

Kategori transaksi yang bisa di-customize user:

```sql
- id (bigint, primary key)
- name (varchar, 100) - nama kategori
- created_at, updated_at (timestamps)
```

### Transactions Table

Tabel utama yang menjadi inti dari sistem aplikasi ini:

```sql
- id (bigint, primary key)
- kategori_id (foreign key ke categories)
- nominal (decimal 15,2) - nominal transaksi
- tipe (enum: 'pemasukan', 'pengeluaran') - jenis transaksi
- deskripsi (text, nullable) - catatan tambahan
- tgl_transaksi (date) - tanggal transaksi
- user_id (foreign key ke users)
- created_at, updated_at (timestamps)
```

## Key design decisions :

Saya pake decimal(15,2) buat nominal karena berurusan dengan uang, floating point itu harus hati-hati karena buat perhitungan data finansial yang harus tepat. 15 digit sebelum koma harusnya cukup buat personal finance (kalo sampe milyaran ya udah).

Enum buat tipe transaksi bukan tabel yg terpisah karena cuma 2 value doang dan sayangnya gak berubah. Kalo mau lebih flexible nanti bisa refactor jadi table master baru jadinya.

Index saya simpan di tgl_transaksi sama user_id karena hampir semua query filter berdasarkan ini:

```php
// Di migration

$table->index('tgl_transaksi');
$table->index(['user_id', 'tgl_transaksi']); // composite index buat performa
```

## Model relationships:

```php
// User.php
public function transactions()
{
    return $this->hasMany(Transaction::class);
}

// Category.php
public function transactions()
{
    return $this->hasMany(Transaction::class, 'kategori_id');
}

// Transaction.php
public function user()
{
    return $this->belongsTo(User::class);
}

public function category()
{
    return $this->belongsTo(Category::class, 'kategori_id');
}

// Scope buat filtering yang sering dipake
public function scopeFilterByMonth($query, $month, $year)
{
    return $query->whereMonth('tgl_transaksi', $month)
                 ->whereYear('tgl_transaksi', $year);
}

public function scopeThisMonth($query)
{
    return $query->whereMonth('tgl_transaksi', now()->month)
                 ->whereYear('tgl_transaksi', now()->year);
}
```

## Database Constraints & Indexes

Constraint dan index yang saya implementasikan untuk performa:

```sql
-- Foreign key constraints dengan cascade delete
ALTER TABLE transactions ADD CONSTRAINT fk_transactions_category
FOREIGN KEY (kategori_id) REFERENCES categories(id) ON DELETE CASCADE;

ALTER TABLE transactions ADD CONSTRAINT fk_transactions_user
FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE;

-- Index untuk performa query
CREATE INDEX idx_transactions_date ON transactions(tgl_transaksi);
CREATE INDEX idx_transactions_type ON transactions(tipe);
CREATE INDEX idx_transactions_user_date ON transactions(user_id, tgl_transaksi);
```

### Implementasi Business Logic yang Detail

## Dashboard Real-Time Calculations

Dashboard component menggunakan reactive data loading:

```php
public function loadDashboardData()
{
    // Summary untuk bulan ini dengan user isolation
    $this->totalIncome = Transaction::where('user_id', auth()->id())
        ->filterByMonth($this->currentMonth, $this->currentYear)
        ->where('tipe', 'pemasukan')
        ->sum('nominal') ?: 0;

    $this->totalExpense = Transaction::where('user_id', auth()->id())
        ->filterByMonth($this->currentMonth, $this->currentYear)
        ->where('tipe', 'pengeluaran')
        ->sum('nominal') ?: 0;

    $this->balance = $this->totalIncome - $this->totalExpense;
}

// Auto-reload data ketika month/year berubah
public function updated($propertyName)
{
    if (in_array($propertyName, ['currentMonth', 'currentYear'])) {
        $this->loadDashboardData();
    }
}
```

## Monthly Report Generation

Chart data generation untuk 6 bulan terakhir:

```php
private function loadMonthlyChart()
{
    $months = [];

    for ($i = 5; $i >= 0; $i--) {
        $date = Carbon::now()->subMonths($i);
        $month = $date->month;
        $year = $date->year;

        $income = Transaction::where('user_id', auth()->id())
            ->filterByMonth($month, $year)
            ->where('tipe', 'pemasukan')
            ->sum('nominal') ?: 0;

        $expense = Transaction::where('user_id', auth()->id())
            ->filterByMonth($month, $year)
            ->where('tipe', 'pengeluaran')
            ->sum('nominal') ?: 0;

        $months[] = [
            'label' => $date->format('M Y'),
            'income' => $income,
            'expense' => $expense,
            'net' => $income - $expense
        ];
    }

    $this->monthlyChart = $months;
}
```

## Validation & Business Rules

Validation rules yang saya implementasikan:

```php
// Transaction validation
$rules = [
    'tgl_transaksi' => 'required|date|before_or_equal:today',
    'deskripsi' => 'required|string|max:255',
    'tipe' => 'required|in:pemasukan,pengeluaran',
    'nominal' => 'required|numeric|min:0.01|max:999999999999.99',
    'kategori_id' => 'required|exists:categories,id'
];

// Custom validation messages
$messages = [
    'tgl_transaksi.before_or_equal' => 'Tanggal tidak boleh di masa depan',
    'nominal.min' => 'Nominal minimal Rp 0.01',
    'nominal.max' => 'Nominal terlalu besar'
];
```

## Performance Optimization

Optimasi yang saya terapkan:

Eager Loading: Load kategori bersamaan dengan transaksi

```php
$this->recentTransactions = Transaction::where('user_id', auth()->id())
    ->with('category') // Eager loading untuk avoid N+1 queries
    ->latest('tgl_transaksi')
    ->take(10)
    ->get();
```

## Query Optimization: Index pada kolom yang sering di-query

Pagination: Limit results untuk halaman transaksi
Caching: Cache hasil kalkulasi dashboard (future enhancement)

## Logika Keuangan

Cara aplikasi ini menghitung balance dan laporan:

### Balance Calculation

```php
Total Balance = Sum(Income) - Sum(Expense)
Monthly Income = Sum(Income) WHERE month = current_month
Monthly Expense = Sum(Expense) WHERE month = current_month
```

### Report Logic

-   **Monthly Summary**: Group transactions by month dan category
-   **Cash Flow**: Calculate difference antara income vs expense per bulan
-   **Category Breakdown**: Persentase spending per kategori

## Kesimpulan logika keuangan dari aplikasi ini :

Semua balance (saldo) diambil dari nominal pemasukkan dan dikurangi dengan nominal pengeluaran, dan setiap pemasukan atau pengeluaran memiliki kategori nya masing - masing untuk mengorganisir data, dengan user menginput nominal mereka yang akan masuk ke dalam tabel transaksi, Lalu dari tabel transaksi ini akan dibuat rangkuman berupa data yang akan ditampilkan pada dashboard dan bisa di export ke PDF/Excel berdasarkan bulan dan tahun tertentu berdasarkan pilihan tahun & bulan. Dan data yg disajikan pada dashboard berupa summary total saldo, pemasukkan, pengeluaran dan kategori, grafik trend 6 bulan terakhir, transaksi baru bulan ini dan transaksi berdasarkan breakdown kategori nya.
