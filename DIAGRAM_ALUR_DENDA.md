# 🔄 DIAGRAM ALUR DENDA - PERPUSTAKAAN UKK

## 1. ALUR UMUM PEMINJAMAN → DENDA

```
┌──────────────────────────────────────────────────────────────────┐
│                    SIKLUS PEMINJAMAN ALAT                          │
└──────────────────────────────────────────────────────────────────┘

    SISWA BUAT PERMINTAAN PINJAM
              ↓
    Form input: alat, jumlah, tujuan
              ↓
    Status Loan: "pending"
              ↓
    ┌─────────────────────────────────────────────┐
    │   PETUGAS REVIEW & SETUJUI PEMINJAMAN       │
    │   Input: Durasi pinjam (1-90 hari)          │
    └─────────────────────────────────────────────┘
              ↓
    Hitung deadline: tanggal_pinjam + durasi
    tanggal_kembali_target = calculated
    Status Loan: "approved"
    Stok alat berkurang
              ↓
    ┌─────────────────────────────────────────────┐
    │        SISWA GUNAKAN ALAT                    │
    │        (Periode peminjaman aktif)           │
    └─────────────────────────────────────────────┘
              ↓
    SISWA KEMBALIKAN ALAT KE PETUGAS
              ↓
    ┌──────────────────────────────────────────────────────────────┐
    │      TAHAP 1: PETUGAS VALIDASI PENGEMBALIAN                  │
    ├──────────────────────────────────────────────────────────────┤
    │ Input:                                                        │
    │  • Jumlah kembali baik     (integer)                         │
    │  • Jumlah kembali rusak    (integer)                         │
    │  • Jumlah kembali hilang   (integer)                         │
    │  • Harga barang            (decimal, untuk rusak/hilang)     │
    │                                                              │
    │ Validasi: Total kembali = Jumlah pinjam                     │
    │                                                              │
    │ Proses Perhitungan Denda:                                   │
    │  1. Jika ada rusak: Denda = 50% × harga × jumlah rusak      │
    │  2. Jika ada hilang: Denda = 100% × harga × jumlah hilang   │
    │  3. Jika baik tapi terlambat:                               │
    │     Denda keterlambatan = (hari_terlambat) × Rp 5.000/hari  │
    │  4. Total Denda = Rusak + Hilang + Keterlambatan           │
    │                                                              │
    │ Output:                                                      │
    │  • Loan.denda = total denda (Rp)                            │
    │  • Loan.denda_status = "menunggu_pembayaran" (jika ada)     │
    │  • Loan.denda_status = "tidak_ada_denda" (jika 0)          │
    │  • Loan.status = "returned"                                 │
    │  • Stok alat bertambah (untuk yang baik)                    │
    └──────────────────────────────────────────────────────────────┘
              ↓
    ADA DENDA?
      ↙        ↖
    TIDAK      YA
      ↓         ↓
    Selesai   ┌─────────────────────────────────────────┐
              │  TAHAP 2: VERIFIKASI PEMBAYARAN          │
              ├─────────────────────────────────────────┤
              │                                         │
              │  SISWA AJUKAN PEMBAYARAN DENDA          │
              │  Input:                                 │
              │   • Jumlah bayar (1.000 - denda+1.000) │
              │   • Metode (tunai / transfer)          │
              │   • Bukti pembayaran (optional)        │
              │                                         │
              │  Buat record DendaPayment:             │
              │   • status = "menunggu_verifikasi"     │
              │   • sisa_denda = denda - jumlah_bayar  │
              │   • Loan.denda_status = "menunggu_verifikasi"
              │                                         │
              │  PETUGAS VERIFIKASI PEMBAYARAN         │
              │  Review:                               │
              │   • Cek bukti pembayaran (jika ada)   │
              │   • Validasi jumlah bayar             │
              │                                         │
              │  Keputusan:                            │
              │   • Approve → DendaPayment terverifikasi
              │   • Reject → DendaPayment ditolak     │
              │                                         │
              └─────────────────────────────────────────┘
                        ↓
              ┌─────────────┴──────────────┐
              ↓                            ↓
          DISETUJUI                   DITOLAK
              ↓                            ↓
    DendaPayment             DendaPayment
    .status =                .status =
    "terverifikasi"          "ditolak"
              ↓                            ↓
    Sisa Denda = 0?          Loan.denda_status
    ↓              ↓         = "menunggu_pembayaran"
   YA             TIDAK              ↓
    ↓              ↓          Siswa bisa ajukan
  Loan        Loan          pembayaran baru
  .denda_status  .denda_status
  = "lunas"  = "menunggu_pembayaran"
  .denda = 0 .denda = sisa_denda
    ↓              ↓
 SELESAI      MENUNGGU PEMBAYARAN
              SISA DENDA
              (Ulangi: siswa bayar lagi)
```

