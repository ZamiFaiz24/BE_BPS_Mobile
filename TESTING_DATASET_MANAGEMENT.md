# ✅ Testing Checklist - Dataset Management System

## 🔍 Pre-Testing Setup

-   [ ] Jalankan migration: `php artisan migrate`
-   [ ] Clear cache: `php artisan cache:clear`
-   [ ] Restart queue worker: `php artisan queue:work`
-   [ ] Buka browser console untuk cek error

---

## 🧪 Test 1: View Management Page

**Akses:** `http://localhost/admin/datasets/management`

**Expected:**

-   [ ] Halaman load dengan benar
-   [ ] Tabel dataset muncul
-   [ ] Minimal 1 dataset terlihat
-   [ ] Button [Enable/Disable] dan [Sync] visible

---

## 🧪 Test 2: Toggle Enable/Disable

**Step:**

1. [ ] Klik button [❌ Disable] pada dataset
2. [ ] Confirm dialog muncul
3. [ ] Klik OK

**Expected:**

-   [ ] Success message muncul
-   [ ] Status berubah jadi "❌ Nonaktif" (red badge)
-   [ ] Button [🔄 Sync] hilang
-   [ ] Database ter-update (check `dataset_overrides` table)

**Database Check:**

```sql
SELECT * FROM dataset_overrides
WHERE dataset_id = 'dataset_populasi_kebumen_51';

-- Expected: enabled = 0 (false)
```

---

## 🧪 Test 3: Toggle Enable Kembali

**Step:**

1. [ ] Klik button [✅ Enable] pada dataset yang disabled
2. [ ] Confirm dialog muncul
3. [ ] Klik OK

**Expected:**

-   [ ] Success message muncul
-   [ ] Status berubah jadi "✅ Aktif" (green badge)
-   [ ] Button [🔄 Sync] muncul kembali

---

## 🧪 Test 4: Manual Sync Per Dataset

**Step:**

1. [ ] Pastikan dataset ENABLED
2. [ ] Klik button [🔄 Sync]
3. [ ] Confirm dialog muncul
4. [ ] Klik OK

**Expected:**

-   [ ] Button berubah jadi "⏳ Syncing..."
-   [ ] Success message: "Sinkronisasi dataset telah dimasukkan ke antrean"
-   [ ] Queue job ditambah (check `jobs` table)

**Console Check:**

```bash
# Terminal 1: Monitor queue
php artisan queue:work

# Terminal 2: Trigger sync
# Klik tombol sync di UI
```

---

## 🧪 Test 5: API Endpoint - Get Datasets List

**Using Postman/curl:**

```bash
curl -X GET "http://localhost/admin/datasets/management/list" \
  -H "Accept: application/json"
```

**Expected:**

```json
{
    "success": true,
    "data": [
        {
            "id": "dataset_populasi_kebumen_51",
            "name": "Jumlah Penduduk Kabupaten Kebumen",
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

---

## 🧪 Test 6: API Endpoint - Sync Single Dataset

**Using Postman:**

```
POST http://localhost/admin/datasets/dataset_populasi_kebumen_51/sync
Header: Accept: application/json
```

**Expected:**

-   [ ] Status 200 OK
-   [ ] Response: `{"success": true, "message": "..."}`
-   [ ] Queue job ditambah

---

## 🧪 Test 7: API Endpoint - Toggle Dataset

**Using Postman:**

```
POST http://localhost/admin/datasets/dataset_populasi_kebumen_51/toggle
Header: Accept: application/json, Content-Type: application/x-www-form-urlencoded
Body: enabled=0
```

**Expected:**

-   [ ] Status 200 OK
-   [ ] Response: `{"success": true, "enabled": false, "message": "..."}`

---

## 🧪 Test 8: Service - DatasetConfigService

**Terminal:**

```bash
php artisan tinker

# Test 1: Get all datasets
$service = app(\App\Services\DatasetConfigService::class);
$all = $service->getAllDatasets();
$all->count(); // Should return > 0

# Test 2: Get enabled datasets
$enabled = $service->getEnabledDatasets();
dd($enabled);

# Test 3: Check if dataset enabled
$isEnabled = $service->isDatasetEnabled('dataset_populasi_kebumen_51');
dd($isEnabled); // Should return true

# Test 4: Toggle dataset
$service->toggleDataset('dataset_populasi_kebumen_51', false);

# Test 5: Verify toggle
$isEnabled2 = $service->isDatasetEnabled('dataset_populasi_kebumen_51');
dd($isEnabled2); // Should return false
```

---

## 🧪 Test 9: Model - DatasetOverride

**Terminal:**

```bash
php artisan tinker

