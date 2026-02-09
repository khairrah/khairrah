# 📋 ANALISIS ALUR DENDA - SISTEM PERPUSTAKAAN

**Status Pemeriksaan:** ✅ LENGKAP & TERINTEGRASI  
**Tanggal Pemeriksaan:** 29 Januari 2026  
**Kesimpulan Umum:** Sistem denda berfungsi dengan baik dan terstruktur dengan alur verifikasi 2-tahap

---

## 1. 📊 RINGKASAN STRUKTUR DENDA

### Tiga Komponen Denda:
| Tipe | Kondisi | Rumus | Biaya |
|------|---------|-------|-------|
| 🟢 **Keterlambatan** | Alat baik tapi terlambat | Hari terlambat × Rp 5.000/hari | Variabel |
| 🟠 **Kerusakan** | Alat rusak saat dikembalikan | 50% × Harga barang | 50% harga |
| 🔴 **Hilang** | Alat hilang | 100% × Harga barang | 100% harga |

### Harga Barang:
- Input oleh petugas saat validasi pengembalian
- Digunakan untuk menghitung denda rusak/hilang
- Tidak diperlukan jika hanya keterlambatan (alat baik)

---

## 2. 🔄 ALUR PROSES DENDA (2-TAHAP)

```
┌─────────────────────────────────────────────────────────────┐
│                    TAHAP 1: PERHITUNGAN DENDA                │
│                   (Petugas - Validasi Return)               │
└─────────────────────────────────────────────────────────────┘
                              ↓
                    Input: Jumlah baik/rusak/hilang
                    Input: Harga barang (jika ada kerusakan)
                              ↓
            DendaHelper::hitungDenda() → Hitung total denda
                              ↓
        Loan.denda_status = 'menunggu_pembayaran'
                Loan.status = 'returned'
                              ↓
┌─────────────────────────────────────────────────────────────┐
│              TAHAP 2: VERIFIKASI PEMBAYARAN                  │
│                   (Siswa + Petugas)                          │
└─────────────────────────────────────────────────────────────┘
                              ↓
                  SISWA MENGAJUKAN PEMBAYARAN
                              ↓
    DendaPayment.status = 'menunggu_verifikasi'
    Loan.denda_status = 'menunggu_verifikasi'
                              ↓
                PETUGAS VERIFIKASI PEMBAYARAN
                              ↓
          Approve? → Ya          Tidak
                ↓                  ↓
         DendaPayment            DendaPayment
         .status =               .status =
         'terverifikasi'         'ditolak'
                ↓                  ↓
         Sisa Denda?         Loan.denda_status
         = 0 → Lunas         = 'menunggu_pembayaran'
         > 0 → Menunggu        (Siswa ulangi bayar)
              pembayaran
                ↓
    Loan.denda_status = 'lunas'
    Loan.denda = 0
```

---

## 3. 📁 FILE-FILE IMPLEMENTASI

### A. Database Models

#### `app/Models/Loan.php` ✅
**Status:** ✅ Lengkap
- **Fillable fields:** `tanggal_kembali_target`, `status_alat`, `harga_barang`, `denda`, `denda_status`
- **Relationship:** `dendaPayments()` - hasMany ke DendaPayment
- **Helper methods:**
  ```php
  hasDenda()                      // Ada denda?
  isDendaPaid()                   // Sudah lunas?
  isDendaWaitingVerification()    // Menunggu verifikasi?
  isDendaWaitingPayment()         // Menunggu pembayaran?
  isPendingDendaPayment()         // Ada denda pending?
  getLatestPendingDendaPayment()  // Ambil pembayaran pending terakhir
  ```

#### `app/Models/DendaPayment.php` ✅
**Status:** ✅ Lengkap
- **Fields:** `loan_id`, `jumlah_denda`, `jumlah_bayar`, `sisa_denda`, `metode_pembayaran`, 
           `bukti_pembayaran`, `status`, `catatan_petugas`, `tanggal_pembayaran`, 
           `tanggal_verifikasi`, `petugas_verifikasi_id`
