<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class SettingController extends Controller
{
    public function index()
    {
        // Debug: cek semua settings yang ada
        $allSettings = setting()->all();
        Log::info('All settings in index', $allSettings);

        $settings = [
            'site_name' => setting('site_name', 'Manajemen Dataset BPS'),
            'site_description' => setting('site_description', ''),
            'site_logo' => setting('site_logo', null),
            'site_favicon' => setting('site_favicon', null),
            'admin_email' => setting('admin_email', ''),
            'sync_schedule' => setting('sync_schedule', 'disabled'),
            'scraping_timeout' => setting('scraping_timeout', 30),
            'bps_base_url' => setting('bps_base_url', 'https://kebumenkab.bps.go.id'),
            'bps_api_key' => env('BPS_API_KEY', ''),
            'maintenance_mode' => setting('maintenance_mode', false),
            'email_notifications' => setting('email_notifications', false),
            'mail_from_name' => setting('mail_from_name', ''),
            'last_sync' => setting('last_sync', 'Belum pernah'),
        ];

        return view('admin.settings.index', compact('settings'));
    }

    /**
     * Memvalidasi dan menyimpan perubahan dari form pengaturan.
     */
    public function update(Request $request)
    {
        Log::info('=== Settings Update Start ===');
        Log::info('Request all data', $request->all());

        $validated = $request->validate([
            'site_name' => 'nullable|string|max:100',
            'site_description' => 'nullable|string|max:255',
            'admin_email' => 'nullable|email',
            'site_logo' => 'nullable|image|mimes:png,jpg,jpeg,svg|max:2048',
            'site_favicon' => 'nullable|image|mimes:png,ico|max:1024',
            'sync_schedule' => 'nullable|string|max:50',
            'scraping_timeout' => 'nullable|integer|min:10|max:300',
            'bps_base_url' => 'nullable|url',
            'bps_api_key' => 'nullable|string|max:255',
            'password' => 'nullable|string|min:8|confirmed',
            'maintenance_mode' => 'nullable|boolean',
            'email_notifications' => 'nullable|boolean',
            'mail_from_name' => 'nullable|string|max:255',
        ]);

        try {
            // Simpan settings satu per satu untuk memastikan tersimpan
            if ($request->filled('site_name')) {
                setting(['site_name' => $request->site_name]);
                Log::info('Saved site_name', ['value' => $request->site_name]);
            }

            if ($request->filled('site_description')) {
                setting(['site_description' => $request->site_description]);
                Log::info('Saved site_description', ['value' => $request->site_description]);
            }

            if ($request->filled('admin_email')) {
                setting(['admin_email' => $request->admin_email]);
                Log::info('Saved admin_email', ['value' => $request->admin_email]);
            }

            if ($request->filled('mail_from_name')) {
                setting(['mail_from_name' => $request->mail_from_name]);
                Log::info('Saved mail_from_name', ['value' => $request->mail_from_name]);
            }

            // Sync schedule dan timeout (selalu simpan)
            setting([
                'sync_schedule' => $request->input('sync_schedule', 'disabled'),
                'scraping_timeout' => $request->input('scraping_timeout', 30),
            ]);

            // Checkbox (selalu simpan)
            setting([
                'maintenance_mode' => $request->has('maintenance_mode') ? 1 : 0,
                'email_notifications' => $request->has('email_notifications') ? 1 : 0,
            ]);

            if ($request->filled('bps_base_url')) {
                setting(['bps_base_url' => $request->bps_base_url]);
            }

            // PENTING: Simpan semua perubahan ke database
            setting()->save();
            Log::info('Settings saved to database');

            // Debug: cek semua settings setelah save
            Log::info('All settings after save', setting()->all());
        } catch (\Exception $e) {
            Log::error('Failed to save settings', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            return back()->withErrors(['error' => 'Gagal menyimpan pengaturan: ' . $e->getMessage()]);
        }

        // Handle API Key - simpan ke .env untuk keamanan
        // PERBAIKI: Cek 'bps_api_key'
        if ($request->filled('bps_api_key')) {
            try {
                // Pastikan $validated['bps_api_key'] ada
                $apiKey = $validated['bps_api_key'] ?? $request->input('bps_api_key');
                $this->updateEnvFile('BPS_API_KEY', $apiKey);
                Log::info('BPS_API_KEY updated', ['value' => $apiKey]);
            } catch (\Exception $e) {
                Log::error('Failed updating API key', ['error' => $e->getMessage()]);
                return back()->withErrors(['bps_api_key' => 'Gagal menyimpan API Key: ' . $e->getMessage()]);
            }
        }

        // Handle upload logo
        // PERBAIKI: Cek 'site_logo'
        if ($request->hasFile('site_logo')) {
            $old = setting('site_logo');
            if ($old && Storage::disk('public')->exists($old)) { // Perbaiki path check
                Storage::disk('public')->delete($old);
            }
            $path = $request->file('site_logo')->store('settings', 'public');
            setting(['site_logo' => $path]); // Simpan path relatif
        }

        // Handle upload favicon
        // PERBAIKI: Cek 'site_favicon'
        if ($request->hasFile('site_favicon')) {
            $old = setting('site_favicon');
            if ($old && Storage::disk('public')->exists($old)) { // Perbaiki path check
                Storage::disk('public')->delete($old);
            }
            $path = $request->file('site_favicon')->store('settings', 'public');
            setting(['site_favicon' => $path]); // Simpan path relatif
        }

        // Ganti password admin jika diisi (gunakan Eloquent User untuk memastikan method 'save' ada)
        if ($request->filled('password')) {
            $hashed = Hash::make($validated['password']);
            $userId = Auth::id();

            if ($userId) {
                $user = User::find($userId);
                if ($user instanceof User) {
                    // Eloquent model instance — aman memanggil save()
                    $user->password = $hashed;
                    $user->save();
                } else {
                    // Fallback: update via query builder jika model tidak tersedia
                    User::whereKey($userId)->update(['password' => $hashed]);
                }
            } else {
                Log::warning('Password update skipped: no authenticated user ID.');
            }
        }

        Log::info('=== Settings Update End ===');
        return back()->with('status', 'Pengaturan berhasil diperbarui!');
    }

    /**
     * Update nilai di file .env
     */
    private function updateEnvFile($key, $value)
    {
        $path = base_path('.env');
        if (!file_exists($path)) {
            throw new \Exception('.env tidak ditemukan');
        }

        $value = str_replace(['\\', '"'], ['\\\\', '\"'], $value);
        $envContent = file_get_contents($path);
        $pattern = "/^{$key}=.*/m";

        if (preg_match($pattern, $envContent)) {
            $envContent = preg_replace($pattern, "{$key}=\"{$value}\"", $envContent);
        } else {
            $envContent .= "\n{$key}=\"{$value}\"";
        }

        $fp = fopen($path, 'c+');
        if (!$fp) {
            throw new \Exception('Tidak bisa membuka .env');
        }
        if (!flock($fp, LOCK_EX)) {
            fclose($fp);
            throw new \Exception('Gagal lock .env');
        }
        ftruncate($fp, 0);
        fwrite($fp, $envContent);
        fflush($fp);
        flock($fp, LOCK_UN);
        fclose($fp);

        try {

            Log::info('Skipping config:clear in development to prevent server crash.');
        } catch (\Exception $e) {
            // ignore
        }
    }

    // Backup database (opsional)
    public function backup()
    {
        $db = config('database.default');
        if ($db === 'sqlite') {
            $file = database_path(config('database.connections.sqlite.database'));
            return response()->download($file, 'backup-database.sqlite');
        }
        return back()->with('status', 'Backup hanya tersedia untuk SQLite.');
    }
}
