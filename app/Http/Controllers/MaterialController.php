<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreMaterialRequest;
use App\Http\Requests\UpdateMaterialRequest;
use App\Models\Material;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;
use Yajra\DataTables\Facades\DataTables;

class MaterialController extends Controller
{
    public function index(): View
    {
        return view('materials.index');
    }

    public function data()
    {
        return DataTables::of(Material::query())
            ->addColumn('aksi', fn(Material $material) => view('partials.datatables.actions', ['editUrl' => route('materials.edit', $material), 'deleteUrl' => route('materials.destroy', $material)])->render())
            ->rawColumns(['aksi'])
            ->make(true);
    }

    public function create(): View
    {
        return view('materials.create');
    }

    public function store(StoreMaterialRequest $request): RedirectResponse
    {
        $data = $request->validated();
        $data['stok_wip'] ??= 0;
        if ($request->hasFile('foto')) $data['foto'] = $request->file('foto')->store('materials', 'public');
        Material::create($data);

        return redirect()->route('materials.index')->with('success', 'Material berhasil dibuat.');
    }

    public function edit(Material $material): View
    {
        return view('materials.edit', compact('material'));
    }

    public function update(UpdateMaterialRequest $request, Material $material): RedirectResponse
    {
        $data = $request->validated();
        if ($request->hasFile('foto')) {
            if ($material->foto) Storage::disk('public')->delete($material->foto);
            $data['foto'] = $request->file('foto')->store('materials', 'public');
        }
        $material->update($data);

        return redirect()->route('materials.index')->with('success', 'Material berhasil diperbarui.');
    }

    public function destroy(Material $material): RedirectResponse
    {
        if ($material->foto) Storage::disk('public')->delete($material->foto);
        $material->delete();

        return back()->with('success', 'Material berhasil dihapus.');
    }

    public function stock(Material $material): JsonResponse
    {
        return response()->json($material->only(['id', 'nama_material', 'satuan', 'stok_sistem', 'stok_wip']));
    }
}
