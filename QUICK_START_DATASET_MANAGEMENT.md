# 🚀 Quick Start - Dataset Management System

## 📦 Yang Baru Ditambah

Fitur untuk **manage dataset BPS langsung dari dashboard** tanpa edit config file!

```
✅ Toggle dataset on/off
✅ Sync single dataset (bukan semua)
✅ View management page
✅ Database override system
```

---

## 🎯 Akses Langsung

**Halaman Management:**

```
http://localhost/admin/datasets/management
```

---

## 📋 Yang Sudah Jadi

### **Files Created:**

```
✅ app/Models/DatasetOverride.php
✅ app/Services/DatasetConfigService.php
✅ resources/views/admin/datasets/management.blade.php
✅ database/migrations/2025_12_10_000001_create_dataset_overrides_table.php
✅ DOCUMENTATION_DATASET_MANAGEMENT.md (detailed docs)
✅ TESTING_DATASET_MANAGEMENT.md (testing guide)
```

### **Files Modified:**

```
✅ config/bps_targets.php (added 'id' & 'enabled' fields)
✅ app/Http/Controllers/Admin/DashboardController.php (4 methods)
✅ routes/web.php (4 new routes)
```

### **Database:**

```
✅ Migration run: dataset_overrides table created
```

---

## 🔧 Setup Final Steps

### **1. Jalankan Migration (SUDAH DONE)**

```bash
php artisan migrate
```

### **2. Restart Laravel**

```bash
php artisan cache:clear
php artisan config:clear
php artisan route:clear
```

### **3. Update bps_targets.php untuk dataset lain**

Edit `config/bps_targets.php`, tambahkan field `id` & `enabled` ke setiap dataset yang uncommented:

**Contoh:**

```php
[
    'id'           => 'dataset_kemiskinan_p2_290',  // ← Tambahkan ini
    'model'        => 'data',
    'name'         => 'Indeks Keparahan Kemiskinan (P2)',
    'variable_id'  => 290,
    'unit'         => 'Persen',
    'tahun_mulai'  => 2002,
    'tahun_akhir'  => 2024,
    'insight_type' => 'percent_lower_is_better',
    'category'     => 3,
    'enabled'      => true,  // ← Tambahkan ini
    'params'       => ['domain' => '3305'],
],
```

---

## 🎮 Cara Pakai

### **Via Dashboard UI**

1. **Buka halaman management:**

    ```
    Admin Panel → Datasets → Management
    atau: /admin/datasets/management
    ```

2. **Lihat semua dataset** dengan status enable/disable

3. **Klik [❌ Disable]** untuk matikan dataset

    - Dataset tidak akan di-sync
    - Data tetap ada di database
    - Bisa enable lagi kapan saja

4. **Klik [✅ Enable]** untuk aktifkan dataset yang disabled

5. **Klik [🔄 Sync]** untuk sync hanya dataset itu
    - Lebih cepat dari sync semua
    - Job masuk ke queue
    - Progress bisa dilihat di logs

---

## 💻 Cara Pakai (Via API/Code)

### **Ambil service**

```php
use App\Services\DatasetConfigService;

$configService = new DatasetConfigService();

// Get semua dataset
$all = $configService->getAllDatasets();

// Get yang enabled saja
$enabled = $configService->getEnabledDatasets();

// Check satu dataset
$dataset = $configService->getDataset('dataset_populasi_kebumen_51');

// Toggle
$configService->toggleDataset('dataset_populasi_kebumen_51', false);
```

### **API Endpoints**

```
GET /admin/datasets/management/list
  → Ambil list datasets JSON

POST /admin/datasets/{datasetId}/sync
  → Trigger sync dataset

POST /admin/datasets/{datasetId}/toggle
  → Toggle enable/disable
  → Body: enabled=0|1
```

---

## 🗂️ Database Schema

### **Table: dataset_overrides**

Menyimpan override settings untuk setiap dataset:

```
id              - Primary key
dataset_id      - ID unik dataset (dari config)
source_type     - 'config' atau 'quick_add'
enabled         - true/false (status aktif)
api_url         - Untuk future quick add feature
config          - JSON, untuk future override
created_by      - User ID yang create
notes           - Catatan tambahan
created_at      - Waktu create
updated_at      - Waktu update
```

---

## 🔄 Workflow Sistem

