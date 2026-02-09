# ✅ CHECKLIST REQUIREMENT UKK - APLIKASI PEMINJAMAN ALAT

## 📊 STATUS IMPLEMENTASI

### ✅ SUDAH LENGKAP (DONE)

#### 1. **Struktur Data & Akses**
- [x] Database Schema dengan relasi tabel (users, loans, tools, categories, denda_payments, activity_logs)
- [x] Models Laravel dengan relationships
- [x] Migration files untuk semua tabel
- [x] Type casting dan data validation

#### 2. **Implementasi Kode**
- [x] Full aplikasi Laravel 11
- [x] Authentication & Authorization (role-based: admin, petugas, siswa)
- [x] CRUD untuk alat, kategori, peminjaman, pengembalian
- [x] Sistem denda dengan verifikasi petugas
- [x] Dashboard untuk setiap role
- [x] Activity logging

#### 3. **Folder Project**
- [x] Struktur Laravel standar
- [x] Routes, Controllers, Models, Views
- [x] Database migrations
- [x] Helpers dan configuration

#### 4. **Coding Guidelines & Best Practices**
- [x] Query yang efisien (with eager loading)
- [x] Hindari N+1 query problems
- [x] Pagination untuk data besar
- [x] Validation di controller
- [x] Error handling
- [x] Code organization yang rapi

---

## ❌ MASIH KURANG (TODO)

### **P1: CRITICAL - WAJIB UNTUK NILAI A**

#### 1. **📐 ERD (Entity Relationship Diagram)**
- [ ] Diagram visual menunjukkan:
  - Semua tabel (users, tools, categories, loans, denda_payments, activity_logs)
  - Relasi antar tabel (1-to-many, many-to-many)
  - Primary key, foreign key
  - Atribut setiap tabel dengan tipe data
- **Format:** PNG/JPG/SVG
- **Tools:** Lucidchart, Draw.io, atau Visio
- **Estimasi:** 15 menit

#### 2. **📋 Flowchart & Pseudocode (3 Proses)**

**a) Flowchart Login**
- [ ] Start → Input username/password → Validasi → Check role → Redirect dashboard
- **Output:** PNG/SVG

**b) Flowchart Peminjaman Alat**
- [ ] Request → Check stok → Approve/Reject → Update stok → Log aktivitas → End
- **Output:** PNG/SVG

**c) Flowchart Pengembalian & Perhitungan Denda**
- [ ] Return request → Input jumlah baik/rusak/hilang → Validasi jumlah → Hitung denda → Verifikasi petugas → Update stok → End
- **Output:** PNG/SVG

**d) Pseudocode untuk 3 proses di atas**
- [ ] Dalam bentuk text/document

---

#### 3. **🗄️ Database Operations (CRITICAL)**

**A. Stored Procedures (Minimal 3-5):**
- [ ] `sp_hitungDenda` - Hitung denda berdasarkan status barang & keterlambatan
- [ ] `sp_prosesValidasiPengembalian` - Validasi pengembalian & update data
- [ ] `sp_updateStokOtomatis` - Update stok saat peminjaman/pengembalian
- [ ] `sp_verifikasiPembayaranDenda` - Proses verifikasi pembayaran
- [ ] `sp_generateLaporan` - Generate laporan peminjaman

**B. Database Functions (Minimal 2-3):**
- [ ] `fn_hitungDendaKeterlambatan` - Hitung denda keterlambatan
- [ ] `fn_cekStokTersedia` - Check stok tersedia
- [ ] `fn_totalDendaPending` - Hitung total denda yang belum lunas

**C. Database Triggers (Minimal 2-3):**
- [ ] `tr_afterLoanInsert` - Kurangi stok saat peminjaman dibuat
- [ ] `tr_afterLoanReturn` - Kembalikan stok saat pengembalian
- [ ] `tr_afterDendaVerification` - Update loan status saat denda terverifikasi

**D. Transaction Management:**
- [ ] Contoh kode dengan BEGIN TRANSACTION, COMMIT, ROLLBACK
- [ ] Skenario: Peminjaman gagal → rollback stok

**E. SQL Dump/Export:**
- [ ] File `ukk_perpustakaan.sql` - Database lengkap dengan data

---

#### 4. **📖 Dokumentasi Modul (Per Function/Method)**

Format untuk setiap modul:
```
Modul: [Nama Modul]
Controller: [NamaController.php]
Method: [methodName()]

INPUT:
- Parameter 1: [tipe data] [deskripsi]
- Parameter 2: [tipe data] [deskripsi]

PROSES:
1. [Validasi input]
2. [Query database]
3. [Proses logika]
4. [Return hasil]

OUTPUT:
- [Tipe return data] - [Deskripsi]
- Contoh response

FUNGSI/PROSEDUR YANG DIGUNAKAN:
- [Nama function] - [Deskripsi]
- [Database stored procedure] - [Deskripsi]

ERROR HANDLING:
- [Error case 1] → [Handling]
- [Error case 2] → [Handling]
```

**Modul yang perlu didokumentasikan:**
- [ ] Authentication (login, logout, register)
- [ ] Approve Loans (approveLoans)
- [ ] Validate Returns (validateReturn)
- [ ] Process Denda Payment (store, verify)
- [ ] Tools Management (toolsIndex, create, update, delete)
- [ ] Reports (generateReport)

---

### **P2: PENTING - UNTUK NILAI BAIK**

#### 5. **🧪 Test Cases (Minimal 5 Skenario)**

