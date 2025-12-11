# 📋 Dokumentasi: Dataset Management Hybrid System

## 🎯 Implementasi Selesai

Sistem hybrid telah diimplementasikan dengan fitur:

-   ✅ Toggle enable/disable dataset via dashboard
-   ✅ Manual sync per dataset
-   ✅ Database overrides untuk management

---

## 📁 File yang Dibuat/Dimodifikasi

### 1. **Database**

-   ✅ Migration: `database/migrations/2025_12_10_000001_create_dataset_overrides_table.php`
    -   Table: `dataset_overrides`
    -   Menyimpan status enable/disable per dataset

### 2. **Models**

-   ✅ `app/Models/DatasetOverride.php` - Model untuk dataset overrides

### 3. **Services**

-   ✅ `app/Services/DatasetConfigService.php` - Service untuk manage dataset config

### 4. **Controllers**

-   ✅ `app/Http/Controllers/Admin/DashboardController.php` - Tambah 4 method baru:
    -   `syncSingleDataset()` - Sync 1 dataset
    -   `toggleDataset()` - Enable/disable dataset
    -   `getDatasetsList()` - API untuk ambil list datasets
    -   `management()` - Show management page

### 5. **Views**

-   ✅ `resources/views/admin/datasets/management.blade.php` - UI management

### 6. **Config**

-   ✅ `config/bps_targets.php` - Tambah field `id` dan `enabled` pada dataset

### 7. **Routes**

-   ✅ `routes/web.php` - Tambah 4 route baru:
    -   `GET /admin/datasets/management` - Management page
    -   `GET /admin/datasets/management/list` - API list datasets
    -   `POST /admin/datasets/{datasetId}/sync` - Sync single dataset
    -   `POST /admin/datasets/{datasetId}/toggle` - Toggle enable/disable

---

## 🔧 Cara Kerja

### **Saat Application Start**

```
1. Baca config dari bps_targets.php
2. Load overrides dari database (dataset_overrides table)
3. Merge keduanya
4. Gunakan yang merged untuk sync & display
```

### **Toggle Enable/Disable**

```
User klik [Enable/Disable] di dashboard
    ↓
POST /admin/datasets/{datasetId}/toggle
    ↓
Update database (dataset_overrides)
    ↓
Next sync → skip yang disabled
```

### **Sync Single Dataset**

```
User klik [🔄 Sync] di dashboard
    ↓
POST /admin/datasets/{datasetId}/sync
    ↓
Validate: dataset enabled? config exists?
    ↓
Queue: bps:fetch-data-single command
    ↓
Process: Fetch & save data
    ↓
Done
```

---

## 📱 API Endpoints

### 1. **Get Datasets List** (for UI)

```http
GET /admin/datasets/management/list
```

**Response:**

```json
{
    "success": true,
    "data": [
        {
            "id": "dataset_populasi_kebumen_51",
            "name": "Jumlah Penduduk Kebumen",
            "variable_id": 51,
            "unit": "Jiwa",
            "tahun_mulai": 2022,
            "tahun_akhir": 2024,
            "enabled": true,
            "source": "config"
        }
    ]
}
```

### 2. **Sync Single Dataset**

```http
POST /admin/datasets/{datasetId}/sync
```

**Response:**

```json
{
    "success": true,
    "message": "Sinkronisasi dataset 'Populasi' telah dimasukkan ke antrean."
}
```

### 3. **Toggle Dataset**

```http
POST /admin/datasets/{datasetId}/toggle
Content-Type: application/x-www-form-urlencoded

enabled=true  // atau false
```

**Response:**

```json
{
    "success": true,
    "message": "Dataset diaktifkan.",
    "enabled": true
}
```

---

## 🎨 UI Dashboard

### **Halaman Management**

Akses: `http://localhost/admin/datasets/management`

```
┌──────────────────────────────────────────────────────────────────────┐
│  Manajemen Dataset BPS                                               │
├──────────────────────────────────────────────────────────────────────┤
│                                                                      │
│  Status │ Nama Dataset           │ Var  │ Unit │ Tahun   │ Aksi    │
│  ─────────────────────────────────────────────────────────────────  │
│  ✅     │ Jumlah Penduduk        │ 51   │ Jiwa │ 2022-24 │[❌][🔄] │
│  ✅     │ Indeks Kemiskinan      │ 290  │ %    │ 2002-24 │[❌][🔄] │
│  ❌     │ PDRB (Disabled)        │ 840  │ Rp   │ 2022-25 │[✅]     │
│                                                                      │
└──────────────────────────────────────────────────────────────────────┘
```

