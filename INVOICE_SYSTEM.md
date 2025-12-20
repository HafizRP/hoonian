# Invoice System Documentation

## Overview
Sistem invoice terintegrasi dengan sistem transaksi yang memungkinkan admin untuk:
- Generate invoice otomatis untuk transaksi yang accepted
- Download invoice dalam format PDF
- Mark invoice sebagai paid
- Cancel invoice jika diperlukan

## Features

### 1. Generate Invoice
- Invoice hanya bisa di-generate untuk transaksi dengan status **"accepted"**
- Invoice number di-generate otomatis dengan format: `INV-YYYYMMDD-XXXX`
- Perhitungan otomatis:
  - Subtotal: Amount dari transaksi
  - Tax (10%): Dihitung otomatis
  - Total Amount: Subtotal + Tax
- Due date: Otomatis 30 hari dari tanggal invoice

### 2. Download PDF
- Invoice langsung di-download sebagai PDF
- Template professional dengan:
  - Company header (Hoonian)
  - Invoice details (number, date, due date, status)
  - Customer & property owner information
  - Itemized billing
  - Tax calculation
  - Payment instructions
  - Footer

### 3. Mark as Paid
- Admin bisa mark invoice sebagai paid
- Input payment method (Bank Transfer, Cash, Credit Card, dll)
- Tanggal pembayaran otomatis tercatat

### 4. Cancel Invoice
- Hapus invoice data dari transaksi
- Hanya bisa dilakukan untuk invoice yang belum paid

## Database Structure

### Tabel: transactions
Kolom tambahan untuk invoice:
- `invoice_number` (string, unique, nullable)
- `tax_rate` (decimal, default 0)
- `tax_amount` (decimal, default 0)
- `total_amount` (decimal, nullable)
- `payment_method` (string, nullable)
- `paid_at` (timestamp, nullable)
- `due_date` (timestamp, nullable)
- `notes` (text, nullable)

## Routes

```php
// Invoice Routes (Admin only)
Route::prefix('backoffice/invoices')->name('backoffice.invoices.')->group(function () {
    Route::get('/generate/{transaction}', [InvoiceController::class, 'generateAndDownload'])->name('generate');
    Route::post('/{id}/mark-paid', [InvoiceController::class, 'markAsPaid'])->name('markPaid');
    Route::delete('/{id}/cancel', [InvoiceController::class, 'cancel'])->name('cancel');
});
```

## Usage

### Generate & Download Invoice
1. Buka halaman **Backoffice > Transactions**
2. Cari transaksi dengan status **"ACCEPTED"**
3. Klik tombol **Generate Invoice** (icon file-invoice)
4. Invoice akan otomatis ter-generate dan ter-download sebagai PDF

### Mark Invoice as Paid
1. Pada transaksi yang sudah punya invoice
2. Klik tombol **Mark as Paid** (icon check-circle hijau)
3. Input payment method
4. Klik "Mark as Paid"

### Cancel Invoice
1. Pada transaksi yang sudah punya invoice (belum paid)
2. Klik tombol **Cancel Invoice** (icon X merah)
3. Konfirmasi cancellation

## Invoice Status
- **Draft**: Invoice belum di-generate
- **Sent**: Invoice sudah di-generate tapi belum dibayar
- **Paid**: Invoice sudah dibayar
- **Overdue**: Invoice melewati due date dan belum dibayar

## PDF Template
File: `resources/views/admin/invoice/pdf.blade.php`

Template menggunakan:
- DomPDF library (barryvdh/laravel-dompdf)
- Professional styling dengan CSS inline
- Company branding
- Responsive layout

## Tax Configuration
Default tax rate: **10%**

Untuk mengubah tax rate, edit di `InvoiceController.php`:
```php
$taxRate = 10; // Change this value
```

## Notes
- Invoice number bersifat unique dan auto-increment per hari
- Setiap transaksi hanya bisa punya 1 invoice
- Invoice yang sudah paid tidak bisa di-cancel
- PDF menggunakan DejaVu Sans font untuk support special characters
