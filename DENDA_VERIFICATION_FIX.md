# DOKUMENTASI ALUR VERIFIKASI DENDA (PERBAIKAN)

## Masalah yang Diperbaiki
**LAMA**: Siswa membayar denda → langsung status denda menjadi lunas, tanpa validasi dari petugas
**BARU**: Siswa membayar denda → menunggu verifikasi petugas → baru kemudian denda dianggap lunas

---

## Alur Sistem Denda yang Baru

### 1. **VALIDASI PENGEMBALIAN ALAT (Petugas)**
- Petugas melakukan validasi pengembalian alat
- Sistem menghitung denda berdasarkan status alat (baik/rusak/hilang)
- **Status Denda**: `menunggu_pembayaran` (Denda telah ditetapkan, menunggu siswa membayar)
- Status Loan: `returned`

**Database Changes:**
```
Column baru: denda_status (default: 'tidak_ada_denda')
Value: 'menunggu_pembayaran'
```

### 2. **PEMBAYARAN DENDA (Siswa)**
- Siswa mengajukan pembayaran denda
- Siswa dapat membayar sebagian atau penuh
- Membuat record di tabel `denda_payments`
- **Status Pembayaran**: `menunggu_verifikasi`
- **Status Denda Loan**: `menunggu_verifikasi`

**Database Changes:**
```
Loan.denda_status = 'menunggu_verifikasi'
DendaPayment.status = 'menunggu_verifikasi'
```

### 3. **VERIFIKASI PEMBAYARAN (Petugas)**
Petugas mengecek pembayaran di halaman `/petugas/verify-denda-payments`

#### **JIKA DISETUJUI:**
- DendaPayment.status = `terverifikasi`
- Jika sisa_denda == 0:
  - Loan.denda_status = `lunas`
  - Loan.denda = 0
- Jika sisa_denda > 0:
  - Loan.denda_status = `menunggu_pembayaran` (menunggu pembayaran sisa)
  - Loan.denda = sisa_denda

#### **JIKA DITOLAK:**
- DendaPayment.status = `ditolak`
- Loan.denda_status = `menunggu_pembayaran` (siswa harus membayar ulang)
- Siswa dapat mengajukan pembayaran baru

---

## Status Denda Loan

| Status | Arti | Aksi Siswa |
|--------|------|-----------|
| `tidak_ada_denda` | Tidak ada denda | - |
| `menunggu_pembayaran` | Denda sudah ditetapkan | Ajukan pembayaran |
| `menunggu_verifikasi` | Pembayaran sedang diproses petugas | Tunggu verifikasi |
| `lunas` | Denda sudah dibayar dan terverifikasi | - |
| `ditolak` | Pembayaran ditolak (ditampilkan via DendaPayment) | Ajukan ulang pembayaran |

---

## Helper Methods di Model Loan

```php
hasDenda()                  // Check apakah ada denda
isDendaPaid()              // Check apakah denda sudah lunas
isDendaWaitingVerification() // Check apakah denda menunggu verifikasi
isDendaWaitingPayment()    // Check apakah denda menunggu pembayaran
isPendingDendaPayment()    // Check apakah ada denda yang belum selesai
getLatestPendingDendaPayment() // Get pembayaran denda terakhir yang pending
```

---

## Views yang Terupdate

### 1. **Siswa**
- `resources/views/siswa/loans.blade.php` - Menampilkan status denda
- `resources/views/siswa/denda-payments/show.blade.php` - Menampilkan status pembayaran dan denda

### 2. **Petugas**
- `resources/views/petugas/validate-returns.blade.php` - Form validasi pengembalian
- `resources/views/petugas/verify-denda-payments.blade.php` - List pembayaran untuk diverifikasi

---

## Controllers yang Terupdate

### 1. **PetugasController::validateReturn()**
- Menambahkan logic untuk set `denda_status = 'menunggu_pembayaran'` saat denda ada
- Menambahkan logic untuk set `denda_status = 'tidak_ada_denda'` saat tidak ada denda

### 2. **DendaPaymentController::store()**
- Saat siswa mengajukan pembayaran, update `loan.denda_status = 'menunggu_verifikasi'`

### 3. **DendaPaymentController::verify()**
- **Jika approve:**
  - Check sisa_denda
  - Jika = 0: set `denda_status = 'lunas'`, `denda = 0`
  - Jika > 0: set `denda_status = 'menunggu_pembayaran'`, `denda = sisa_denda`
- **Jika reject:**
  - Set `denda_status = 'menunggu_pembayaran'`
  - Siswa bisa ajukan pembayaran baru

---

## Database Migration

File: `database/migrations/2026_01_26_173000_add_denda_status_column_to_loans_table.php`

```sql
ALTER TABLE loans ADD COLUMN denda_status VARCHAR(255) DEFAULT 'tidak_ada_denda';
```

---

## Alur Lengkap Dari User Perspective

### **SISWA:**
1. Pinjam alat
2. Kembalikan alat (status masih pending, menunggu petugas validasi)
3. Petugas validasi pengembalian → denda ditetapkan (jika ada)
4. ✅ **[BARU]** Siswa melihat notifikasi "💸 Menunggu Pembayaran"
5. Siswa mengajukan pembayaran denda (upload bukti jika transfer)
6. ✅ **[BARU]** Status berubah menjadi "⏳ Menunggu Verifikasi"
7. Tunggu petugas verifikasi...
8. ✅ **[BARU]** Petugas approve/reject pembayaran
   - **Approve**: Status "✓ Denda Lunas"
   - **Reject**: Kembali ke "💸 Menunggu Pembayaran"

### **PETUGAS:**
1. Validasi pengembalian alat
2. Hitung dan catat denda
3. Tunggu pembayaran dari siswa
4. **[BARU]** Buka halaman "Verifikasi Pembayaran Denda"
5. **[BARU]** Review bukti pembayaran
6. **[BARU]** Approve atau reject pembayaran

---

## Testing Checklist

- [ ] Siswa membayar denda → status jadi "⏳ Menunggu Verifikasi"
- [ ] Petugas approve pembayaran (sisa = 0) → status jadi "✓ Denda Lunas"
- [ ] Petugas approve pembayaran (sisa > 0) → status jadi "💸 Menunggu Pembayaran"
- [ ] Petugas reject pembayaran → status jadi "💸 Menunggu Pembayaran"
- [ ] Siswa bisa ajukan pembayaran baru setelah reject
- [ ] View loans menampilkan status denda dengan benar
- [ ] Laporan petugas menampilkan status denda dengan benar

---

## Environment
- Framework: Laravel 11
- Database: MySQL
- Date: 26 January 2026