---

## 2. STATE MACHINE - STATUS DENDA

```
                    ┌──────────────────────┐
                    │  tidak_ada_denda     │
                    │  (No Fine)           │
                    └──────────────────────┘
                            ↑
                            │
                    TIDAK ADA KERUSAKAN/HILANG
                    TIDAK TERLAMBAT
                            │
    ┌───────────────────────┴───────────────────────┐
    │                                               │
    ↓                                               ↓
RUSAK/HILANG/TERLAMBAT                    ALAT DIKEMBALIKAN BAIK
    │                                      TEPAT WAKTU
    │
    ├───────────────────────────────────────┐
    │                                       │
    ↓                                       ↓
┌──────────────────┐        ┌──────────────────────────┐
│menunggu_pembayaran│        │  SISTEM CALC DENDA      │
│(Waiting Payment)  │        │  1. Check status alat   │
│                  │        │  2. Check keterlambatan │
│ Denda ditetapkan │        │  3. Hitung total        │
│ Siswa harus bayar│        │  4. Set status          │
└──────────────────┘        └──────────────────────────┘
    ↑       │                         │
    │       │                         │
    │       │ Siswa ajukan pembayaran │
    │       │ Create DendaPayment     │
    │       ↓
    │  ┌──────────────────────┐
    │  │menunggu_verifikasi   │
    │  │(Verification Pending)│
    │  │                      │
    │  │ Pembayaran submitted │
    │  │ Tunggu petugas cek   │
    │  └──────────────────────┘
    │       │         │
    │       │         │
    │  Tolak│         │Setujui
    │       │         │
    │       ↓         ↓
    │   ┌────┐   ┌──────────────────┐
    │   │ ✗  │   │ Sisa Denda = 0?  │
    └───┤REJECT│   └──────────────────┘
        └────┘         │          │
      (Reject)        YA          TIDAK
                       │           │
                       ↓           ↓
                   ┌─────────┐ ┌──────────────┐
                   │  lunas  │ │  menunggu_   │
                   │ (Paid)  │ │  pembayaran  │
                   └─────────┘ └──────────────┘
                       ↑           │
                       │           │
                       │ Sisa bayar │
                       └───────────→┘
```

---

## 3. DETAIL PERHITUNGAN DENDA

```
INPUT VALIDASI RETURN:
  jumlah_baik, jumlah_rusak, jumlah_hilang, harga_barang
  tanggal_kembali_target, tanggal_kembali_actual

        ↓

STEP 1: CEK KERUSAKAN/HILANG
  
  IF jumlah_rusak > 0:
    denda_rusak = jumlah_rusak × harga_barang × 50%
    breakdown[] << "Rusak: Rp X"
  
  IF jumlah_hilang > 0:
    denda_hilang = jumlah_hilang × harga_barang × 100%
    breakdown[] << "Hilang: Rp X"

        ↓

STEP 2: CEK KETERLAMBATAN (HANYA UNTUK YANG BAIK)
  
  IF jumlah_baik > 0 AND status_alat == 'baik':
    hari_terlambat = tanggal_actual - tanggal_target
    
    IF hari_terlambat > 0:
      denda_terlambat = hari_terlambat × Rp 5.000
      breakdown[] << "Terlambat: Rp X"
    ELSE:
      breakdown[] << "Tepat waktu"

        ↓

STEP 3: JUMLAH TOTAL
  
  total_denda = denda_rusak + denda_hilang + denda_terlambat
  keterangan = implode(breakdown, " | ")

        ↓

OUTPUT:
  [
    'total' => Rp X,
    'breakdown' => [array],
    'keterangan' => 'String penjelasan'
  ]

        ↓

UPDATE DATABASE:
  Loan.denda = total_denda
  Loan.alasan_denda = keterangan
  Loan.denda_status = (total_denda > 0) ? 'menunggu_pembayaran' : 'tidak_ada_denda'
  Loan.status = 'returned'
```

