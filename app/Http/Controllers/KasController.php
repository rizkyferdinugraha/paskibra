<?php

namespace App\Http\Controllers;

use App\Models\KasTransaksi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class KasController extends Controller
{
    public function index()
    {
        $transaksis = KasTransaksi::orderBy('tanggal', 'desc')->orderBy('id', 'desc')->paginate(15);
        $totalPemasukan = KasTransaksi::where('jenis', 'pemasukan')->sum('jumlah');
        $totalPengeluaran = KasTransaksi::where('jenis', 'pengeluaran')->sum('jumlah');
        $saldo = $totalPemasukan - $totalPengeluaran;

        return view('admin.kas.index', compact('transaksis', 'totalPemasukan', 'totalPengeluaran', 'saldo'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'tanggal' => ['required', 'date'],
            'jenis' => ['required', 'in:pemasukan,pengeluaran'],
            'jumlah' => ['required', 'integer', 'min:1'],
            'keterangan' => ['nullable', 'string', 'max:255'],
        ]);

        $validated['created_by'] = Auth::id();

        DB::transaction(function () use ($validated): void {
            KasTransaksi::create($validated);
        });

        return redirect()->route('kas.index')->with('success', 'Transaksi kas berhasil disimpan.');
    }

    public function destroy(KasTransaksi $kas)
    {
        $kas->delete();
        return redirect()->route('kas.index')->with('success', 'Transaksi kas berhasil dihapus.');
    }
}


