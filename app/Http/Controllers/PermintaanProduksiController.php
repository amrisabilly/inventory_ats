<?php

namespace App\Http\Controllers;

use App\Http\Requests\StorePermintaanProduksiRequest;
use App\Models\PermintaanProduksi;
use App\Models\Produk;
use App\Services\ProduksiService;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Yajra\DataTables\Facades\DataTables;

class PermintaanProduksiController extends Controller
{
    public function index(): View
    {
        return view('permintaan-produksi.index');
    }

    public function data()
    {
        return DataTables::of(PermintaanProduksi::with(['produk', 'user']))
            ->addColumn('produk_nama', fn(PermintaanProduksi $row) => $row->produk->nama_produk)
            ->addColumn('pembuat', fn(PermintaanProduksi $row) => $row->user->nama)
            ->editColumn('status_permintaan', fn(PermintaanProduksi $row) => view('partials.datatables.status', ['status' => $row->status_permintaan])->render())
            ->addColumn('aksi', function (PermintaanProduksi $row) {
                return view('partials.datatables.actions', [
                    'showUrl' => route('permintaan-produksi.show', $row),
                    'processUrl' => auth()->user()->role === 'admin' && $row->status_permintaan === 'pending'
                        ? route('permintaan-produksi.show', $row)
                        : null,
                ])->render();
            })
            ->rawColumns(['aksi', 'status_permintaan'])
            ->make(true);
    }

    public function create(): View
    {
        return view('permintaan-produksi.create', ['produk' => Produk::orderBy('nama_produk')->get()]);
    }

    public function store(StorePermintaanProduksiRequest $request): RedirectResponse
    {
        PermintaanProduksi::create([...$request->validated(), 'user_id' => auth()->id(), 'status_permintaan' => 'pending']);

        return redirect()->route('permintaan-produksi.index')->with('success', 'Permintaan produksi berhasil dibuat.');
    }

    public function show(PermintaanProduksi $permintaanProduksi, ProduksiService $service): View
    {
        $kebutuhan = [];
        $ketersediaan = [];

        if ($permintaanProduksi->produk->boms()->exists()) {
            $kebutuhan = $service->hitungKebutuhanMaterial($permintaanProduksi);
            $ketersediaan = $service->cekKetersediaanStok($kebutuhan);
        }

        return view('permintaan-produksi.show', compact('permintaanProduksi', 'kebutuhan', 'ketersediaan'));
    }

    public function proses(PermintaanProduksi $permintaanProduksi, ProduksiService $service): RedirectResponse
    {
        $service->prosesPermintaanProduksi($permintaanProduksi, auth()->user());

        return back()->with('success', 'Permintaan produksi berhasil diproses. PO otomatis dibuat jika ada material yang kurang.');
    }

    public function mulai(PermintaanProduksi $permintaanProduksi, ProduksiService $service): RedirectResponse
    {
        $service->mulaiProduksi($permintaanProduksi);

        return back()->with('success', 'Produksi dimulai dan material dialokasikan ke WIP.');
    }

    public function selesai(PermintaanProduksi $permintaanProduksi, ProduksiService $service): RedirectResponse
    {
        $service->selesaikanProduksi($permintaanProduksi);

        return back()->with('success', 'Produksi berhasil diselesaikan.');
    }
}
