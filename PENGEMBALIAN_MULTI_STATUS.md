# PERBAIKAN SISTEM PENGEMBALIAN BARANG MULTI-STATUS

## Masalah yang Diperbaiki
**LAMA**: Sistem hanya track 1 status alat untuk seluruh peminjaman
- Pinjam 50 barang → Validasi: 1 status (baik/rusak/hilang) untuk semua 50
- Tidak bisa track berapa yang baik, rusak, atau hilang

**BARU**: Sistem track jumlah barang per status pengembalian
- Pinjam 50 barang → Validasi: Input 40 baik, 5 rusak, 5 hilang
- Denda dihitung per-kategori dengan benar
- Stok hanya bertambah untuk yang baik

---

## Database Changes

### Migration: `2026_01_26_180000_add_pengembalian_details_to_loans_table.php`

```sql
ALTER TABLE loans ADD COLUMN jumlah_kembali_baik INT DEFAULT 0;
ALTER TABLE loans ADD COLUMN jumlah_kembali_rusak INT DEFAULT 0;
ALTER TABLE loans ADD COLUMN jumlah_kembali_hilang INT DEFAULT 0;
```

**Kolom Baru:**
- `jumlah_kembali_baik` - Jumlah barang yang dikembalikan dalam kondisi baik
- `jumlah_kembali_rusak` - Jumlah barang yang dikembalikan dalam kondisi rusak
- `jumlah_kembali_hilang` - Jumlah barang yang hilang

---

## Model Updates

### Loan Model
Tambah kolom ke `$fillable`:
```php
'jumlah_kembali_baik',
'jumlah_kembali_rusak',
'jumlah_kembali_hilang',
```

---

## View Changes

### Petugas Validate Returns Form

**LAMA:**
```html
<select name="status_alat">
  <option>Baik</option>
  <option>Rusak</option>
  <option>Hilang</option>
</select>
```

**BARU:**
```html
Jumlah Barang Baik: [input number]
Jumlah Barang Rusak: [input number]
Jumlah Barang Hilang: [input number]
Harga Barang (Rp): [input text]
```

**Features:**
- ✅ Real-time validation: Total harus = jumlah pinjam
- ✅ Conditional harga input: Hanya tampil jika ada rusak/hilang
- ✅ Error message jika total tidak sesuai
- ✅ Buttons disabled sampai data valid

---

## Controller Changes

### PetugasController::validateReturn()

**Logic Baru:**

1. **Validasi Input:**
   - Check `jumlah_kembali_baik + rusak + hilang == jumlah_pinjam`

2. **Hitung Denda Per-Kategori:**
   - **Yang Baik**: Denda keterlambatan (Rp 5.000/hari)
   - **Yang Rusak**: 50% × (jumlah × harga barang)
   - **Yang Hilang**: 100% × (jumlah × harga barang)

3. **Update Stok:**
   - Hanya return yang baik: `stok += jumlah_baik`
   - Yang rusak/hilang: Tidak direturn (hilang dari sistem)

4. **Update Loan:**
   ```php
   $loan->update([
       'jumlah_kembali_baik' => $jumlahBaik,
       'jumlah_kembali_rusak' => $jumlahRusak,
       'jumlah_kembali_hilang' => $jumlahHilang,
       'status_alat' => 'validated',
       'denda' => $denda_total,
       'denda_status' => 'menunggu_pembayaran|tidak_ada_denda'
   ]);
   ```

5. **Log Activity:**
   ```
   "Validasi pengembalian alat (Baik: 40, Rusak: 5, Hilang: 5) - Denda: Rp 200.000"
   ```

---

## Contoh Skenario Sebelum vs Sesudah

### Skenario: Pinjam 50 barang, kembalikan 40 baik, 5 rusak, 5 hilang
**Harga barang: Rp 100.000/unit, Keterlambatan 5 hari**

**SEBELUM (LAMA):**
```
Status Alat: [Single choice] Rusak/Baik/Hilang
Denda: Unclear - bisa 0, bisa 50%, atau 100%
Stok: Tidak clear naik berapa
```

**SESUDAH (BARU):**
```
✓ Baik: 40 barang
   Denda Keterlambatan: 40 × Rp 5.000 × 5 hari = Rp 1.000.000

⚠️ Rusak: 5 barang
   Denda: 5 × (Rp 100.000 × 50%) = Rp 250.000

✗ Hilang: 5 barang
   Denda: 5 × Rp 100.000 = Rp 500.000

Total Denda: Rp 1.750.000
Stok Return: +40 (yang baik saja)
```

---

## Views Update

### `resources/views/petugas/validate-returns.blade.php`

1. **Modal Form:**
   - 3 input number untuk baik/rusak/hilang
   - 1 input harga barang (conditional)
   - Real-time validation

2. **Informasi Display:**
   - Jumlah pinjam ditampilkan
   - Validasi status live
   - Error message jika total tidak sesuai

3. **JavaScript Features:**
   ```javascript
   validateJumlah()           // Check total = pinjam
   checkNeedHargaBarang()     // Show/hide harga input
   formatCurrency()           // Format Rp display
   ```

---

## Keuntungan Perubahan

✅ **Akurasi Perhitungan Denda**
- Denda untuk masing-masing kategori dihitung dengan benar
- Tidak ada denda untuk barang yang baik dan tepat waktu

✅ **Tracking Stok yang Jelas**
- Hanya barang yang baik yang dikembalikan ke stok
- Barang rusak/hilang tercatat dengan jelas

✅ **Transparansi untuk Siswa**
- Laporan detail: berapa baik, rusak, hilang
- Denda detail per-kategori

✅ **Audit Trail**
- Setiap pengembalian terekam dengan detail
- Mudah untuk tracking barang hilang/rusak

---

## Testing Checklist

- [ ] Input 50 baik, 0 rusak, 0 hilang → Total = 50 ✓
- [ ] Input 40 baik, 5 rusak, 5 hilang → Total = 50 ✓
- [ ] Input 40 baik, 5 rusak, 6 hilang → Error message muncul
- [ ] Harga barang field hanya tampil jika ada rusak/hilang
- [ ] Stok bertambah sesuai jumlah_baik saja
- [ ] Denda dihitung dengan benar per-kategori
- [ ] Log activity menampilkan detail breakdown

---

## Environment
- Framework: Laravel 11
- Database: MySQL
- Date: 26 January 2026
