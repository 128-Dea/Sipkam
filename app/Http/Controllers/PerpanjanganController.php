<?php

namespace App\Http\Controllers;

use App\Models\Peminjaman;
use App\Models\Perpanjangan;
use Illuminate\Http\Request;

class PerpanjanganController extends Controller
{
    public function index()
    {
        $perpanjangan = Perpanjangan::with('peminjaman.pengguna')->orderByDesc('waktu_pengajuan')->get();

        return view('perpanjangan.index', compact('perpanjangan'));
    }

    public function create()
    {
        $peminjaman = Peminjaman::with('pengguna')->get();

        return view('perpanjangan.create', compact('peminjaman'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'id_peminjaman' => 'required|exists:peminjaman,id_peminjaman',
            'alasan' => 'required|string',
            'waktu_pengajuan' => 'required|date',
            'waktu_perpanjangan' => 'required|date|after:waktu_pengajuan',
        ]);

        $peminjaman = Peminjaman::findOrFail($data['id_peminjaman']);

        // Pastikan hanya pemilik peminjaman yang bisa mengajukan perpanjangan
        if ($peminjaman->id_pengguna !== $this->resolveAuthPenggunaId()) {
            return back()->withErrors(['id_peminjaman' => 'Anda tidak memiliki akses untuk memperpanjang peminjaman ini.']);
        }

        $data['status_persetujuan'] = 'menunggu';

        Perpanjangan::create($data);

        return redirect()->route('mahasiswa.perpanjangan.index')->with('success', 'Permohonan perpanjangan berhasil dikirim');
    }

    public function update(Request $request, Perpanjangan $perpanjangan)
    {
        $data = $request->validate([
            'status_persetujuan' => 'required|in:ditolak,disetujui,menunggu',
        ]);

        $perpanjangan->update($data);

        $routeScope = auth()->user()?->role === 'petugas' ? 'petugas' : 'mahasiswa';

        return redirect()->route($routeScope . '.perpanjangan.index')->with('success', 'Status perpanjangan diperbarui');
    }

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
