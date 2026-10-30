<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Schema;
use Illuminate\View\View;

class LoginController extends Controller
{
    /**
     * Tampilkan halaman login.
     */
    public function show(): View
    {
        return view('login');
    }

    /**
     * Proses autentikasi login.
     */
    public function authenticate(Request $request): RedirectResponse
    {
        $request->validate([
            'username' => ['required', 'string'],
            'password' => ['required', 'string'],
        ]);

        $loginInput = trim($request->input('username'));
        $password = $request->input('password');

        // Pastikan kolom 'username' ada di tabel users secara otomatis
        if (Schema::hasTable('users') && !Schema::hasColumn('users', 'username')) {
            try {
                Schema::table('users', function (Blueprint $table) {
                    $table->string('username')->nullable()->unique()->after('name');
                });
            } catch (\Throwable $e) {
                // Abaikan jika sudah ada atau terjadi race condition
            }
        }

        // Jika belum ada user admin di tabel users, otomatis buatkan
        $hasAdmin = User::where('username', 'admin')->orWhere('email', 'admin@raccer.com')->exists();
        if (!$hasAdmin) {
            User::create([
                'name' => 'Admin',
                'username' => 'admin',
                'email' => 'admin@raccer.com',
                'password' => Hash::make('admin123'),
            ]);
        }

        // Cari user berdasarkan username ATAU email
        $user = User::where(function ($query) use ($loginInput) {
            $query->where('username', $loginInput)
                ->orWhere('email', $loginInput);
        })->first();

        // Verifikasi password
        if ($user && Hash::check($password, $user->password)) {
            Auth::login($user, $request->boolean('remember'));
            $request->session()->regenerate();

            return redirect()->intended('/admin');
        }

        return back()->withErrors([
            'username' => 'Username atau password salah.',
        ])->onlyInput('username');
    }

    /**
     * Proses logout.
     */
    public function logout(Request $request): RedirectResponse
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/login');
    }
}
