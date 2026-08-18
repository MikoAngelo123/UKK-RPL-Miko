<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\LogAktivitas;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    /**
     * Tampilkan seluruh data pengguna sistem.
     */
    public function index(Request $request)
    {
        $role = $request->get('role');
        $search = $request->get('search');

        $users = User::when($role, function ($query, $role) {
                $query->where('role', $role);
            })
            ->when($search, function ($query, $search) {
                $query->where(function ($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%")
                      ->orWhere('username', 'like', "%{$search}%")
                      ->orWhere('email', 'like', "%{$search}%")
                      ->orWhere('no_telp', 'like', "%{$search}%");
                });
            })
            ->latest()
            ->paginate(10)
            ->withQueryString();

        return view('user.index', compact('users', 'role', 'search'));
    }

    /**
     * Form tambah pengguna baru.
     */
    public function create()
    {
        return view('user.create');
    }

    /**
     * Simpan data pengguna baru.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:100'],
            'username' => ['required', 'string', 'max:50', 'unique:users,username'],
            'email' => ['nullable', 'email', 'max:100', 'unique:users,email'],
            'password' => ['required', 'string', 'min:6'],
            'role' => ['required', 'in:admin,petugas,peminjam'],
            'no_telp' => ['nullable', 'string', 'max:20'],
            'alamat' => ['nullable', 'string', 'max:255'],
        ], [
            'name.required' => 'Nama lengkap wajib diisi.',
            'username.required' => 'Username wajib diisi.',
            'username.unique' => 'Username sudah digunakan.',
            'password.required' => 'Password wajib diisi.',
            'role.required' => 'Pilih peran / role pengguna.',
        ]);

        $validated['password'] = Hash::make($validated['password']);

        $user = User::create($validated);

        LogAktivitas::catat(Auth::id(), "Menambahkan pengguna baru: {$user->name} sebagai {$user->role}");

        return redirect()->route('user.index')->with('success', 'Data pengguna baru berhasil ditambahkan.');
    }

    /**
     * Form edit data pengguna.
     */
    public function edit($id)
    {
        $user = User::findOrFail($id);
        return view('user.edit', compact('user'));
    }

    /**
     * Perbarui data pengguna.
     */
    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:100'],
            'username' => ['required', 'string', 'max:50', 'unique:users,username,' . $id],
            'email' => ['nullable', 'email', 'max:100', 'unique:users,email,' . $id],
            'password' => ['nullable', 'string', 'min:6'],
            'role' => ['required', 'in:admin,petugas,peminjam'],
            'no_telp' => ['nullable', 'string', 'max:20'],
            'alamat' => ['nullable', 'string', 'max:255'],
        ]);

        if (!empty($validated['password'])) {
            $validated['password'] = Hash::make($validated['password']);
        } else {
            unset($validated['password']);
        }

        $user->update($validated);

        LogAktivitas::catat(Auth::id(), "Memperbarui profil pengguna: {$user->name}");

        return redirect()->route('user.index')->with('success', 'Data pengguna berhasil diperbarui.');
    }

    /**
     * Hapus data pengguna.
     */
    public function destroy($id)
    {
        if (Auth::id() == $id) {
            return back()->with('error', 'Anda tidak dapat menghapus akun Anda sendiri yang sedang aktif.');
        }

        $user = User::findOrFail($id);
        $nama = $user->name;
        $user->delete();

        LogAktivitas::catat(Auth::id(), "Menghapus akun pengguna: {$nama}");

        return redirect()->route('user.index')->with('success', "Akun '{$nama}' berhasil dihapus.");
    }
}
