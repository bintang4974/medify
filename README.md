# Medify - Technical Test

Laravel 9 project untuk tes rekrutmen programmer Medify.

---

## Setup

```bash
composer install
php artisan migrate
php artisan storage:link
npm install && npm run dev
```

---

## Yang Dikerjakan

### 1. Field Foto pada Master Items
- Migrasi baru: menambah kolom `foto` (nullable) ke tabel `master_items`
- Form create/edit dilengkapi input upload gambar dengan preview foto saat edit
- File disimpan di `storage/public/master_items/foto/`
- Foto ditampilkan di halaman index (thumbnail) dan halaman detail item

### 2. Fix Bug Filter Harga Min & Max
- **Bug:** filter harga hanya cek `hargamin`, tapi langsung pakai `hargamax` tanpa validasi — menyebabkan query salah jika `hargamax` kosong
- **Fix:** logika dipisah menjadi tiga kondisi: keduanya ada (BETWEEN), hanya min, atau hanya max

### 3. CRUD Kategori Items (Many-to-Many dengan Master Items)
- Migrasi: tabel `kategori_items` (kolom `nama`, `kode`) dan pivot table `kategori_item_master_item`
- Model `KategoriItem` dengan relasi `belongsToMany` ke `MasterItem`, dan sebaliknya
- Halaman index: tabel + filter berdasarkan nama dan kode kategori
- Halaman detail/single: menampilkan nama & kode kategori, serta daftar item yang memiliki kategori tersebut
- Form create/edit kategori
- Form Master Items ditambah field kategori (checkbox multi-select, tersinkronisasi dengan `sync()`)
- Navbar ditambah link **Master Items** dan **Kategori Items**

### 4. Export PDF Kategori (Download di Halaman Detail)
- Menggunakan package `barryvdh/laravel-dompdf` yang sudah tersedia
- PDF berisi: nama kategori, kode kategori, tabel item (kode, nama, harga beli, laba, harga jual, supplier, jenis)
- Footer PDF menampilkan tanggal dan waktu cetak
- Tombol download tersedia di halaman detail/single kategori

### 5. Export Excel Master Items
- Menambahkan package `phpoffice/phpspreadsheet`
- File `.xlsx` berisi kolom: No, Nama Kategori (terpisah koma), Nama Items, Nama Supplier, Harga, Laba, Harga Jual
- Tombol download tersedia di halaman index Master Items
- Data diambil menggunakan eager loading (`with('kategoriItems')`)

---

## File Baru / yang Diubah

| Tipe | Path |
|------|------|
| Migration | `database/migrations/2024_01_01_000001_add_foto_to_master_items_table.php` |
| Migration | `database/migrations/2024_01_01_000002_create_kategori_items_table.php` |
| Migration | `database/migrations/2024_01_01_000003_create_kategori_item_master_item_table.php` |
| Model | `app/Models/KategoriItem.php` *(baru)* |
| Model | `app/Models/MasterItem.php` *(diupdate)* |
| Controller | `app/Http/Controllers/KategoriItemsController.php` *(baru)* |
| Controller | `app/Http/Controllers/MasterItemsController.php` *(diupdate)* |
| Routes | `routes/web.php` *(diupdate)* |
| View | `resources/views/layouts/app.blade.php` *(navbar diupdate)* |
| View | `resources/views/master_items/**` *(form, table, js, single diupdate)* |
| View | `resources/views/kategori_items/**` *(semua baru: index, form, single, pdf)* |

## Packages Tambahan

| Package | Kegunaan |
|---------|----------|
| `barryvdh/laravel-dompdf` | Generate PDF (sudah ada di project awal) |
| `phpoffice/phpspreadsheet` | Generate file Excel (.xlsx) |

---

## Catatan Teknis

- Semua query menggunakan **Eloquent** dengan **eager loading** (`with()`) untuk menghindari N+1 problem
- Upload foto menggunakan `Storage::disk('public')`, pastikan sudah menjalankan `php artisan storage:link`
- Soft deletes diterapkan pada tabel `master_items` dan `kategori_items`
- Struktur view mengikuti pola yang sudah ada di Master Items (layout, filter, table, js terpisah)
- Filter harga pada Master Items sekarang mendukung input salah satu saja (min atau max) tanpa error
