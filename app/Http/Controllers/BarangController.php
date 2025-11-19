<?php

namespace App\Http\Controllers;

use App\Models\Barang;
use App\Models\Kategori;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class BarangController extends Controller
{
    public function index()
    {
        // Jika petugas: lihat semua barang
        if (auth()->check() && auth()->user()->role === 'petugas') {
            $barang = Barang::with('kategori')->get();
        } else {
            // Mahasiswa / user lain / tamu: hanya barang dengan status 'tersedia'
            $barang = Barang::with('kategori')
                ->where('status', 'tersedia')
                ->get();
        }

        return view('barang.index', compact('barang'));
    }

    public function create()
    {
        $kategori = Kategori::all();

        return view('barang.create', compact('kategori'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'id_kategori' => 'required|exists:kategori,id_kategori',
            'nama_barang' => 'required|string|max:100',
            'kode_barang' => 'nullable|string|max:20|unique:barang,kode_barang',
            'harga' => 'nullable|numeric|min:0',
            'stok' => 'required|integer|min:0',
            'foto_barang' => 'nullable|image|max:2048',
        ]);

        $data['kode_barang'] = ($data['kode_barang'] ?? null) ?: $this->generateKodeBarang();
        $data['harga'] = $data['harga'] ?? null;
        $data['status'] = $data['stok'] > 0 ? 'tersedia' : 'dipinjam';

        if ($request->hasFile('foto_barang')) {
            $data['foto_path'] = $request->file('foto_barang')->store('barang', 'public');
        }

        Barang::create($data);

        return redirect()->route('barang.index')->with('success', 'Barang berhasil ditambahkan');
    }

    public function show(Barang $barang)
    {
        // pastikan relasi kategori ikut dibawa
        $barang->load('kategori');

        return view('barang.show', compact('barang'));
    }

    public function edit(Barang $barang)
    {
        $kategori = Kategori::all();

        return view('barang.edit', compact('barang', 'kategori'));
    }

    public function update(Request $request, Barang $barang)
    {
        $data = $request->validate([
            'id_kategori' => 'required|exists:kategori,id_kategori',
            'nama_barang' => 'required|string|max:100',
            'kode_barang' => 'nullable|string|max:20|unique:barang,kode_barang,' . $barang->id_barang . ',id_barang',
            'harga' => 'nullable|numeric|min:0',
            'stok' => 'required|integer|min:0',
            'foto_barang' => 'nullable|image|max:2048',
        ]);

        $data['kode_barang'] = ($data['kode_barang'] ?? null) ?: ($barang->kode_barang ?? $this->generateKodeBarang());
        $data['harga'] = $data['harga'] ?? $barang->harga;
        $data['stok'] = $data['stok'] ?? $barang->stok;

        if (in_array($barang->status, ['tersedia', 'dipinjam'])) {
            $data['status'] = $data['stok'] > 0 ? 'tersedia' : 'dipinjam';
        } else {
            $data['status'] = $barang->status;
        }

        if ($request->hasFile('foto_barang')) {
            if ($barang->foto_path) {
                Storage::disk('public')->delete($barang->foto_path);
            }
            $data['foto_path'] = $request->file('foto_barang')->store('barang', 'public');
        }

        $barang->update($data);

        return redirect()->route('barang.index')->with('success', 'Barang berhasil diperbarui');
    }

    public function destroy(Barang $barang)
    {
        if ($barang->foto_path) {
            Storage::disk('public')->delete($barang->foto_path);
        }

        $barang->delete();

        return redirect()->route('barang.index')->with('success', 'Barang berhasil dihapus');
    }

    private function generateKodeBarang(): string
    {
        $nextNumber = (Barang::max('id_barang') ?? 0) + 1;

        return 'BRG-' . str_pad((string) $nextNumber, 4, '0', STR_PAD_LEFT);
    }
}
