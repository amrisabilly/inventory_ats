<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;
use Yajra\DataTables\Facades\DataTables;

class UserController extends Controller
{
    public function index(): View
    {
        return view('users.index');
    }

    public function data()
    {
        return DataTables::of(User::query()->select(['id', 'nama', 'username', 'email', 'role', 'no_hp']))
            ->addColumn('aksi', fn(User $user) => view('partials.datatables.actions', ['editUrl' => route('users.edit', $user), 'deleteUrl' => route('users.destroy', $user)])->render())
            ->rawColumns(['aksi'])
            ->make(true);
    }

    public function create(): View
    {
        return view('users.create');
    }

    public function store(StoreUserRequest $request): RedirectResponse
    {
        $data = $request->validated();
        if ($request->hasFile('foto')) {
            $data['foto'] = $request->file('foto')->store('users', 'public');
        }
        User::create($data);

        return redirect()->route('users.index')->with('success', 'User berhasil dibuat.');
    }

    public function edit(User $user): View
    {
        return view('users.edit', compact('user'));
    }

    public function update(UpdateUserRequest $request, User $user): RedirectResponse
    {
        $data = $request->validated();
        if ($request->hasFile('foto')) {
            if ($user->foto) Storage::disk('public')->delete($user->foto);
            $data['foto'] = $request->file('foto')->store('users', 'public');
        }
        if (blank($data['password'] ?? null)) unset($data['password']);
        $user->update($data);

        return redirect()->route('users.index')->with('success', 'User berhasil diperbarui.');
    }

    public function destroy(User $user): RedirectResponse
    {
        abort_if(auth()->id() === $user->id, 422, 'User yang sedang login tidak dapat dihapus.');
        $user->delete();

        return back()->with('success', 'User berhasil dihapus.');
    }
}
