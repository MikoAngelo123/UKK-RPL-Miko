<?php

namespace App\Http\Controllers;

use App\Models\Alat;
use App\Models\Kategori;
use App\Models\LogAktivitas;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class AlatController extends Controller
{
    /**
     * Tampilkan katalog / inventaris seluruh alat.
     */
    public function index(Request $request)
    {
        $search = $request->get('search');
        $kategoriId = $request->get('kategori_id');
        $kondisi = $request->get('kondisi');

        $kategoris = Kategori::orderBy('nama_kategori')->get();

        $alats = Alat::with('kategori')
            ->when($search, function ($query, $search) {
                $query->where(function ($q) use ($search) {
                    $q->where('nama_alat', 'like', "%{$search}%")
                      ->orWhere('kode_alat', 'like', "%{$search}%")
                      ->orWhere('deskripsi', 'like', "%{$search}%");
                });
            })
            ->when($kategoriId, function ($query, $kategoriId) {
                $query->where('id_kategori', $kategoriId);
            })
            ->when($kondisi, function ($query, $kondisi) {
                $query->where('kondisi', $kondisi);
            })
            ->latest()
            ->paginate(9)
            ->withQueryString();

        return view('alat.index', compact('alats', 'kategoris', 'search', 'kategoriId', 'kondisi'));
    }

    /**
     * Form tambah data alat baru.
     */
    public function create()
    {
        $kategoris = Kategori::orderBy('nama_kategori')->get();
        return view('alat.create', compact('kategoris'));
    }

    /**
     * Simpan data alat baru ke database.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'kode_alat' => ['required', 'string', 'max:50', 'unique:alats,kode_alat'],
            'nama_alat' => ['required', 'string', 'max:150'],
            'id_kategori' => ['required', 'exists:kategoris,id_kategori'],
            'stok' => ['required', 'integer', 'min:0'],
            'kondisi' => ['required', 'in:Baik,Perlu Perbaikan,Rusak'],
            'foto' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
            'deskripsi' => ['nullable', 'string'],
        ], [
            'kode_alat.required' => 'Kode alat wajib diisi.',
            'kode_alat.unique' => 'Kode alat sudah digunakan.',
            'nama_alat.required' => 'Nama alat wajib diisi.',
            'id_kategori.required' => 'Kategori alat harus dipilih.',
            'stok.required' => 'Jumlah stok wajib diisi.',
            'stok.min' => 'Stok minimal 0.',
            'kondisi.required' => 'Kondisi fisik alat harus dipilih.',
            'foto.image' => 'File foto harus berupa gambar.',
            'foto.max' => 'Ukuran file foto maksimal 2MB.',
        ]);

        if ($request->hasFile('foto')) {
            $path = $request->file('foto')->store('alat_foto', 'public');
            $validated['foto'] = $path;
        }

        $alat = Alat::create($validated);

        LogAktivitas::catat(Auth::id(), "Menambahkan data alat baru [{$alat->kode_alat}] {$alat->nama_alat}");

        return redirect()->route('alat.index')->with('success', 'Data alat baru berhasil disimpan.');
    }

    /**
     * Tampilkan detail alat beserta histori peminjamannya.
     */
    public function show($id)
    {
        $alat = Alat::with(['kategori', 'peminjamans.user'])->findOrFail($id);
        return view('alat.show', compact('alat'));
    }

    /**
     * Form edit data alat.
     */
    public function edit($id)
    {
        $alat = Alat::findOrFail($id);
        $kategoris = Kategori::orderBy('nama_kategori')->get();
        return view('alat.edit', compact('alat', 'kategoris'));
    }

    /**
     * Perbarui data alat di database.
     */
    public function update(Request $request, $id)
    {
        $alat = Alat::findOrFail($id);

        $validated = $request->validate([
            'kode_alat' => ['required', 'string', 'max:50', 'unique:alats,kode_alat,' . $id . ',id_alat'],
            'nama_alat' => ['required', 'string', 'max:150'],
            'id_kategori' => ['required', 'exists:kategoris,id_kategori'],
            'stok' => ['required', 'integer', 'min:0'],
            'kondisi' => ['required', 'in:Baik,Perlu Perbaikan,Rusak'],
            'foto' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
            'deskripsi' => ['nullable', 'string'],
        ]);

        if ($request->hasFile('foto')) {
            if ($alat->foto && Storage::disk('public')->exists($alat->foto)) {
                Storage::disk('public')->delete($alat->foto);
            }
            $path = $request->file('foto')->store('alat_foto', 'public');
            $validated['foto'] = $path;
        }

        $alat->update($validated);

        LogAktivitas::catat(Auth::id(), "Memperbarui data alat [{$alat->kode_alat}] {$alat->nama_alat}");

        return redirect()->route('alat.index')->with('success', 'Data alat berhasil diperbarui.');
    }

    /**
     * Hapus data alat.
     */
    public function destroy($id)
    {
        $alat = Alat::findOrFail($id);

        // Cek jika alat sedang dipinjam aktif
        $activeLoans = $alat->peminjamans()->whereIn('status', ['Menunggu Konfirmasi', 'Disetujui', 'Sedang Dipinjam'])->count();
        if ($activeLoans > 0) {
            return back()->with('error', 'Alat tidak dapat dihapus karena sedang dalam status peminjaman aktif.');
        }

        if ($alat->foto && Storage::disk('public')->exists($alat->foto)) {
            Storage::disk('public')->delete($alat->foto);
        }

        $nama = $alat->nama_alat;
        $alat->delete();

        LogAktivitas::catat(Auth::id(), "Menghapus alat: {$nama}");

        return redirect()->route('alat.index')->with('success', "Alat '{$nama}' berhasil dihapus.");
    }
}
