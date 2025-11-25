<?php

namespace App\Http\Controllers;

use App\Models\Kategori;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Models\Service;

class KategoriController extends Controller
{
    public function index()
    {
        $kategori = Kategori::all();

        return view('kategori.index', compact('kategori'));
    }

    public function create()
    {
        return view('kategori.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'nama_kategori' => 'required|string|max:50',
        ]);

        Kategori::create([
            'kategori' => $data['nama_kategori'],
        ]);

        return redirect()->route('petugas.kategori.index')->with('success', 'Kategori berhasil ditambahkan');
    }

    public function edit(Kategori $kategori)
    {
        return view('kategori.edit', compact('kategori'));
    }

    public function show(Kategori $kategori)
    {
        // Tidak ada halaman detail, arahkan ke form edit agar URL ini tetap aman dipakai.
        return redirect()->route('petugas.kategori.edit', $kategori->id_kategori);
    }

    public function update(Request $request, Kategori $kategori)
    {
        $data = $request->validate([
            'nama_kategori' => 'required|string|max:50',
        ]);

        $kategori->update([
            'kategori' => $data['nama_kategori'],
        ]);

        return redirect()->route('petugas.kategori.index')->with('success', 'Kategori berhasil diperbarui');
    }

    public function destroy(Request $request, Kategori $kategori)
    {
        $barang = $kategori->barang()->get(['id_barang', 'nama_barang']);

        // Jika kategori masih dipakai, minta konfirmasi sebelum menghapus barang terkait.
        if ($barang->isNotEmpty() && !$request->boolean('confirm_delete')) {
            return view('kategori.confirm_delete', [
                'kategori' => $kategori,
                'barang'   => $barang,
            ]);
        }

        DB::transaction(function () use ($kategori, $barang) {
            foreach ($barang as $item) {
                // Bersihkan dependensi yang menempel ke barang agar tidak melanggar FK.
                $item->notifikasi()->delete();
                $item->peminjaman()->each(function ($peminjaman) {
                    $peminjaman->denda()->delete();
                    $peminjaman->perpanjangan()->delete();

                    $peminjaman->keluhan()->each(function ($keluhan) {
                        $keluhan->service()->delete();
                        $keluhan->delete();
                    });

                    if ($pengembalian = $peminjaman->pengembalian) {
                        $pengembalian->riwayat()->delete();
                        $pengembalian->delete();
                    }

                    $peminjaman->qr()->delete();
                    $peminjaman->delete();
                });

                Service::where('id_barang', $item->id_barang)->delete();
                $item->delete();
            }

            $kategori->delete();
        });

        return redirect()->route('petugas.kategori.index')->with('success', 'Kategori berhasil dihapus');
    }
}