---

## 4. TABEL METODE PEMBAYARAN & VERIFIKASI

```
┌──────────────────────────────────────────────────────────────────┐
│                    METODE PEMBAYARAN DENDA                        │
├──────────────────────────────────────────────────────────────────┤
│                                                                  │
│  TUNAI                                │  TRANSFER                │
│  ────────────────────────────────────┼──────────────────────────│
│  • Bayar langsung ke petugas         │  • Siswa transfer ke    │
│  • Bukti: Tidak wajib                │    rekening sekolah     │
│  • Verifikasi: Instant               │  • Bukti: Screenshot    │
│  • Proses: Cepat                     │    atau print transfer   │
│  • Keamanan: Pembayaran langsung     │  • Verifikasi: Petugas  │
│                                      │    cek bukti & rekening │
│                                      │  • Proses: 1-2 jam      │
│                                      │  • Keamanan: Terdokumen │
│                                      │                        │
└──────────────────────────────────────────────────────────────────┘

WORKFLOW VERIFIKASI:

  Siswa submit pembayaran
  (metode + jumlah + bukti optional)
         ↓
  Sistem create DendaPayment
  Status: menunggu_verifikasi
         ↓
  Petugas buka halaman verifikasi
  (/petugas/denda-payments/verify)
         ↓
  Petugas klik "Periksa" pada pembayaran
         ↓
  Modal terbuka dengan:
  • Nama siswa
  • Preview bukti pembayaran (jika ada)
  • Buttons: Terima / Tolak / Batal
         ↓
  Petugas review:
  • Apakah bukti valid?
  • Apakah jumlah sesuai?
  • Apakah metode sesuai?
         ↓
  Petugas klik Terima/Tolak
         ↓
  Jika Terima:
    DendaPayment.status = terverifikasi
    DendaPayment.tanggal_verifikasi = now()
    DendaPayment.petugas_verifikasi_id = petugas_id
    
    Cek sisa_denda:
      IF sisa_denda = 0:
        Loan.denda_status = lunas
        Loan.denda = 0
      ELSE:
        Loan.denda_status = menunggu_pembayaran
        Loan.denda = sisa_denda
         ↓
      [SELESAI]
  
  Jika Tolak:
    DendaPayment.status = ditolak
    DendaPayment.catatan_petugas = [input catatan]
    Loan.denda_status = menunggu_pembayaran
    (Siswa harus bayar ulang)
         ↓
      [SISWA DAPAT AJUKAN PEMBAYARAN BARU]
```

---

## 5. INTERAKSI ANTAR ENTITY

```
┌─────────────┐
│   User      │
│  (Siswa)    │
└─────────────┘
      │
      │ has many
      ↓
  ┌─────────┐
  │  Loans  │
  └─────────┘
      │
      ├─ has columns:
      │  • denda (Rp amount)
      │  • denda_status (status)
      │  • tanggal_kembali_target
      │  • status_alat
      │  • harga_barang
      │
      ↓ has many
  ┌────────────────┐
  │ DendaPayment   │
  └────────────────┘
      │
      ├─ has columns:
      │  • jumlah_denda (total denda)
      │  • jumlah_bayar (dibayar)
      │  • sisa_denda
      │  • status (menunggu/terverifikasi/ditolak)
      │  • petugas_verifikasi_id (FK)
      │
      ↓ belongs to
  ┌────────────┐
  │ User       │  (Petugas verifikator)
  │(Petugas)   │
  └────────────┘


RELATIONSHIP FLOW:

  1. Siswa buat loan (User → Loan)
  2. Petugas validasi return + hitung denda (Loan.denda calculated)
  3. Siswa bayar denda (Loan → DendaPayment)
  4. Petugas verifikasi pembayaran (DendaPayment → Petugas)
  5. Update status denda (DendaPayment → Loan.denda_status)
```

