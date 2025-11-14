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
        // PERBAIKI: Sesuaikan key ini agar konsisten saat mengambil data
        $settings = [
            'site_name' => setting('site_name', 'Manajemen Dataset BPS'),
            'site_description' => setting('site_description', ''),
            'site_logo' => setting('site_logo', null),
            'site_favicon' => setting('site_favicon', null),
            'admin_email' => setting('admin_email', ''),
            'sync_schedule' => setting('sync_schedule', 'disabled'),
            'scraping_timeout' => setting('scraping_timeout', 30),
            'bps_base_url' => setting('bps_base_url', 'https://kebumenkab.bps.go.id'), // Tambahkan ini
            'bps_api_key' => env('BPS_API_KEY', ''), // Gunakan bps_api_key
            'maintenance_mode' => setting('maintenance_mode', false),
            'email_notifications' => setting('email_notifications', false),
            'mail_from_name' => setting('mail_from_name', ''),
            'last_sync' => setting('last_sync', 'Belum pernah'),
        ];
        // Pastikan Anda meneruskan 'settings' ke view yang benar
        // return view('admin.settings.index', compact('settings'));

        // Asumsi dari kode sebelumnya, Anda mem-pass 'settings'
        // Jika nama view Anda 'admin.settings', ganti di bawah:
        return view('admin.settings.index', compact('settings')); // Sesuaikan nama view jika perlu
    }

    /**
     * Memvalidasi dan menyimpan perubahan dari form pengaturan.
     */
    public function update(Request $request)
    {
        Log::info('Settings update request received', $request->all());

        // PERBAIKI: Validasi disesuaikan dengan 'name' di form Blade
        $validated = $request->validate([
            'site_name' => 'nullable|string|max:100',
            'site_description' => 'nullable|string|max:255',
            'admin_email' => 'nullable|email',
            'site_logo' => 'nullable|image|mimes:png,jpg,jpeg,svg|max:2048',
            'site_favicon' => 'nullable|image|mimes:png,ico|max:1024',
            'sync_schedule' => 'nullable|string|max:50',
            'scraping_timeout' => 'nullable|integer|min:10|max:300',
            'bps_base_url' => 'nullable|url', // Tambahkan ini
            'bps_api_key' => 'nullable|string|max:255', // Ganti dari 'api_key'
            'password' => 'nullable|string|min:8|confirmed', // Ganti dari 'new_password'
            'maintenance_mode' => 'nullable|boolean',
            'email_notifications' => 'nullable|boolean',
            'mail_from_name' => 'nullable|string|max:255',
        ]);

        // PERBAIKI: Simpan dengan key yang benar
        setting([
            'site_name' => $validated['site_name'] ?? setting('site_name'),
            'site_description' => $validated['site_description'] ?? setting('site_description'),
            'admin_email' => $validated['admin_email'] ?? setting('admin_email'),
            'sync_schedule' => $validated['sync_schedule'] ?? setting('sync_schedule', 'disabled'),
            'scraping_timeout' => $validated['scraping_timeout'] ?? setting('scraping_timeout', 30),
            'bps_base_url' => $validated['bps_base_url'] ?? setting('bps_base_url'), // Tambahkan ini
            'maintenance_mode' => $request->has('maintenance_mode') ? 1 : 0,
            'email_notifications' => $request->has('email_notifications') ? 1 : 0,
            'mail_from_name' => $validated['mail_from_name'] ?? setting('mail_from_name'),
        ]);

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

        Log::info('Settings update completed');
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
