<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreStokOpnameRequest;
use App\Models\Material;
use App\Models\StokOpname;
use App\Services\StokOpnameService;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Yajra\DataTables\Facades\DataTables;

class StokOpnameController extends Controller
{
    public function index(): View
    {
        return view('stok-opname.index');
    }

    public function data()
    {
        return DataTables::of(StokOpname::with(['material', 'user']))
            ->addColumn('material_nama', fn(StokOpname $row) => $row->material->nama_material)
            ->addColumn('petugas', fn(StokOpname $row) => $row->user->nama)
            ->make(true);
    }

    public function create(): View
    {
        return view('stok-opname.create', ['materials' => Material::orderBy('nama_material')->get()]);
    }

    public function store(StoreStokOpnameRequest $request, StokOpnameService $service): RedirectResponse
    {
        $material = Material::findOrFail($request->integer('material_id'));
        $service->catatOpname($material, $request->integer('stok_fisik'), auth()->user());

        return redirect()->route('stok-opname.index')->with('success', 'Stok opname berhasil dicatat.');
    }
}