---

## 🔄 Workflow Penggunaan

### **Scenario 1: Normal Operation**

```
1. Admin masuk ke admin.datasets.management
2. Lihat semua dataset dari config bps_targets.php
3. Status setiap dataset (enable/disable)
4. Bisa klik [🔄 Sync] untuk sync single dataset
5. Atau klik [Disable] untuk matikan dataset
```

### **Scenario 2: Dataset Error/Maintenance**

```
1. API BPS bermasalah untuk variable_id tertentu
2. Admin klik [❌ Disable] untuk dataset itu
3. Next sync otomatis skip dataset itu
4. Tidak perlu edit config atau redeploy
5. Nanti ketika sudah fix, klik [✅ Enable]
```

### **Scenario 3: Update Config**

```
1. Developer edit bps_targets.php
   - Tambah dataset baru dengan id & enabled
   - Atau modify existing dataset
2. Commit & push ke Git
3. Deploy ke server
4. App auto-load config baru
5. Admin bisa manage via dashboard
```

---

## 💾 Database Schema

### Table: `dataset_overrides`

```sql
CREATE TABLE dataset_overrides (
  id BIGINT PRIMARY KEY,
  dataset_id VARCHAR(100) UNIQUE,     -- ID dari config
  source_type VARCHAR(20),             -- 'config' atau 'quick_add'
  enabled BOOLEAN DEFAULT true,        -- Status aktif
  api_url TEXT NULL,                   -- Untuk future quick add
  config JSON NULL,                    -- Untuk future config override
  created_by BIGINT NULL,              -- User yang create
  notes TEXT NULL,                     -- Catatan
  created_at TIMESTAMP,
  updated_at TIMESTAMP
);
```

---

## 📝 Kode Contoh Penggunaan

### **Inject Service di Controller**

```php
use App\Services\DatasetConfigService;

class MyController {
    public function __construct(private DatasetConfigService $configService) {}

    public function index() {
        // Ambil semua dataset (config + override merged)
        $datasets = $this->configService->getAllDatasets();

        // Ambil yang enabled saja
        $enabledDatasets = $this->configService->getEnabledDatasets();

        // Cek 1 dataset
        $dataset = $this->configService->getDataset('dataset_populasi_kebumen_51');

        // Toggle dataset
        $this->configService->toggleDataset('dataset_populasi_kebumen_51', false);
    }
}
```

### **Query dari Database**

```php
use App\Models\DatasetOverride;

// Ambil semua override
$overrides = DatasetOverride::all();

// Ambil yang disabled
$disabledDatasets = DatasetOverride::disabled()->get();

// Ambil quick add
$quickAdds = DatasetOverride::quickAdd()->get();

// Update
DatasetOverride::updateOrCreate(
    ['dataset_id' => 'dataset_populasi_kebumen_51'],
    ['enabled' => false]
);
```

---

## 🚀 Next Steps: FUTURE IMPLEMENTATION

Ketika siap untuk Quick Add feature:

1. **Buat URL Parser Service**

    - Parse API URL BPS
    - Extract variable_id, domain, model, tahun

2. **Buat Endpoint untuk Quick Add**

    - Form input URL
    - Preview data
    - Save to dataset_overrides

3. **Buat Command untuk Single Dataset Sync**
    - `bps:fetch-data-single {dataset_id}`
    - Ambil config dari DatasetConfigService
    - Fetch hanya dataset itu

---

## ⚙️ Troubleshooting

### **Dataset tidak muncul di management**

```
Kemungkinan: ID di config tidak unique
Solusi: Pastikan setiap dataset punya unique 'id'
```

### **Toggle tidak bekerja**

```
Kemungkinan: CSRF token invalid
Solusi: Pastikan form punya {{ csrf_field() }}
```

### **Sync tidak jalan**

```
Kemungkinan: Queue worker tidak running
Solusi:
1. Jalankan: php artisan queue:work
2. Atau set QUEUE_CONNECTION=sync di .env
```

---

## 📞 Support

Untuk pertanyaan atau issue, silakan contact team development.

---

**Terakhir diupdate:** 10 Desember 2025
**Status:** ✅ Production Ready (tanpa Quick Add)
