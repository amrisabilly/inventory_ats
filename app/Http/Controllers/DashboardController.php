<?php

namespace App\Http\Controllers;

use App\Models\Material;
use App\Models\PermintaanProduksi;
use App\Models\PurchaseOrder;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        return view('dashboard', [
            'role' => auth()->user()->role,
            'totalMaterial' => Material::count(),
            'stokKritis' => Material::where('stok_sistem', '<=', 10)->count(),
            'poMenunggu' => PurchaseOrder::where('status_po', 'diajukan')->count(),
            'produksiAktif' => PermintaanProduksi::where('status_permintaan', '!=', 'selesai')->count(),
        ]);
    }
}
