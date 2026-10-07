<!doctype html>
<html lang="en" data-theme="light">
<head>
    @include('partials.global-gradient-theme')
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title','Admin') — Aesthetic Store</title>
    <link href="https://cdn.jsdelivr.net/npm/daisyui@5" rel="stylesheet" type="text/css">
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
    <style>
        :root{--as-black:#111111;--as-red:#B91C2C;--as-gold:#C9A227;--as-bg:#f6f6f4}
        html{scroll-behavior:smooth} body{background:var(--as-bg)}
        .as-card{background:#fff;border:1px solid #e8e8e5;border-radius:1.25rem;box-shadow:0 1px 2px rgba(17,17,17,.03)}
        .as-nav-active{background:rgba(201,162,39,.14)!important;color:#f0cf63!important}
        .as-input:focus{outline:none!important;border-color:#c9a227!important;box-shadow:0 0 0 3px rgba(201,162,39,.12)!important}
        .table :where(th,td){padding-top:.9rem;padding-bottom:.9rem}
    </style>
</head>
<body class="text-[#171717]">
<div class="drawer lg:drawer-open min-h-screen">
    <input id="admin-drawer" type="checkbox" class="drawer-toggle">
    <div class="drawer-content min-w-0">
        <header class="h-[70px] navbar bg-white/95 backdrop-blur border-b border-[#e9e9e6] sticky top-0 z-30 px-3 md:px-6">
            <div class="flex-none lg:hidden">
                <label for="admin-drawer" class="btn btn-square btn-ghost" aria-label="Open menu">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/></svg>
                </label>
            </div>
            <div class="flex-1 min-w-0">
                <div class="text-xs text-black/40 hidden sm:block">Aesthetic Store / @yield('title','Admin')</div>
                <div class="font-semibold truncate sm:hidden">@yield('title','Admin')</div>
            </div>
            <div class="flex items-center gap-2">
                <a href="{{ route('home') }}" target="_blank" class="btn btn-sm btn-ghost hidden sm:inline-flex">
                    Storefront
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 3h7v7m0-7L10 14M5 7v12h12v-5"/></svg>
                </a>
                <div class="dropdown dropdown-end">
                    <button tabindex="0" class="btn btn-ghost btn-circle avatar placeholder" aria-label="Account menu">
                        <div class="bg-[#111] text-[#C9A227] rounded-full w-9"><span class="text-xs font-bold">AS</span></div>
                    </button>
                    <div tabindex="0" class="dropdown-content mt-3 z-[40] w-56 rounded-2xl bg-white border border-base-200 shadow-xl p-2">
                        <div class="px-3 py-2 border-b border-base-200 mb-1"><p class="text-xs text-base-content/45">Signed in as</p><p class="font-medium text-sm truncate">{{ auth()->user()?->email }}</p></div>
                        <a href="{{ route('home') }}" target="_blank" class="btn btn-sm btn-ghost w-full justify-start sm:hidden">View storefront</a>
                        <form method="post" action="{{ route('admin.logout') }}">@csrf<button class="btn btn-sm btn-ghost text-error w-full justify-start">Logout</button></form>
                    </div>
                </div>
            </div>
        </header>

        <main class="p-4 md:p-6 xl:p-8 max-w-[1600px] mx-auto">
            @if(session('success'))
                <div class="alert bg-emerald-50 border border-emerald-200 text-emerald-800 mb-5 rounded-2xl"><span>{{ session('success') }}</span></div>
            @endif
            @if(session('error'))
                <div class="alert bg-red-50 border border-red-200 text-red-800 mb-5 rounded-2xl"><span>{{ session('error') }}</span></div>
            @endif
            @if($errors->any())
                <div class="alert bg-red-50 border border-red-200 text-red-800 mb-5 rounded-2xl items-start"><div><div class="font-semibold mb-1">Please check the form</div><ul class="list-disc list-inside text-sm">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul></div></div>
            @endif
            @yield('content')
        </main>
    </div>

    <div class="drawer-side z-40">
        <label for="admin-drawer" class="drawer-overlay"></label>
        <aside class="bg-[#111111] text-white min-h-full w-[285px] flex flex-col border-r border-white/5">
            <div class="h-[70px] px-5 flex items-center border-b border-white/10">
                <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-xl border border-[#C9A227]/50 grid place-items-center font-serif text-xl text-[#C9A227]">AS</div>
                    <div><div class="font-serif tracking-[.18em] text-[15px]">AESTHETIC</div><div class="text-[10px] tracking-[.35em] text-white/45">STORE ADMIN</div></div>
                </a>
            </div>

            <div class="px-4 py-5 flex-1 overflow-y-auto">
                <p class="px-3 mb-2 text-[10px] uppercase tracking-[.22em] text-white/35">Management</p>
                <ul class="menu gap-1 p-0 text-[14px]">
                    <li><a class="{{ request()->routeIs('admin.dashboard')?'as-nav-active':'' }} rounded-xl" href="{{ route('admin.dashboard') }}"><span class="w-5">◫</span> Dashboard</a></li>
                    <li><a class="{{ request()->routeIs('admin.products.*')?'as-nav-active':'' }} rounded-xl" href="{{ route('admin.products.index') }}"><span class="w-5">◇</span> Products</a></li>
                    <li><a class="{{ request()->routeIs('admin.categories.*')?'as-nav-active':'' }} rounded-xl" href="{{ route('admin.categories.index') }}"><span class="w-5">▦</span> Categories</a></li>
                    <li><a class="{{ request()->routeIs('admin.orders.*')?'as-nav-active':'' }} rounded-xl" href="{{ route('admin.orders.index') }}"><span class="w-5">▤</span> Orders</a></li>
                    <li><a class="{{ request()->routeIs('admin.reviews.*')?'as-nav-active':'' }} rounded-xl" href="{{ route('admin.reviews.index') }}"><span class="w-5">★</span> Reviews</a></li>
                </ul>

                @if(auth()->user()?->hasAdminRole(['owner','manager']))
                <p class="px-3 mt-7 mb-2 text-[10px] uppercase tracking-[.22em] text-white/35">Growth & Store</p>
                <ul class="menu gap-1 p-0 text-[14px]">
                    <li><a class="{{ request()->routeIs('admin.hero-slides.*')?'as-nav-active':'' }} rounded-xl" href="{{ route('admin.hero-slides.index') }}"><span class="w-5">▣</span> Hero sliders</a></li>
                    <li><a class="{{ request()->routeIs('admin.delivery-zones.*')?'as-nav-active':'' }} rounded-xl" href="{{ route('admin.delivery-zones.index') }}"><span class="w-5">🚚</span> Delivery charges</a></li>
                    <li><a class="{{ request()->routeIs('admin.reports.*')?'as-nav-active':'' }} rounded-xl" href="{{ route('admin.reports.sales') }}"><span class="w-5">↗</span> Sales reports</a></li>
                </ul>
                @endif
                @if(auth()->user()?->hasAdminRole('owner'))
                <p class="px-3 mt-7 mb-2 text-[10px] uppercase tracking-[.22em] text-white/35">Owner</p>
                <ul class="menu gap-1 p-0 text-[14px]">
                    <li><a class="{{ request()->routeIs('admin.admins.*')?'as-nav-active':'' }} rounded-xl" href="{{ route('admin.admins.index') }}"><span class="w-5">👥</span> Admin users</a></li>
                    <li><a class="{{ request()->routeIs('admin.settings.*')?'as-nav-active':'' }} rounded-xl" href="{{ route('admin.settings.edit') }}"><span class="w-5">⚙</span> Store settings</a></li>
                </ul>
                @endif
                <ul class="menu gap-1 p-0 text-[14px] mt-5"><li><a class="rounded-xl" href="{{ route('home') }}" target="_blank"><span class="w-5">↗</span> View storefront</a></li></ul>
            </div>

            <div class="p-4 border-t border-white/10">
                <div class="rounded-2xl bg-white/[.06] p-4">
                    <p class="text-xs text-white/45">Support</p>
                    <p class="font-semibold mt-1">{{ config('store.phone') }}</p>
                    <p class="text-xs text-white/45 truncate">{{ config('store.email') }}</p>
                </div>
            </div>
        </aside>
    </div>
</div>
</body>
</html>