- **Methods:**
  ```php
  isWaitingVerification()   // Status = menunggu_verifikasi?
  isVerified()              // Status = terverifikasi?
  isRejected()              // Status = ditolak?
  markAsVerified()          // Set terverifikasi oleh petugas
  markAsRejected()          // Set ditolak oleh petugas
  ```

### B. Helper Class

#### `app/Helpers/DendaHelper.php` ✅
**Status:** ✅ Lengkap
```php
public static function hitungDenda(
    $status_alat,              // 'baik', 'rusak', 'hilang'
    $harga_barang = 0,        // Harga item
    $tanggal_kembali_target,  // Deadline pengembalian
    $tanggal_kembali_actual   // Tanggal kembali sebenarnya
)
```

**Return:** Array dengan struktur
```php
[
    'total' => int,        // Total denda (Rp)
    'breakdown' => array,  // Daftar komponen
    'keterangan' => string // Penjelasan lengkap
]
```

**Contoh Output:**
- Alat baik tepat waktu: `['total' => 0, 'breakdown' => ['Alat kembali tepat waktu'], 'keterangan' => '...']`
- Terlambat 7 hari: `['total' => 35000, 'breakdown' => ['Keterlambatan 7 hari @ Rp 5.000/hari'], 'keterangan' => '...']`
- Rusak: `['total' => 250000, 'breakdown' => ['Alat rusak (50% × Rp 500.000)'], 'keterangan' => '...']`
- Hilang: `['total' => 500000, 'breakdown' => ['Alat hilang (100% × Rp 500.000)'], 'keterangan' => '...']`

### C. Controllers

#### `app/Http/Controllers/PetugasController.php::validateReturn()` ✅
**Status:** ✅ Lengkap
- **Input:** Jumlah baik, rusak, hilang, harga barang
- **Proses:**
  1. Validasi total jumlah = jumlah pinjam
  2. Hitung denda untuk rusak (50% harga) dan hilang (100% harga)
  3. Hitung keterlambatan untuk yang baik menggunakan `DendaHelper`
  4. Set `Loan.denda_status = 'menunggu_pembayaran'` atau `'tidak_ada_denda'`
  5. Update stok untuk barang yang baik
- **Output:** Redirect ke halaman validate-returns dengan pesan sukses

#### `app/Http/Controllers/DendaPaymentController.php` ✅
**Status:** ✅ Lengkap

**Method `store()`** - Siswa mengajukan pembayaran
- Input: Jumlah bayar, metode pembayaran, bukti (optional)
- Output: Membuat `DendaPayment` dengan status `'menunggu_verifikasi'`
- Update: `Loan.denda_status = 'menunggu_verifikasi'`

**Method `verify()`** - Petugas verifikasi pembayaran
- Input: Action (approve/reject), catatan (untuk reject)
- Jika approve:
  - `DendaPayment.status = 'terverifikasi'`
  - Jika `sisa_denda <= 0`: `Loan.denda_status = 'lunas'` dan `Loan.denda = 0`
  - Jika `sisa_denda > 0`: `Loan.denda_status = 'menunggu_pembayaran'` dan `Loan.denda = sisa_denda`
- Jika reject:
  - `DendaPayment.status = 'ditolak'`
  - `Loan.denda_status = 'menunggu_pembayaran'` (siswa bisa bayar ulang)

---

## 4. 🗄️ DATABASE SCHEMA

### Tabel `loans` - Kolom Denda:
```sql
- tanggal_kembali_target  : date, nullable
- status_alat             : enum('baik', 'rusak', 'hilang'), nullable
- harga_barang            : decimal(12,2), nullable
- alasan_denda            : text, nullable
- denda                   : decimal(12,2), default 0
- denda_status            : varchar, default 'tidak_ada_denda'
- status                  : varchar (returned saat sudah dikembalikan)
```

