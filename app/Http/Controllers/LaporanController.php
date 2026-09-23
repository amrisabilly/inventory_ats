<?php

namespace App\Http\Controllers;

use App\Models\Material;
use App\Models\PurchaseOrder;
use App\Models\StokOpname;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Yajra\DataTables\Facades\DataTables;

class LaporanController extends Controller
{
    public function index(Request $request): View
    {
        return view('laporan.index', [
            'jenis' => $request->string('jenis', 'material')->toString(),
            'dari' => $request->string('dari')->toString(),
            'sampai' => $request->string('sampai')->toString(),
        ]);
    }

    public function data(Request $request)
    {
        $jenis = $request->string('jenis', 'material')->toString();
        $query = $this->reportQuery($request);
        $dataTable = DataTables::of($query->latest());

        if ($jenis === 'po') {
            $dataTable->addColumn('pengaju', fn(PurchaseOrder $row) => $row->user?->nama ?? '-');
        }

        if ($jenis === 'opname') {
            $dataTable
                ->addColumn('material_nama', fn(StokOpname $row) => $row->material?->nama_material ?? '-')
                ->addColumn('petugas', fn(StokOpname $row) => $row->user?->nama ?? '-');
        }

        return $dataTable->make(true);
    }

    public function cetakPdf(Request $request)
    {
        $jenis = $request->string('jenis', 'material')->toString();
        $data = $this->reportQuery($request)->latest()->get();

        return Pdf::loadView('laporan.pdf', compact('jenis', 'data'))->download("laporan-{$jenis}.pdf");
    }

    private function reportQuery(Request $request)
    {
        $jenis = $request->string('jenis', 'material')->toString();
        $dateColumn = match ($jenis) {
            'po' => 'tanggal_po',
            'opname' => 'tanggal_opname',
            default => 'created_at',
        };
        $query = match ($jenis) {
            'po' => PurchaseOrder::with('user'),
            'opname' => StokOpname::with(['material', 'user']),
            default => Material::query(),
        };

        return $query
            ->when($request->filled('dari'), fn($builder) => $builder->whereDate($dateColumn, '>=', $request->input('dari')))
            ->when($request->filled('sampai'), fn($builder) => $builder->whereDate($dateColumn, '<=', $request->input('sampai')));
    }
}
