<!doctype html>
<html lang="id">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $title ?? 'Dashboard' }} - Inventory ATS</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body x-data="{ sidebarOpen: false }" class="min-h-screen">
    <div class="flex min-h-screen">
        <div x-show="sidebarOpen" x-cloak @click="sidebarOpen = false"
            class="fixed inset-0 z-30 bg-slate-900/40 lg:hidden"></div>

        <aside :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full lg:translate-x-0'"
            class="fixed inset-y-0 left-0 z-40 flex w-72 flex-col bg-primary text-white transition-transform lg:static">
            <div class="flex h-20 items-center gap-3 border-b border-white/10 px-6">
                <div class="flex h-10 w-10 items-center justify-center rounded-lg bg-secondary font-bold">IA</div>
                <div>
                    <p class="font-semibold tracking-wide">Inventory ATS</p>
                    <p class="text-xs text-white/60">Material & Purchase Order</p>
                </div>
            </div>

            <nav class="flex-1 space-y-1 px-4 py-6">
                <p class="mb-3 px-3 text-[11px] font-semibold uppercase tracking-widest text-white/45">Menu utama</p>
                <a href="{{ route('dashboard') }}"
                    class="flex items-center rounded-lg px-3 py-2.5 text-sm text-white/75 hover:bg-white/10 hover:text-white">Dashboard</a>
                @if (auth()->check() && auth()->user()->role === 'admin')
                    <a href="{{ route('materials.index') }}"
                        class="mt-1 flex items-center rounded-lg px-3 py-2.5 text-sm text-white/75 hover:bg-white/10 hover:text-white">Material</a>
                    <a href="{{ route('produk.index') }}"
                        class="flex items-center rounded-lg px-3 py-2.5 text-sm text-white/75 hover:bg-white/10 hover:text-white">Produk
                        & BOM</a>
                    <a href="{{ route('purchase-orders.index') }}"
                        class="flex items-center rounded-lg px-3 py-2.5 text-sm text-white/75 hover:bg-white/10 hover:text-white">Purchase
                        Order</a>
                    <a href="{{ route('users.index') }}"
                        class="flex items-center rounded-lg px-3 py-2.5 text-sm text-white/75 hover:bg-white/10 hover:text-white">User</a>
                    <a href="{{ route('laporan.index') }}"
                        class="flex items-center rounded-lg px-3 py-2.5 text-sm text-white/75 hover:bg-white/10 hover:text-white">Laporan</a>
                @endif
                @if (auth()->check() && auth()->user()->role === 'manajer')
                    <a href="{{ route('permintaan-produksi.index') }}"
                        class="flex items-center rounded-lg px-3 py-2.5 text-sm text-white/75 hover:bg-white/10 hover:text-white">Permintaan
                        Produksi</a>
                    <a href="{{ route('purchase-orders.index') }}"
                        class="flex items-center rounded-lg px-3 py-2.5 text-sm text-white/75 hover:bg-white/10 hover:text-white">Approval
                        PO</a>
                    <a href="{{ route('laporan.index') }}"
                        class="flex items-center rounded-lg px-3 py-2.5 text-sm text-white/75 hover:bg-white/10 hover:text-white">Laporan</a>
                @endif
                @if (auth()->check() && auth()->user()->role === 'staff_workshop')
                    <a href="{{ route('permintaan-produksi.index') }}"
                        class="flex items-center rounded-lg px-3 py-2.5 text-sm text-white/75 hover:bg-white/10 hover:text-white">Produksi</a>
                    <a href="{{ route('purchase-orders.index') }}"
                        class="flex items-center rounded-lg px-3 py-2.5 text-sm text-white/75 hover:bg-white/10 hover:text-white">Penerimaan
                        PO</a>
                    <a href="{{ route('stok-opname.index') }}"
                        class="flex items-center rounded-lg px-3 py-2.5 text-sm text-white/75 hover:bg-white/10 hover:text-white">Stok
                        Opname</a>
                @endif
            </nav>

            <div class="border-t border-white/10 p-4">
                @auth
                    <div class="mb-3 flex items-center gap-3 px-2">
                        <div
                            class="flex h-9 w-9 items-center justify-center rounded-full bg-secondary text-sm font-semibold">
                            {{ strtoupper(substr(auth()->user()->nama, 0, 1)) }}</div>
                        <div class="min-w-0">
                            <p class="truncate text-sm font-medium">{{ auth()->user()->nama }}</p>
                            <p class="truncate text-xs text-white/55">{{ auth()->user()->role ?? 'Pengguna' }}</p>
                        </div>
                    </div>
                @endauth
                <form method="POST" action="{{ route('logout') }}">@csrf<button type="submit"
                        class="w-full rounded-lg px-3 py-2 text-left text-sm text-white/70 hover:bg-white/10 hover:text-white">Keluar</button>
                </form>
            </div>
        </aside>

        <main class="min-w-0 flex-1">
            <header class="flex h-20 items-center justify-between border-b border-border bg-white px-4 sm:px-8">
                <div class="flex items-center gap-3"><button @click="sidebarOpen = true"
                        class="rounded-lg p-2 text-text-secondary hover:bg-surface lg:hidden"
                        aria-label="Buka menu">Menu</button>
                    <div>
                        <p class="text-xs text-text-secondary">{{ $breadcrumb ?? 'Workspace' }}</p>
                        <h1 class="text-lg font-semibold">{{ $title ?? 'Dashboard' }}</h1>
                    </div>
                </div>
                <div class="hidden text-right sm:block">
                    <p class="text-sm font-medium">{{ now()->translatedFormat('l, d F Y') }}</p>
                    <p class="text-xs text-text-secondary">Sistem pengelolaan material</p>
                </div>
            </header>
            <div class="p-4 sm:p-8">
                @if (session('success'))
                    <x-alert type="success" class="mb-5">{{ session('success') }}</x-alert>
                @endif
                @if (session('error'))
                    <x-alert type="danger" class="mb-5">{{ session('error') }}</x-alert>
                @endif
                @if ($errors->any())
                    <x-alert type="danger" class="mb-5">Periksa kembali input yang ditandai.</x-alert>
                @endif
                @yield('content')
            </div>
        </main>
    </div>
    @stack('scripts')
</body>

</html>