Format Test Case:
```
Test Case ID: [TC-001]
Nama: [Deskripsi test]
Tujuan: [Apa yang ditest]
Prasyarat: [Data/kondisi awal]

LANGKAH TEST:
1. [Aksi]
2. [Aksi]

HASIL YANG DIHARAPKAN:
- [Ekspektasi]

HASIL AKTUAL:
- [Apa yang terjadi]

STATUS: ✅ PASS / ❌ FAIL

SCREENSHOT: [Lampiran]
```

**5 Skenario Wajib:**

**TC-001: Login dengan kredensial yang benar**
- [ ] Aksi: Login dengan username & password benar
- [ ] Expected: Berhasil login, redirect ke dashboard
- [ ] Screenshot: ✓

**TC-002: Login dengan kredensial yang salah**
- [ ] Aksi: Login dengan password salah
- [ ] Expected: Gagal, muncul error message
- [ ] Screenshot: ✓

**TC-003: Tambah Alat (Admin)**
- [ ] Aksi: Admin menambah alat baru
- [ ] Expected: Alat berhasil ditambah, muncul di daftar
- [ ] Screenshot: ✓

**TC-004: Pinjam Alat (Siswa)**
- [ ] Aksi: Siswa mengajukan peminjaman
- [ ] Expected: Request dibuat, menunggu approval petugas
- [ ] Screenshot: ✓

**TC-005: Kembalikan Alat & Perhitungan Denda**
- [ ] Aksi: Pengembalian 50 alat (40 baik, 5 rusak, 5 hilang)
- [ ] Expected: Denda dihitung per kategori, terverifikasi petugas
- [ ] Screenshot: ✓

**TC-006: Cek Privilege User (Optional)**
- [ ] Aksi: User siswa coba akses halaman admin
- [ ] Expected: Forbidden/403
- [ ] Screenshot: ✓

---

#### 6. **📝 Laporan Evaluasi Singkat**

**A. Fitur yang sudah berjalan dengan baik:**
- [ ] List fitur ✅ dengan deskripsi
- [ ] Minimal 8-10 fitur

**B. Bug yang belum diperbaiki:**
- [ ] List bug ❌ (jika ada)
- [ ] Deskripsi masalah
- [ ] Rencana fixing

**C. Rencana Pengembangan Berikutnya:**
- [ ] Fitur yang bisa ditambahkan
- [ ] Improvement dari fitur yang ada
- [ ] Timeline estimasi

---

#### 7. **🐛 Dokumentasi Debugging**

- [ ] Error handling di setiap controller
- [ ] Validasi input
- [ ] Exception handling
- [ ] Logging untuk troubleshooting

---

## 📌 RINGKASAN YANG MASIH KURANG

| No | Item | Priority | Status | Estimasi |
|---|---|---|---|---|
| 1 | ERD Diagram | P1 | ❌ | 15 min |
| 2 | Flowchart (3) | P1 | ❌ | 30 min |
| 3 | Pseudocode | P1 | ❌ | 15 min |
| 4 | Stored Procedures (5) | P1 | ❌ | 45 min |
| 5 | Database Functions (3) | P1 | ❌ | 20 min |
| 6 | Database Triggers (3) | P1 | ❌ | 20 min |
| 7 | Transaction Examples | P1 | ❌ | 15 min |
| 8 | SQL Dump (.sql) | P1 | ❌ | 5 min |
| 9 | Dokumentasi Modul | P2 | ❌ | 60 min |
| 10 | Test Cases (6) | P2 | ❌ | 45 min |
| 11 | Screenshots Testing | P2 | ❌ | 30 min |
| 12 | Laporan Evaluasi | P2 | ❌ | 20 min |
| 13 | Dokumentasi Debugging | P2 | ❌ | 15 min |
| | **TOTAL** | | | **~5 jam** |

---

## 🎯 REKOMENDASI PENGERJAAN

**Hari 1 (P1 - Critical):**
1. ERD Diagram (15 min)
2. Flowchart & Pseudocode (45 min)
3. Stored Procedures + Functions + Triggers (85 min)
4. Transaction Examples (15 min)
5. SQL Dump (5 min)
**Total: ~3 jam**

**Hari 2 (P2 - Important):**
1. Dokumentasi Modul (60 min)
2. Test Cases + Screenshots (75 min)
3. Laporan Evaluasi (20 min)
4. Debugging Documentation (15 min)
**Total: ~2.5 jam**

---

## 📂 FILE OUTPUT YANG DIPERLUKAN

```
📁 UKK_SUBMISSION/
├── 📁 PROJECT_CODE/
│   ├── (folder aplikasi Laravel)
│   └── (semua file kode)
├── 📁 DATABASE/
│   ├── ukk_perpustakaan.sql
│   ├── stored_procedures.sql
│   ├── functions.sql
│   ├── triggers.sql
│   └── transactions_example.sql
├── 📁 DOCUMENTATION/
│   ├── ERD.png
│   ├── Flowchart_Login.png
│   ├── Flowchart_Peminjaman.png
│   ├── Flowchart_Pengembalian.png
│   ├── Pseudocode.txt
│   ├── Module_Documentation.docx
│   ├── Test_Cases.xlsx
│   ├── Test_Screenshots/ (folder dengan SS)
│   ├── Evaluation_Report.docx
│   └── Debugging_Documentation.txt
└── 📄 README.md
```

---

**Siap mulai? Saya bisa buatkan semua ini untuk Anda! 🚀**