# Create override
$override = \App\Models\DatasetOverride::create([
    'dataset_id' => 'test_dataset',
    'source_type' => 'config',
    'enabled' => false
]);

# Query
$all = \App\Models\DatasetOverride::all();
$enabled = \App\Models\DatasetOverride::enabled()->get();
$disabled = \App\Models\DatasetOverride::disabled()->get();

# Update
$override->update(['enabled' => true]);

# Delete
$override->delete();
```

---

## 🧪 Test 10: Config File Format

**File:** `config/bps_targets.php`

**Check:**

-   [ ] Setiap dataset punya field `id`
-   [ ] Setiap dataset punya field `enabled`
-   [ ] ID unique (tidak duplicate)
-   [ ] ID format: `dataset_<name>_<variable_id>`

**Example:**

```php
[
    'id'           => 'dataset_populasi_kebumen_51',  // ✅ Ada
    'model'        => 'data',
    'name'         => 'Jumlah Penduduk Kebumen',
    'variable_id'  => 51,
    'unit'         => 'Jiwa',
    'enabled'      => true,  // ✅ Ada
    'params'       => ['domain' => '3305'],
],
```

---

## 🧪 Test 11: Database Table Check

**Terminal:**

```bash
php artisan tinker

# Check table exists
\Illuminate\Support\Facades\Schema::hasTable('dataset_overrides');
// Should return true

# Check columns
$columns = \Illuminate\Support\Facades\Schema::getColumnListing('dataset_overrides');
dd($columns);

// Should include:
// - id, dataset_id, source_type, enabled
// - api_url, config, created_by, notes
// - created_at, updated_at
```

---

## 🧪 Test 12: Permission Check

**Requirement:**

-   Hanya user dengan role "Super Admin" yang bisa access management page
-   User dengan role "Operator" tidak bisa akses

**Test:**

-   [ ] Login dengan Super Admin → Bisa akses management
-   [ ] Login dengan Operator → Redirect atau error

---

## 🧪 Test 13: Error Handling

**Test Case 1: Non-existent dataset**

```bash
curl -X POST "http://localhost/admin/datasets/invalid_id/sync"
# Expected: 404 error
```

**Test Case 2: Sync disabled dataset**

```bash
# Disable dataset dulu
# Kemudian trigger sync
# Expected: 422 error "Dataset tidak aktif"
```

**Test Case 3: Double click protection**

```bash
# Click [Sync] button 5 kali rapidly
# Expected: First one berhasil, rest ditolak dengan pesan lock
```

---

## 📋 Performance Test

**Test:** Load management page dengan banyak dataset

-   [ ] Buka management page
-   [ ] Check browser DevTools > Network
-   [ ] Check query ke API: `/admin/datasets/management/list`
-   [ ] Response time < 200ms
-   [ ] No N+1 query issues

---

## 🐛 Bug Report Template

Jika menemukan bug:

```
**Title:** [BUG] Deskripsi singkat

**Steps to Reproduce:**
1. Akses halaman ...
2. Klik button ...
3. ...

**Expected Behavior:**
- Seharusnya...

**Actual Behavior:**
- Yang terjadi...

**Browser/Environment:**
- Laravel version: 11
- PHP version: 8.2
- Browser: Chrome 131

**Error Log:**
```

(screenshot atau copy-paste error dari laravel.log)

```

```

---

## ✅ Acceptance Criteria

Semua test harus PASS sebelum deploy:

-   [ ] Test 1: View Management Page ✅
-   [ ] Test 2: Toggle Disable ✅
-   [ ] Test 3: Toggle Enable ✅
-   [ ] Test 4: Manual Sync ✅
-   [ ] Test 5: API Get List ✅
-   [ ] Test 6: API Sync ✅
-   [ ] Test 7: API Toggle ✅
-   [ ] Test 8: Service ✅
-   [ ] Test 9: Model ✅
-   [ ] Test 10: Config Format ✅
-   [ ] Test 11: Database ✅
-   [ ] Test 12: Permission ✅
-   [ ] Test 13: Error Handling ✅
-   [ ] Performance Test ✅

---

## 📝 Test Report

**Tester:** ******\_\_\_\_******
**Date:** ******\_\_\_\_******
**Status:** ⏳ In Progress / ✅ PASS / ❌ FAIL

**Notes:**

```
________________
________________
________________
```

---

**Last Updated:** 10 Desember 2025