### Tabel `denda_payments`:
```sql
- id
- loan_id                 : foreign key
- jumlah_denda            : decimal(12,2)
- jumlah_bayar            : decimal(12,2)
- sisa_denda              : decimal(12,2)
- metode_pembayaran       : enum('tunai', 'transfer')
- bukti_pembayaran        : string, nullable
- status                  : enum('menunggu_verifikasi', 'terverifikasi', 'ditolak')
- catatan_petugas         : text, nullable
- tanggal_pembayaran      : datetime
- tanggal_verifikasi      : datetime, nullable
- petugas_verifikasi_id   : foreign key, nullable
- created_at / updated_at
```

---

## 5. 🎨 VIEWS/TAMPILAN

### Untuk Siswa:
1. **`siswa/denda-payments/index.blade.php`** - Daftar pembayaran denda
2. **`siswa/denda-payments/create.blade.php`** - Form input pembayaran denda
3. **`siswa/denda-payments/show.blade.php`** - Detail status pembayaran & denda
4. **`siswa/loans.blade.php`** - Menampilkan status denda di list peminjaman

### Untuk Petugas:
1. **`petugas/validate-returns.blade.php`** - Form validasi pengembalian + perhitungan denda
2. **`petugas/verify-denda-payments.blade.php`** - ✅ List pembayaran untuk diverifikasi
   - Menampilkan tabel pembayaran menunggu verifikasi
   - Modal untuk preview bukti pembayaran
   - Tombol approve/reject dengan catatan

---

## 6. ✅ STATUS KONDISI SISTEM

### Yang Sudah Diimplementasikan:
- ✅ Model Loan & DendaPayment lengkap dengan relationships
- ✅ DendaHelper untuk perhitungan otomatis
- ✅ Controller logic untuk validasi return & verifikasi pembayaran
- ✅ Database schema dengan kolom denda
- ✅ Views untuk siswa dan petugas
- ✅ Activity logging terintegrasi
- ✅ PDF export untuk struk & bukti pembayaran
- ✅ Alur 2-tahap: perhitungan denda → verifikasi pembayaran

### Fitur yang Berjalan:
1. **Perhitungan Denda Otomatis** - Berdasarkan status alat dan keterlambatan
2. **Verifikasi 2-Tahap** - Siswa bayar → petugas verifikasi
3. **Pembayaran Parsial** - Siswa bisa bayar sebagian, sisa menunggu pembayaran berikutnya
4. **Pencatatan Lengkap** - Semua transaksi tercatat di activity log
5. **Bukti Pembayaran** - Siswa bisa upload bukti transfer/pembayaran

---

## 7. 🔍 STATUS DENDA & ARTINYA

| Status Denda | Arti | Aksi User |
|---|---|---|
| `tidak_ada_denda` | Tidak ada denda | - |
| `menunggu_pembayaran` | Denda sudah ditetapkan, siswa harus bayar | Ajukan pembayaran |
| `menunggu_verifikasi` | Pembayaran sedang diproses petugas | Tunggu hasil verifikasi |
| `lunas` | Denda sudah dibayar & disetujui petugas | - |

---

## 8. 🔐 ALUR KEAMANAN & VALIDASI

### Validasi di Tingkat Siswa:
- ✅ Only pembayaran lewat form official (`DendaPaymentController@store`)
- ✅ Validasi jumlah bayar: min 1.000, max (denda + 1.000)
- ✅ Hanya siswa pemilik loan yang bisa bayar denda
- ✅ Check: apakah sudah ada pembayaran pending

### Validasi di Tingkat Petugas:
- ✅ Only role 'petugas' yang bisa verifikasi
- ✅ Harus input action (approve/reject) & catatan (jika reject)
- ✅ Preview bukti pembayaran sebelum verifikasi
- ✅ Validasi total pengembalian = jumlah pinjam

