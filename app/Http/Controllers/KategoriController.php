<?php

namespace App\Http\Controllers;

use App\Models\Kategori;
use App\Models\LogAktivitas;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class KategoriController extends Controller
{
    /**
     * Tampilkan daftar seluruh kategori alat.
     */
    public function index(Request $request)
    {
        $search = $request->get('search');

        $kategoris = Kategori::withCount('alats')
            ->when($search, function ($query, $search) {
                $query->where('nama_kategori', 'like', "%{$search}%")
                      ->orWhere('deskripsi', 'like', "%{$search}%");
            })
            ->latest()
            ->paginate(10)
            ->withQueryString();

        return view('kategori.index', compact('kategoris', 'search'));
    }

    /**
     * Simpan data kategori baru ke database.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama_kategori' => ['required', 'string', 'max:100', 'unique:kategoris,nama_kategori'],
            'deskripsi' => ['nullable', 'string', 'max:255'],
        ], [
            'nama_kategori.required' => 'Nama kategori wajib diisi.',
            'nama_kategori.unique' => 'Nama kategori sudah ada.',
        ]);

        $kategori = Kategori::create($validated);

        LogAktivitas::catat(Auth::id(), "Menambahkan kategori alat baru: {$kategori->nama_kategori}");

        return redirect()->route('kategori.index')->with('success', 'Kategori baru berhasil ditambahkan.');
    }

    /**
     * Perbarui data kategori yang sudah ada.
     */
    public function update(Request $request, $id)
    {
        $kategori = Kategori::findOrFail($id);

        $validated = $request->validate([
            'nama_kategori' => ['required', 'string', 'max:100', 'unique:kategoris,nama_kategori,' . $id . ',id_kategori'],
            'deskripsi' => ['nullable', 'string', 'max:255'],
        ], [
            'nama_kategori.required' => 'Nama kategori wajib diisi.',
            'nama_kategori.unique' => 'Nama kategori sudah digunakan oleh data lain.',
        ]);

        $kategori->update($validated);

        LogAktivitas::catat(Auth::id(), "Memperbarui kategori alat: {$kategori->nama_kategori}");

        return redirect()->route('kategori.index')->with('success', 'Kategori berhasil diperbarui.');
    }

    /**
     * Hapus kategori dari database.
     */
    public function destroy($id)
    {
        $kategori = Kategori::withCount('alats')->findOrFail($id);

        if ($kategori->alats_count > 0) {
            return back()->with('error', 'Kategori tidak dapat dihapus karena masih digunakan oleh beberapa data alat.');
        }

        $nama = $kategori->nama_kategori;
        $kategori->delete();

        LogAktivitas::catat(Auth::id(), "Menghapus kategori alat: {$nama}");

        return redirect()->route('kategori.index')->with('success', "Kategori '{$nama}' berhasil dihapus.");
    }
}