```
┌─────────────────────────────────────────────────────┐
│ Saat App Start                                      │
├─────────────────────────────────────────────────────┤
│ 1. Baca config/bps_targets.php                      │
│ 2. Load dataset_overrides dari database             │
│ 3. Merge keduanya                                   │
│ 4. Apply status enabled/disabled                    │
│ 5. Siap untuk sync/display                          │
└─────────────────────────────────────────────────────┘

┌─────────────────────────────────────────────────────┐
│ Saat Toggle/Sync via Dashboard                     │
├─────────────────────────────────────────────────────┤
│ 1. User action di UI                               │
│ 2. POST ke controller                              │
│ 3. Update database override                        │
│ 4. Next sync → gunakan override                    │
│ 5. Dataset disabled di-skip                        │
└─────────────────────────────────────────────────────┘
```

---

## 🎯 Contoh Penggunaan

### **Scenario 1: API BPS Bermasalah**

```
Jam 10:00 - API variable_id 290 error
    ↓
Admin login → Management page
    ↓
Klik [❌ Disable] pada "Indeks Kemiskinan"
    ↓
Next sync otomatis skip dataset itu
    ↓
Dataset lain tetap sync normal
    ↓
Jam 14:00 - API sudah normal
    ↓
Admin klik [✅ Enable]
    ↓
Dataset siap di-sync lagi
```

### **Scenario 2: Pengguna Butuh Update Cepat**

```
User: "Mas, tolong update data populasi saja!"
    ↓
Admin buka Management page
    ↓
Klik [🔄 Sync] di "Populasi" dataset
    ↓
Hanya populasi yang sync (cepat!)
    ↓
User dapat data baru ~2 menit
    ↓
(Vs sync semua 15 menit)
```

---

## ⚙️ Konfigurasi (Opsional)

### **Untuk disable dataset by default:**

Edit `config/bps_targets.php`:

```php
[
    'id'      => 'dataset_xxx',
    'enabled' => false,  // ← Dataset tidak aktif by default
    // ... rest of config
],
```

### **Untuk queue processing:**

Edit `.env`:

```env
QUEUE_CONNECTION=database  # atau redis/sync
```

Jalankan worker:

```bash
php artisan queue:work
```

---

## 🧪 Quick Test

### **Test 1: Buka halaman**

```
Akses: http://localhost/admin/datasets/management
Expect: Tabel dataset tampil dengan button
```

### **Test 2: Disable dataset**

```
Klik [❌ Disable] → Confirm
Expect: Status berubah, button berubah
```

### **Test 3: Sync dataset**

```
Klik [🔄 Sync] → Confirm
Expect: Message "dimasukkan ke antrean"
```

### **Test 4: Check database**

```bash
php artisan tinker
>>> \App\Models\DatasetOverride::all();
// Should return created record
```

---

## 🚨 Troubleshooting

### **"Halaman kosong / tidak load"**

```
Cek:
1. Sudah run migration?
   → php artisan migrate
2. Dataset punya 'id' field di config?
   → Edit config/bps_targets.php
3. Clear cache:
   → php artisan cache:clear
```

### **"Toggle/Sync button tidak jalan"**

```
Cek:
1. CSRF token di form? (auto, tapi cek)
2. Queue running?
   → php artisan queue:work
3. Check browser console: Network tab
```

### **"Dataset tidak muncul di management"**

```
Cek:
1. Dataset di config/bps_targets.php?
2. Punya field 'id'?
3. ID unique (tidak duplicate)?
4. Call: dd(config('bps_targets.datasets'));
```

---

## 📞 Support

Lihat dokumentasi lengkap:

-   `DOCUMENTATION_DATASET_MANAGEMENT.md` - Detail docs
-   `TESTING_DATASET_MANAGEMENT.md` - Testing guide

---

## ✅ Checklist Sebelum Production

-   [ ] Migration sudah run
-   [ ] Semua dataset di config punya `id` & `enabled`
-   [ ] Test toggle work
-   [ ] Test sync work
-   [ ] Test management page load
-   [ ] Queue worker running
-   [ ] Permission check (hanya Super Admin bisa akses)
-   [ ] Clear cache sebelum deploy

---

**Status:** ✅ Ready for testing
**Version:** 1.0.0
**Last Updated:** 10 Desember 2025
