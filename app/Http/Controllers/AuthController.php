<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\LogAktivitas;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    /**
     * Tampilkan halaman form login.
     */
    public function showLogin()
    {
        if (Auth::check()) {
            return redirect()->route('dashboard');
        }
        return view('auth.login');
    }

    /**
     * Proses autentikasi login pengguna.
     */
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'username' => ['required', 'string'],
            'password' => ['required', 'string'],
        ], [
            'username.required' => 'Username wajib diisi.',
            'password.required' => 'Password wajib diisi.',
        ]);

        if (Auth::attempt($credentials, $request->boolean('remember'))) {
            $request->session()->regenerate();
            $user = Auth::user();

            LogAktivitas::catat($user->id, "User {$user->name} ({$user->role}) berhasil masuk ke sistem.");

            return redirect()->intended(route('dashboard'))
                ->with('success', "Selamat datang kembali, {$user->name}!");
        }

        return back()->withErrors([
            'username' => 'Kombinasi username atau password yang dimasukkan salah.',
        ])->onlyInput('username');
    }

    /**
     * Tampilkan halaman registrasi untuk siswa / peminjam baru.
     */
    public function showRegister()
    {
        if (Auth::check()) {
            return redirect()->route('dashboard');
        }
        return view('auth.register');
    }

    /**
     * Proses registrasi akun peminjam baru.
     */
    public function register(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:100'],
            'username' => ['required', 'string', 'max:50', 'unique:users,username'],
            'email' => ['nullable', 'email', 'max:100', 'unique:users,email'],
            'password' => ['required', 'string', 'min:6', 'confirmed'],
            'no_telp' => ['nullable', 'string', 'max:20'],
            'alamat' => ['nullable', 'string', 'max:255'],
        ], [
            'name.required' => 'Nama lengkap wajib diisi.',
            'username.required' => 'Username wajib diisi.',
            'username.unique' => 'Username sudah digunakan, silakan pilih yang lain.',
            'password.required' => 'Password wajib diisi.',
            'password.min' => 'Password minimal 6 karakter.',
            'password.confirmed' => 'Konfirmasi password tidak cocok.',
        ]);

        $user = User::create([
            'name' => $validated['name'],
            'username' => $validated['username'],
            'email' => $validated['email'] ?? null,
            'password' => Hash::make($validated['password']),
            'role' => 'peminjam',
            'no_telp' => $validated['no_telp'] ?? null,
            'alamat' => $validated['alamat'] ?? null,
        ]);

        Auth::login($user);
        LogAktivitas::catat($user->id, "Pengguna baru terdaftar sebagai peminjam: {$user->name}");

        return redirect()->route('dashboard')->with('success', 'Akun berhasil dibuat. Selamat datang di Aplikasi Peminjaman Barang!');
    }

    /**
     * Proses logout pengguna.
     */
    public function logout(Request $request)
    {
        $user = Auth::user();
        if ($user) {
            LogAktivitas::catat($user->id, "User {$user->name} logout dari sistem.");
        }

        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login')->with('success', 'Anda telah berhasil keluar.');
    }
}
