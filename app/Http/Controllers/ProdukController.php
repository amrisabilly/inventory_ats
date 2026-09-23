<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreProdukRequest;
use App\Http\Requests\UpdateProdukRequest;
use App\Models\Material;
use App\Models\Produk;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Yajra\DataTables\Facades\DataTables;

class ProdukController extends Controller
{
    public function index(): View
    {
        return view('produk.index');
    }

    public function show(Produk $produk): View
    {
        return view('produk.show', ['produk' => $produk->load('boms.detailBoms.material')]);
    }

    public function data()
    {
        return DataTables::of(Produk::query()->with(['boms.detailBoms.material'])->withCount('boms'))
            ->addColumn('jumlah_bom', fn(Produk $produk) => $produk->boms_count)
            ->addColumn('bom', fn(Produk $produk) => view('partials.datatables.bom-summary', compact('produk'))->render())
            ->addColumn('aksi', fn(Produk $produk) => view('partials.datatables.actions', ['showUrl' => route('produk.show', $produk), 'editUrl' => route('produk.edit', $produk), 'deleteUrl' => route('produk.destroy', $produk)])->render())
            ->rawColumns(['aksi', 'bom'])
            ->make(true);
    }

    public function create(): View
    {
        return view('produk.create', ['materials' => Material::orderBy('nama_material')->get()]);
    }

    public function store(StoreProdukRequest $request): RedirectResponse
    {
        $produk = Produk::create($request->safe()->only(['nama_produk', 'deskripsi']));
        $bom = $produk->boms()->create(['nama_bom' => $request->string('nama_bom')->toString()]);
        $bom->detailBoms()->createMany($request->validated('bom_details'));

        return redirect()->route('produk.index')->with('success', 'Produk dan BOM berhasil dibuat.');
    }

    public function edit(Produk $produk): View
    {
        return view('produk.edit', ['produk' => $produk->load('boms.detailBoms'), 'materials' => Material::orderBy('nama_material')->get()]);
    }

    public function update(UpdateProdukRequest $request, Produk $produk): RedirectResponse
    {
        $produk->update($request->safe()->only(['nama_produk', 'deskripsi']));
        $bom = $produk->boms()->firstOrCreate([], ['nama_bom' => $request->string('nama_bom')->toString()]);
        $bom->update(['nama_bom' => $request->string('nama_bom')->toString()]);
        $bom->detailBoms()->delete();
        $bom->detailBoms()->createMany($request->validated('bom_details'));

        return redirect()->route('produk.index')->with('success', 'Produk dan BOM berhasil diperbarui.');
    }

    public function destroy(Produk $produk): RedirectResponse
    {
        $produk->delete();

        return back()->with('success', 'Produk berhasil dihapus.');
    }
}
