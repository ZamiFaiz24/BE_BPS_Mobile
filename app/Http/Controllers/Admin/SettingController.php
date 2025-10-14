<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Hash;

class SettingController extends Controller
{
    public function index()
    {
        $settings = [
            'dashboard_title' => setting('dashboard_title', 'Manajemen Dataset BPS'),
            'footer_text'     => setting('footer_text', '© ' . date('Y') . ' BPS'),
            'logo'            => setting('logo', null),
            'favicon'         => setting('favicon', null),
            'notif_email'     => setting('notif_email', ''),
            'sync_schedule'   => setting('sync_schedule', ''),
            'api_key'         => setting('api_key', ''),
        ];
        return view('admin.settings.index', compact('settings'));
    }

    /**
     * Memvalidasi dan menyimpan perubahan dari form pengaturan.
     */
    public function update(Request $request)
    {
        $validated = $request->validate([
            'dashboard_title' => 'required|string|max:100',
            'footer_text'     => 'nullable|string|max:255',
            'notif_email'     => 'nullable|email',
            'logo'            => 'nullable|image|mimes:png,jpg,jpeg,svg|max:2048',
            'favicon'         => 'nullable|image|mimes:png,ico|max:1024',
            'sync_schedule'   => 'nullable|string|max:50',
            'api_key'         => 'nullable|string|max:255',
            'new_password'    => 'nullable|string|min:8|confirmed',
        ]);

        // Simpan setting text
        setting([
            'dashboard_title' => $validated['dashboard_title'],
            'footer_text'     => $validated['footer_text'],
            'notif_email'     => $validated['notif_email'],
            'sync_schedule'   => $validated['sync_schedule'],
            'api_key'         => $validated['api_key'],
        ]);

        // Handle upload logo
        if ($request->hasFile('logo')) {
            // Hapus logo lama jika ada
            $oldLogo = setting('logo');
            if ($oldLogo && Storage::disk('public')->exists(str_replace('/storage/', '', $oldLogo))) {
                Storage::disk('public')->delete(str_replace('/storage/', '', $oldLogo));
            }
            $logoPath = $request->file('logo')->store('settings', 'public');
            setting(['logo' => '/storage/' . $logoPath]);
        }

        // Handle upload favicon
        if ($request->hasFile('favicon')) {
            $oldFavicon = setting('favicon');
            if ($oldFavicon && Storage::disk('public')->exists(str_replace('/storage/', '', $oldFavicon))) {
                Storage::disk('public')->delete(str_replace('/storage/', '', $oldFavicon));
            }
            $faviconPath = $request->file('favicon')->store('settings', 'public');
            setting(['favicon' => '/storage/' . $faviconPath]);
        }

        // Ganti password admin jika diisi
        if ($request->filled('new_password')) {
            $user = \Illuminate\Support\Facades\Auth::user();
            if ($user instanceof \App\Models\User) {
                $user->password = Hash::make($validated['new_password']);
                $user->save();
            }
        }

        return back()->with('status', 'Pengaturan berhasil diperbarui!');
    }

    // Backup database (opsional)
    public function backup()
    {
        // Contoh: backup SQLite, untuk MySQL/Postgres perlu logic tambahan
        $db = config('database.default');
        if ($db === 'sqlite') {
            $file = database_path(config('database.connections.sqlite.database'));
            return response()->download($file, 'backup-database.sqlite');
        }
        // Untuk MySQL/Postgres, tampilkan pesan atau handle sesuai kebutuhan
        return back()->with('status', 'Backup hanya tersedia untuk SQLite.');
    }
}
