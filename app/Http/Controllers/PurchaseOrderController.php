<?php

namespace App\Http\Controllers;

use App\Http\Requests\RejectPurchaseOrderRequest;
use App\Http\Requests\RevisePurchaseOrderRequest;
use App\Http\Requests\TerimaMaterialPoRequest;
use App\Models\Material;
use App\Models\PurchaseOrder;
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
            ->addColumn('aksi', fn (PurchaseOrder $row) => view('partials.datatables.actions', [
                'showUrl' => route('purchase-orders.show', $row),
                'editUrl' => auth()->user()->role === 'admin' && $row->status_po === 'rejected'
                    ? route('purchase-orders.revisi-form', $row)
                    : null,
            ])->render())
            ->rawColumns(['aksi', 'status_po'])
            ->make(true);
    }

    public function show(PurchaseOrder $purchaseOrder): View
    {
        return view('purchase-orders.show', ['purchaseOrder' => $purchaseOrder->load('detailPos.material', 'permintaanProduksi')]);
    }

    public function revisiForm(PurchaseOrder $purchaseOrder): View
    {
        abort_unless($purchaseOrder->status_po === 'rejected', 404);

        return view('purchase-orders.revisi', [
            'purchaseOrder' => $purchaseOrder->load('detailPos.material'),
            'materials' => Material::orderBy('nama_material')->get(),
        ]);
    }

    public function revisi(RevisePurchaseOrderRequest $request, PurchaseOrder $purchaseOrder, PurchaseOrderService $service): RedirectResponse
    {
        $service->revisiPO($purchaseOrder, $request->validated());

        return redirect()->route('purchase-orders.show', $purchaseOrder)->with('success', 'PO berhasil direvisi dan diajukan ulang untuk approval manager.');
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
