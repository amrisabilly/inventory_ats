<?php

namespace App\Http\Controllers;

use App\Http\Requests\RejectPurchaseOrderRequest;
use App\Http\Requests\StorePurchaseOrderRequest;
use App\Http\Requests\TerimaMaterialPoRequest;
use App\Models\Material;
use App\Models\PurchaseOrder;
use App\Models\PermintaanProduksi;
use App\Services\PurchaseOrderService;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Yajra\DataTables\Facades\DataTables;

class PurchaseOrderController extends Controller
{
    public function index(): View
    {
        return view('purchase-orders.index');
    }

    public function data()
    {
        return DataTables::of(PurchaseOrder::with(['user', 'permintaanProduksi']))
            ->addColumn('pengaju', fn(PurchaseOrder $row) => $row->user->nama)
            ->editColumn('status_po', fn(PurchaseOrder $row) => view('partials.datatables.status', ['status' => $row->status_po])->render())
            ->addColumn('aksi', fn(PurchaseOrder $row) => view('partials.datatables.actions', ['showUrl' => route('purchase-orders.show', $row)])->render())
            ->rawColumns(['aksi', 'status_po'])
            ->make(true);
    }

    public function create(): View
    {
        return view('purchase-orders.create', ['materials' => Material::orderBy('nama_material')->get(), 'permintaan' => PermintaanProduksi::where('status_permintaan', 'menunggu_po')->get()]);
    }

    public function store(StorePurchaseOrderRequest $request, PurchaseOrderService $service): RedirectResponse
    {
        $service->buatPO($request->validated(), auth()->user());

        return redirect()->route('purchase-orders.index')->with('success', 'Purchase order berhasil diajukan.');
    }

    public function show(PurchaseOrder $purchaseOrder): View
    {
        return view('purchase-orders.show', ['purchaseOrder' => $purchaseOrder->load('detailPos.material', 'permintaanProduksi')]);
    }

    public function approve(PurchaseOrder $purchaseOrder, PurchaseOrderService $service): RedirectResponse
    {
        $service->approvePO($purchaseOrder);

        return back()->with('success', 'Purchase order berhasil disetujui.');
    }

    public function reject(RejectPurchaseOrderRequest $request, PurchaseOrder $purchaseOrder, PurchaseOrderService $service): RedirectResponse
    {
        $service->rejectPO($purchaseOrder, $request->validated('catatan_penolakan'));

        return back()->with('success', 'Purchase order ditolak.');
    }

    public function terimaMaterial(TerimaMaterialPoRequest $request, PurchaseOrder $purchaseOrder, PurchaseOrderService $service): RedirectResponse
    {
        $service->terimaMaterialPO($purchaseOrder, $request->validated('items_diterima'));

        return back()->with('success', 'Penerimaan material berhasil dicatat.');
    }
}