### Validasi di Tingkat Database:
- ✅ Foreign keys untuk referential integrity
- ✅ Enum untuk status pembayaran
- ✅ Decimal precision untuk nilai uang

---

## 9. 🚀 ROUTES/API ENDPOINTS

### Untuk Siswa:
```
GET  /siswa/denda-payments              → Lihat daftar pembayaran denda
GET  /siswa/denda-payments/{id}         → Detail pembayaran denda
GET  /siswa/loans/{loanId}/denda/create → Form bayar denda
POST /siswa/loans/{loanId}/denda        → Submit pembayaran denda
GET  /siswa/denda-payments/{id}/cetak   → Download bukti pembayaran (PDF)
```

### Untuk Petugas:
```
GET  /petugas/validate-returns          → Halaman validasi pengembalian
POST /petugas/loans/{loanId}/validate   → Submit validasi pengembalian + denda
GET  /petugas/denda-payments/verify     → Halaman verifikasi pembayaran denda
POST /petugas/denda-payments/{id}/verify → Submit verifikasi pembayaran
```

---

## 10. 📈 FLOW DIAGRAM LENGKAP

```
SISWA PINJAM ALAT
       ↓
Petugas setujui + set deadline
       ↓
SISWA KEMBALIKAN ALAT
       ↓
Petugas validasi (cek baik/rusak/hilang)
       ↓
Sistem hitung denda otomatis
  • Jika baik & tepat waktu → Denda = 0
  • Jika baik & terlambat → Denda keterlambatan
  • Jika rusak → Denda 50% harga
  • Jika hilang → Denda 100% harga
       ↓
  ADA DENDA?
    ↓      ↓
   YA      TIDAK
    ↓      ↓
  Status   Selesai
  menunggu
  pembayaran
    ↓
SISWA BAYAR DENDA
    ↓
Buat DendaPayment
Status = menunggu_verifikasi
    ↓
PETUGAS VERIFIKASI
    ↓
Approve?
  ↓           ↓
 YA          TIDAK
  ↓           ↓
Sisa > 0?   Status ditolak
  ↓           ↓
YA   TIDAK   Siswa ulangi
 ↓    ↓
Mp.  Denda   pembayaran
W.P  lunas
 ↓
Menunggu
pembayaran
sisa
```

---

## 11. ⚠️ HAL YANG PERLU DIPERHATIKAN

### Positif ✅
- Alur logic sangat terstruktur dan jelas
- Validasi 2-tahap mencegah pembayaran palsu
- Database schema normalized dan konsisten
- Activity logging lengkap untuk audit trail
- Perhitungan denda otomatis mengurangi human error

### Catatan 📝
1. **Harga Barang** - Hanya di-input saat validasi return, bukan saat peminjaman
   - Ini memungkinkan harga berubah seiring waktu
   - Pastikan petugas input harga dengan akurat

2. **Pembayaran Parsial** - Sistem mendukung pembayaran bertahap
   - Jika jumlah_bayar < denda, sisa harus dibayar lagi
   - Maksimal pembayaran 1.000 lebih dari denda untuk fleksibilitas

3. **Bukti Pembayaran** - Optional tapi recommended
   - Jika transfer, upload bukti untuk memudahkan verifikasi
   - Jika tunai, bisa langsung di-verifikasi tanpa bukti

4. **Timezone** - Pastikan menggunakan 'Asia/Jakarta'
   - Penting untuk perhitungan keterlambatan yang akurat

---

## 12. 🎯 KESIMPULAN

Sistem denda di proyek ini **sudah lengkap dan terintegrasi dengan baik**. 
Implementasi mengikuti best practices dengan:
- ✅ Separation of concerns (Helper, Model, Controller)
- ✅ Validasi bertingkat (User, Petugas, Database)
- ✅ Audit trail lengkap (Activity Log)
- ✅ Alur transparan (2-tahap verifikasi)

**Rekomendasi:** Sistem siap digunakan dan tidak memerlukan perbaikan urgent.

