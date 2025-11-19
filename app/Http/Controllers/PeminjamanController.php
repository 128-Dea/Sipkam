<?php

namespace App\Http\Controllers;

use App\Models\Barang;
use App\Models\Peminjaman;
use App\Models\Pengguna;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PeminjamanController extends Controller
{
    public function index()
    {
        $peminjaman = Peminjaman::with(['pengguna', 'barang'])->orderByDesc('waktu_awal')->get();

        return view('peminjaman.index', compact('peminjaman'));
    }

    public function create()
    {
        $pengguna = Pengguna::where('role', 'mahasiswa')->get();
        $barang = Barang::where('status', 'tersedia')->where('stok', '>', 0)->get();

        return view('peminjaman.create', compact('pengguna', 'barang'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'id_barang' => 'required|exists:barang,id_barang',
            'waktu_awal' => 'required|date',
            'waktu_akhir' => 'required|date|after:waktu_awal',
            'alasan' => 'nullable|string',
        ]);

        $peminjaman = DB::transaction(function () use ($data) {
            $barang = Barang::where('id_barang', $data['id_barang'])
                ->where('status', 'tersedia')
                ->where('stok', '>', 0)
                ->lockForUpdate()
                ->firstOrFail();

            $peminjaman = Peminjaman::create([
                'id_pengguna' => auth()->id(),
                'id_barang' => $data['id_barang'],
                'waktu_awal' => $data['waktu_awal'],
                'waktu_akhir' => $data['waktu_akhir'],
                'alasan' => $data['alasan'],
                'status' => 'berlangsung',
            ]);

            $barang->decrement('stok');
            $barang->refresh();

            if (in_array($barang->status, ['tersedia', 'dipinjam'])) {
                $barang->status = $barang->stok > 0 ? 'tersedia' : 'dipinjam';
                $barang->save();
            }

            $qrCode = 'QR-PJM-' . uniqid();

            $peminjaman->qr()->create([
                'qr_code' => $qrCode,
                'jenis_transaksi' => 'peminjaman',
                'is_active' => true,
            ]);

            return $peminjaman;
        });

        return redirect()->route('peminjaman.show', $peminjaman)->with('success', 'Peminjaman berhasil dibuat. QR Code telah dihasilkan.');
    }

    public function show(Peminjaman $peminjaman)
    {
        $peminjaman->load(['pengguna', 'barang', 'pengembalian', 'denda', 'keluhan', 'perpanjangan', 'serahTerima', 'qr']);

        return view('peminjaman.show', compact('peminjaman'));
    }

    public function destroy(Peminjaman $peminjaman)
    {
        DB::transaction(function () use ($peminjaman) {
            if ($peminjaman->barang) {
                $peminjaman->barang->increment('stok');
                $peminjaman->barang->refresh();

                if (in_array($peminjaman->barang->status, ['tersedia', 'dipinjam'])) {
                    $peminjaman->barang->update([
                        'status' => $peminjaman->barang->stok > 0 ? 'tersedia' : 'dipinjam',
                    ]);
                }
            }
            $peminjaman->delete();
        });

        return redirect()->route('peminjaman.index')->with('success', 'Peminjaman berhasil dihapus');
    }
}
