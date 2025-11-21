<?php

namespace App\Http\Controllers;

use App\Models\Denda;
use App\Models\Peminjaman;
use App\Models\Pengembalian;
use App\Models\Riwayat;
use App\Models\Qr;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PengembalianController extends Controller
{
    /**
     * INDEX
     * Dipakai PETUGAS: lihat semua pengembalian yang sudah tercatat.
     */
    public function index()
    {
        $pengembalian = Pengembalian::with([
                'peminjaman.pengguna',
                'peminjaman.barang',
                'peminjaman.denda',
            ])
            ->orderByDesc('waktu_pengembalian')
            ->get();

        return view('pengembalian.index', compact('pengembalian'));
    }

    /**
     * FORM PENGEMBALIAN (MAHASISWA)
     */
    public function create()
    {
        $query = Peminjaman::with(['barang', 'pengguna'])
            ->whereDoesntHave('pengembalian'); // hanya yang belum dikembalikan

        if (auth()->user()->role === 'mahasiswa') {
            $authPenggunaId = $this->resolveAuthPenggunaId();
            $query->whereHas('pengguna', function ($q) use ($authPenggunaId) {
                $q->where('id_pengguna', $authPenggunaId);
            });
        }

        $peminjaman = $query->get();

        return view('pengembalian.create', compact('peminjaman'));
    }

    /**
     * STORE UMUM
     * Dipakai MAHASISWA (manual) dan bisa juga dipakai kalau mau dipanggil dari petugas.
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'id_peminjaman'      => 'required|exists:peminjaman,id_peminjaman',
            'waktu_pengembalian' => 'required|date',
            'catatan'            => 'nullable|string',
            'biaya_rusak'        => 'nullable|numeric|min:0',
            'biaya_hilang'       => 'nullable|numeric|min:0',
        ]);

        $peminjaman = Peminjaman::with(['barang', 'pengguna', 'qr'])->findOrFail($data['id_peminjaman']);

        // Kalau mahasiswa, pastikan peminjaman itu milik dia
        if (auth()->user()->role === 'mahasiswa') {
            if (!$peminjaman->pengguna || $peminjaman->pengguna->id_pengguna !== $this->resolveAuthPenggunaId()) {
                abort(403, 'Anda tidak boleh mengembalikan peminjaman milik orang lain.');
            }
        }

        $this->prosesPengembalianTransaksi($data, $peminjaman);

        // Redirect sesuai role
        if (auth()->user()->role === 'mahasiswa') {
            return redirect()
                ->route('mahasiswa.dashboard')
                ->with('success', 'Pengembalian berhasil dikirim, menunggu verifikasi petugas.');
        }

        return redirect()
            ->route('petugas.pengembalian.index')
            ->with('success', 'Pengembalian berhasil diproses');
    }

    /**
     * FORM SCAN QR (PETUGAS)
     */
    public function scanForm()
    {
        return view('pengembalian.scan');
    }

    /**
     * HANDLE HASIL SCAN QR (PETUGAS)
     * Menerima qr_code, cari peminjaman, lalu arahkan ke halaman konfirmasi.
     */
    public function handleScan(Request $request)
    {
        $data = $request->validate([
            'qr_code' => 'required|string',
        ]);

        $qr = Qr::with(['peminjaman.pengguna', 'peminjaman.barang'])
            ->where('qr_code', $data['qr_code'])
            ->where('jenis_transaksi', 'peminjaman')
            ->where('is_active', true)
            ->first();

        if (!$qr || !$qr->peminjaman) {
            return back()->withErrors([
                'qr_code' => 'QR tidak valid, peminjaman tidak ditemukan, atau sudah dikembalikan.',
            ]);
        }

        return redirect()->route('petugas.pengembalian.konfirmasi', $qr->peminjaman->id_peminjaman);
    }

    /**
     * HALAMAN KONFIRMASI SETELAH QR (PETUGAS)
     * Pertanyaan: barang berfungsi baik atau tidak.
     */
    public function konfirmasi(Peminjaman $peminjaman)
    {
        $peminjaman->load(['barang', 'pengguna', 'qr']);

        return view('pengembalian.konfirmasi', compact('peminjaman'));
    }

    /**
     * PROSES JIKA BARANG BAIK (PETUGAS)
     * Tidak ada kerusakan → denda hanya dari keterlambatan (kalau ada).
     */
    public function prosesTanpaKerusakan(Request $request, Peminjaman $peminjaman)
    {
        $data = [
            'id_peminjaman'      => $peminjaman->id_peminjaman,
            'waktu_pengembalian' => now(),
            'catatan'            => $request->input('catatan'),
            'biaya_rusak'        => 0,
            'biaya_hilang'       => 0,
        ];

        $this->prosesPengembalianTransaksi($data, $peminjaman);

        $peminjaman->load('pengembalian');

        return view('pengembalian.sukses', compact('peminjaman'));
    }

    /**
     * FORM JIKA ADA KERUSAKAN / TIDAK BERFUNGSI (PETUGAS)
     */
    public function formKerusakan(Peminjaman $peminjaman)
    {
        $peminjaman->load(['barang', 'pengguna']);

        return view('pengembalian.kerusakan', compact('peminjaman'));
    }

    /**
     * PROSES JIKA ADA KERUSAKAN (PETUGAS)
     * Petugas isi catatan dan denda (kalau memang ditanggung peminjam).
     */
    public function prosesDenganKerusakan(Request $request, Peminjaman $peminjaman)
    {
        $data = $request->validate([
            'waktu_pengembalian' => 'nullable|date',
            'catatan'            => 'required|string',
            'biaya_rusak'        => 'nullable|numeric|min:0',
            'biaya_hilang'       => 'nullable|numeric|min:0',
        ]);

        $data['id_peminjaman'] = $peminjaman->id_peminjaman;
        if (empty($data['waktu_pengembalian'])) {
            $data['waktu_pengembalian'] = now();
        }

        $this->prosesPengembalianTransaksi($data, $peminjaman);

        $peminjaman->load('pengembalian');

        return view('pengembalian.sukses', compact('peminjaman'));
    }

    /**
     * LOGIKA TRANSAKSI PENGEMBALIAN
     * Dipakai oleh store(), prosesTanpaKerusakan(), dan prosesDenganKerusakan().
     */
    protected function prosesPengembalianTransaksi(array $data, Peminjaman $peminjaman): void
    {
        DB::transaction(function () use ($data, $peminjaman) {
            // Simpan pengembalian
            $pengembalian = Pengembalian::create([
                'id_peminjaman'      => $data['id_peminjaman'],
                'waktu_pengembalian' => $data['waktu_pengembalian'],
                'catatan'            => $data['catatan'] ?? null,
            ]);

            // Hitung denda terlambat
            $waktuAkhir        = strtotime($peminjaman->waktu_akhir);
            $waktuPengembalian = strtotime($data['waktu_pengembalian']);
            $terlambatMenit    = max(0, ($waktuPengembalian - $waktuAkhir) / 60);
            $dendaTerlambat    = $terlambatMenit * 1000; // 1000 per menit

            $totalDenda = $dendaTerlambat;

            if (!empty($data['biaya_rusak'])) {
                $totalDenda += $data['biaya_rusak'];
            }

            if (!empty($data['biaya_hilang'])) {
                $totalDenda += $data['biaya_hilang'];
            }

            // Simpan denda jika ada
            if ($totalDenda > 0) {
                Denda::create([
                    'id_peminjaman'     => $peminjaman->id_peminjaman,
                    'jenis'             => 'pengembalian',
                    'total_denda'       => $totalDenda,
                    'status_pembayaran' => 'belum_dibayar',
                    'keterangan'        => 'Denda pengembalian barang',
                ]);
            }

            // Nonaktifkan QR
            if ($peminjaman->qr) {
                $peminjaman->qr->update(['is_active' => false]);
            }

            // Update status peminjaman & stok barang
            $peminjaman->update(['status' => 'selesai']);

            if ($peminjaman->barang) {
                $peminjaman->barang->increment('stok');
                $peminjaman->barang->refresh();

                if (in_array($peminjaman->barang->status, ['tersedia', 'dipinjam'])) {
                    $peminjaman->barang->update([
                        'status' => $peminjaman->barang->stok > 0 ? 'tersedia' : 'dipinjam',
                    ]);
                }
            }

            // Simpan ke tabel riwayat
            Riwayat::create([
                'id_pengembalian' => $pengembalian->id_pengembalian,
                'serah_terima'    => $totalDenda > 0 ? 'ya' : 'tidak',
                'denda'           => $totalDenda,
            ]);
        });
    }

    /**
     * Helper: resolve id_pengguna dari user auth
     */
    protected function resolveAuthPenggunaId(): ?int
    {
        $user = auth()->user();

        if (!$user) {
            return null;
        }

        $pengguna = \App\Models\Pengguna::find($user->id);

        if (!$pengguna && $user->email) {
            $pengguna = \App\Models\Pengguna::where('email', $user->email)->first();
        }

        if (!$pengguna) {
            $nomorHp = $user->phone ?? $user->nomor_hp ?? '0';
            $pengguna = new \App\Models\Pengguna();
            $pengguna->id_pengguna = $user->id;
            $pengguna->nama = $user->name ?? 'Pengguna';
            $pengguna->email = $user->email;
            $pengguna->nomor_hp = $nomorHp;
            $pengguna->role = $user->role ?? 'mahasiswa';
            $pengguna->save();
        }

        return $pengguna->id_pengguna;
    }
}