---

## 6. DATA FLOW CHART

```
┌─────────────────────────────────────────────────────────────────┐
│                         DATA FLOW DENDA                          │
└─────────────────────────────────────────────────────────────────┘

USER INPUT (FORM VALIDASI RETURN)
    │
    ├─ jumlah_kembali_baik
    ├─ jumlah_kembali_rusak
    ├─ jumlah_kembali_hilang
    └─ harga_barang
        │
        ↓
    VALIDATION LAYER
    ├─ Total = Jumlah pinjam?
    ├─ Harga valid?
    └─ Harga required jika ada rusak/hilang?
        │
        ↓
    BUSINESS LOGIC (PetugasController::validateReturn)
    ├─ Calculate denda_rusak
    ├─ Calculate denda_hilang
    ├─ Calculate denda_terlambat (via DendaHelper)
    └─ total = rusak + hilang + terlambat
        │
        ↓
    DATABASE WRITE (Loan Model)
    ├─ UPDATE loans SET
    │  ├─ denda = total
    │  ├─ denda_status = (total > 0) ? 'menunggu_pembayaran' : 'tidak_ada_denda'
    │  ├─ alasan_denda = keterangan
    │  ├─ status_alat = validated_status
    │  ├─ harga_barang = harga_input
    │  ├─ status = 'returned'
    │  └─ tanggal_kembali = now()
    │
    ├─ UPDATE tools SET
    │  └─ stok += jumlah_baik
    │
    └─ INSERT INTO activity_logs (audit trail)
        │
        ↓
    DATABASE READ (DendaPaymentController::store)
    ├─ Read Loan.denda
    ├─ Read Loan.denda_status
    └─ Validate jumlah_bayar <= denda
        │
        ↓
    DATABASE WRITE (DendaPayment Model)
    ├─ INSERT INTO denda_payments
    │  ├─ loan_id
    │  ├─ jumlah_denda = Loan.denda
    │  ├─ jumlah_bayar = user_input
    │  ├─ sisa_denda = denda - bayar
    │  ├─ status = 'menunggu_verifikasi'
    │  └─ tanggal_pembayaran = now()
    │
    ├─ UPDATE loans SET
    │  └─ denda_status = 'menunggu_verifikasi'
    │
    └─ INSERT INTO activity_logs
        │
        ↓
    DATABASE READ (DendaPaymentController::verify)
    └─ Read DendaPayment & Loan
        │
        ↓
    VERIFICATION (Petugas decision)
    ├─ IF approve:
    │  ├─ UPDATE DendaPayment.status = 'terverifikasi'
    │  ├─ IF sisa_denda <= 0:
    │  │  └─ UPDATE Loan.denda_status = 'lunas'
    │  └─ ELSE:
    │     └─ UPDATE Loan.denda_status = 'menunggu_pembayaran'
    │
    └─ ELSE reject:
       └─ UPDATE Loan.denda_status = 'menunggu_pembayaran'
```

---

## 7. TIMELINE CONTOH KASUS

```
┌──────────────────────────────────────────────────────────────────┐
│ CONTOH KASUS: SISWA KEMBALIKAN ALAT RUSAK & TERLAMBAT           │
└──────────────────────────────────────────────────────────────────┘

TANGGAL  | WAKTU | EVENT              | TINDAKAN                  | STATUS
---------|-------|--------------------|--------------------------|-----------
Jan 12   | 09:00 | Siswa pinjam       | Form peminjaman dibuat   | pending
         |       | 1 Proyektor        |                          |
         |       | 1 Laptop           |                          |
         |       |                    |                          |
Jan 12   | 10:00 | Petugas setujui    | Set deadline: Jan 19     | approved
         |       | 7 hari             |                          |
         |       | (12 + 7)           |                          |
         |       |                    |                          |
Jan 19   | 17:00 | Deadline hari ini  | (Masih dalam periode)    | approved
         |       |                    |                          |
Jan 20   | 09:00 | Siswa kembalikan   | Kondisi:                 | -
         |       | alat               | - Proyektor rusak        |
         |       |                    | - Laptop baik            |
         |       |                    | (TERLAMBAT 1 hari)       |
         |       |                    |                          |
Jan 20   | 09:30 | Petugas validasi   | Input:                   | -
         |       | pengembalian       | - jumlah_rusak = 1       |
         |       |                    | - jumlah_baik = 1        |
         |       |                    | - harga_barang = 1.000.000|
         |       |                    |                          |
         |       |                    | Perhitungan:             | -
         |       |                    | - Rusak: 50% × 1jt = 500k|
         |       |                    | - Laptop terlambat 1 hari|
         |       |                    |   = 1 × 5.000 = 5.000   |
         |       |                    | - TOTAL = 505.000        |
         |       |                    |                          |
Jan 20   | 09:45 | Sistem simpan data | Loan.denda = 505.000     | returned
         |       |                    | Loan.denda_status =      |
         |       |                    | menunggu_pembayaran      |
         |       |                    |                          |
Jan 21   | 14:00 | Siswa bayar denda  | Form pembayaran:         | -
         |       |                    | - jumlah_bayar = 505.000 |
         |       |                    | - metode = transfer      |
         |       |                    | - bukti = screenshot     |
         |       |                    |                          |
Jan 21   | 14:15 | Sistem create      | DendaPayment created     | -
         |       | DendaPayment       | status = menunggu_verifikasi
         |       |                    | sisa_denda = 0           |
         |       |                    |                          |
Jan 21   | 15:00 | Petugas login      | Buka halaman verifikasi  | -
         |       |                    | Lihat pembayaran dari    |
         |       |                    | Siswa A (Rp 505.000)     |
         |       |                    |                          |
Jan 21   | 15:05 | Petugas review     | Click "Periksa"          | -
         |       |                    | Modal buka:              |
         |       |                    | - Show bukti screenshot  |
         |       |                    | - Verifikasi jumlah OK   |
         |       |                    | - Click "Terima"         |
         |       |                    |                          |
Jan 21   | 15:06 | Sistem update      | DendaPayment.status =    | -
         |       |                    | terverifikasi            |
         |       |                    | Loan.denda_status = lunas|
         |       |                    | Loan.denda = 0           |
         |       |                    |                          |
Jan 21   | 15:07 | PROSES SELESAI     | Siswa bebas denda        | lunas
         |       |                    | Dapat pinjam lagi        |

TIMELINE RINGKAS:
  Jan 12 - Peminjaman setujui (deadline Jan 19)
  Jan 20 - Return (rusak + terlambat 1 hari)
  Jan 20 - Petugas hitung denda: Rp 505.000
  Jan 21 - Siswa bayar full
  Jan 21 - Petugas verifikasi & terima pembayaran
  Jan 21 - STATUS: LUNAS ✓
```

---

## 8. KETERANGAN KONSTANTA

```
KONSTANTA DENDA YANG DIGUNAKAN:

┌─────────────────────────┬──────────────┬───────────────────┐
│ Tipe Denda              │ Formula      │ Contoh (1jt item) │
├─────────────────────────┼──────────────┼───────────────────┤
│ Keterlambatan           │ Rp 5.000/hari│ 5 hari = Rp 25k  │
│ Kerusakan               │ 50% harga    │ 1jt item = Rp 500k│
│ Hilang                  │ 100% harga   │ 1jt item = Rp 1jt │
│ Pembayaran minimum      │ Rp 1.000     │ Min amount input   │
│ Pembayaran maksimum     │ denda + 1.000│ Rp 506.000 max    │
└─────────────────────────┴──────────────┴───────────────────┘

LOKASI KONSTANTA DI KODE:
  • DENDA_KETERLAMBATAN_PER_HARI = 5000
    File: app/Helpers/DendaHelper.php (line 10)
  
  • Validation max pembayaran: max: ($loan->denda + 1000)
    File: app/Http/Controllers/DendaPaymentController.php (line ~78)

JIKA PERLU UBAH KONSTANTA:
  1. Edit app/Helpers/DendaHelper.php
     const DENDA_KETERLAMBATAN_PER_HARI = [NILAI_BARU];
  
  2. Perhatian: Ini TIDAK termasuk pembayaran yang sudah dibuat
     Hanya berlaku untuk perhitungan denda BARU
```

