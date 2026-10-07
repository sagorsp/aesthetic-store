<!doctype html>
<html lang="en" data-theme="light">
<head>
    @include('partials.global-gradient-theme')
    <meta charset="utf-8"><meta name="viewport" content="width=device-width, initial-scale=1"><meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Aesthetic Store') — {{ config('store.domain') }}</title>
    <meta name="description" content="@yield('meta_description', 'Chinese imported lifestyle products in Bangladesh. Shop Aesthetic Store online.')">
    <link rel="preconnect" href="https://cdn.jsdelivr.net">
    <link href="https://cdn.jsdelivr.net/npm/daisyui@5" rel="stylesheet" type="text/css" />
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
    <style>
        :root{--as-black:#111111;--as-red:#B91C2C;--as-gold:#C9A227;--as-cream:#FAF7F0}
        html{scroll-behavior:smooth}
        body{background:#fff;color:#111;font-family:Inter,ui-sans-serif,system-ui,-apple-system,BlinkMacSystemFont,"Segoe UI",sans-serif}
        .brand-serif{font-family:Georgia,'Times New Roman',serif}
        .as-container{max-width:1240px;margin:auto;padding-left:1rem;padding-right:1rem}
        .as-shadow{box-shadow:0 16px 40px rgba(17,17,17,.08)}
        .product-card:hover{transform:translateY(-4px)}
        .hide-scrollbar::-webkit-scrollbar{display:none}.hide-scrollbar{scrollbar-width:none}
        .header-icon{width:42px;height:42px;border-radius:999px;display:grid;place-items:center;transition:.2s ease}
        .header-icon:hover{background:#f5f5f5}
        .footer-social{width:42px;height:42px;border-radius:999px;border:1px solid rgba(255,255,255,.16);display:grid;place-items:center;transition:.2s ease}
        .footer-social:hover{background:#C9A227;color:#111;border-color:#C9A227;transform:translateY(-2px)}
        .live-search-wrap{position:relative}
        .live-search-box{position:absolute;left:0;right:0;top:calc(100% + .55rem);background:#fff;border:1px solid #eceae4;border-radius:1rem;box-shadow:0 20px 55px rgba(17,17,17,.14);overflow:hidden;z-index:80;display:none}
        .live-search-box.is-open{display:block}
        .live-search-item{display:flex;gap:.8rem;align-items:center;padding:.72rem .8rem;border-bottom:1px solid #f1f0eb;transition:.15s ease}
        .live-search-item:hover,.live-search-item.is-active{background:#faf8f2}
        .live-search-thumb{width:54px;height:54px;border-radius:.75rem;object-fit:cover;background:#f5f4ef;flex:none}
        .live-search-empty{padding:1rem;text-align:center;color:rgba(17,17,17,.5);font-size:.85rem}
    </style>
    @stack('head')
</head>
<body class="min-h-screen bg-white">

    <div class="bg-[#111111] text-white text-center text-[11px] md:text-xs py-2 px-4">
        Authentic imported finds • Cash on Delivery available • Support:
        {{ $storeSettings['support_phone'] ?? config('store.phone') }}
    </div>

    <header class="sticky top-0 z-50 bg-white/95 backdrop-blur border-b border-base-200">
        <div class="as-container">
            <div class="flex items-center gap-3 md:gap-6 min-h-20">

                {{-- LOGO --}}
                <a href="{{ route('home') }}" class="shrink-0 flex items-center" aria-label="Aesthetic Store home">
                    @if(!empty($storeSettings['store_logo']))
                        <img src="{{ asset('storage/'.$storeSettings['store_logo']) }}" alt="Aesthetic Store" class="h-12 md:h-14 w-auto object-contain">
                    @else
                        <img src="{{ asset('images/aesthetic-store-logo.png') }}" alt="Aesthetic Store" class="h-12 md:h-14 w-auto object-contain">
                    @endif
                </a>

                {{-- LIVE PRODUCT SEARCH --}}
                <form action="{{ route('shop') }}" class="hidden sm:block flex-1 max-w-2xl mx-auto live-search-wrap" data-live-search-form>
                    <label class="input input-bordered w-full h-11 rounded-full bg-[#fafafa] border-[#e8e8e8] flex items-center gap-3 px-4 focus-within:border-[#C9A227] focus-within:outline-none">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-black/40" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                            <circle cx="11" cy="11" r="7"></circle><path d="m20 20-3.5-3.5"></path>
                        </svg>
                        <input autocomplete="off" type="search" name="q" value="{{ request('q') }}" placeholder="Search products..." class="grow bg-transparent outline-none text-sm" data-live-search-input>
                        <span class="loading loading-spinner loading-xs text-[#B91C2C] hidden" data-live-search-loader></span>
                    </label>
                    <div class="live-search-box" data-live-search-box></div>
                </form>

                {{-- ACCOUNT --}}
                @auth
                    <div class="dropdown dropdown-end ml-auto sm:ml-0">
                        <button tabindex="0" class="header-icon" aria-label="Account">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7">
                                <circle cx="12" cy="8" r="4"></circle><path d="M4 21a8 8 0 0 1 16 0"></path>
                            </svg>
                        </button>
                        <ul tabindex="0" class="dropdown-content menu bg-base-100 rounded-2xl z-[60] w-56 p-2 shadow-xl border border-base-200">
                            <li class="menu-title">{{ auth()->user()->name }}</li>
                            @if(auth()->user()->is_admin)
                                <li><a href="{{ route('admin.dashboard') }}">Admin panel</a></li>
                            @else
                                <li><a href="{{ route('account.dashboard') }}">My account</a></li>
                                <li><a href="{{ route('wishlist.index') }}">Wishlist</a></li>
                            @endif
                            <li>
                                <form method="post" action="{{ route('logout') }}">@csrf
                                    <button class="w-full text-left">Logout</button>
                                </form>
                            </li>
                        </ul>
                    </div>
                @else
                    <a class="header-icon ml-auto sm:ml-0" href="{{ route('login') }}" aria-label="Customer login" title="Account">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7">
                            <circle cx="12" cy="8" r="4"></circle><path d="M4 21a8 8 0 0 1 16 0"></path>
                        </svg>
                    </a>
                @endauth

                {{-- CART --}}
                <a class="header-icon relative" href="{{ route('cart.index') }}" aria-label="Cart" title="Cart">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7">
                        <path d="M3 4h2l2.2 10.2a2 2 0 0 0 2 1.6h7.9a2 2 0 0 0 2-1.6L21 7H6"></path>
                        <circle cx="10" cy="20" r="1"></circle><circle cx="18" cy="20" r="1"></circle>
                    </svg>
                    <span class="absolute top-0 right-0 min-w-5 h-5 px-1 rounded-full bg-[#B91C2C] text-white text-[10px] font-bold grid place-items-center">
                        {{ array_sum(array_column(session('cart',[]),'quantity')) }}
                    </span>
                </a>
            </div>

            {{-- MOBILE LIVE SEARCH --}}
            <form action="{{ route('shop') }}" class="sm:hidden pb-3 live-search-wrap" data-live-search-form>
                <label class="input input-bordered w-full h-10 rounded-full bg-[#fafafa] border-[#e8e8e8] flex items-center gap-3 px-4">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-black/40" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                        <circle cx="11" cy="11" r="7"></circle><path d="m20 20-3.5-3.5"></path>
                    </svg>
                    <input autocomplete="off" type="search" name="q" value="{{ request('q') }}" placeholder="Search products..." class="grow bg-transparent outline-none text-sm" data-live-search-input>
                    <span class="loading loading-spinner loading-xs text-[#B91C2C] hidden" data-live-search-loader></span>
                </label>
                <div class="live-search-box" data-live-search-box></div>
            </form>
        </div>
    </header>

    @if(session('success'))<div class="as-container pt-4"><div class="alert alert-success text-white">{{ session('success') }}</div></div>@endif
    @if(session('error'))<div class="as-container pt-4"><div class="alert alert-error text-white">{{ session('error') }}</div></div>@endif
    @if($errors->any())<div class="as-container pt-4"><div class="alert alert-error text-white"><ul class="list-disc ml-5">@foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul></div></div>@endif

    <main>@yield('content')</main>

    <footer class="mt-16 bg-[#111111] text-white">
        <div class="as-container py-12 md:py-14">
            <div class="grid lg:grid-cols-[1.3fr_.8fr_.9fr] gap-10">

                <div>
                    <div class="inline-flex bg-white rounded-2xl p-3 mb-5">
                        @if(!empty($storeSettings['store_logo']))
                            <img src="{{ asset('storage/'.$storeSettings['store_logo']) }}" alt="Aesthetic Store" class="h-14 w-auto object-contain">
                        @else
                            <img src="{{ asset('images/aesthetic-store-logo.png') }}" alt="Aesthetic Store" class="h-14 w-auto object-contain">
                        @endif
                    </div>
                    <p class="text-white/60 max-w-md leading-7">
                        Curated imported products for modern everyday life in Bangladesh — useful, trendy and fairly priced.
                    </p>

                    <div class="flex items-center gap-3 mt-6">
                        @if(!empty($storeSettings['facebook_url']))
                        <a href="{{ $storeSettings['facebook_url'] }}" target="_blank" rel="noopener" class="footer-social" aria-label="Facebook">
                            <svg class="w-5 h-5" viewBox="0 0 24 24" fill="currentColor"><path d="M13.5 22v-9h3l.5-3.5h-3.5V7.3c0-1 .3-1.8 1.8-1.8H17V2.4c-.3 0-1.5-.1-2.8-.1-2.8 0-4.7 1.7-4.7 4.8v2.4H6.4V13h3.1v9h4z"/></svg>
                        </a>
                        @endif
                        @if(!empty($storeSettings['instagram_url']))
                        <a href="{{ $storeSettings['instagram_url'] }}" target="_blank" rel="noopener" class="footer-social" aria-label="Instagram">
                            <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><rect x="3" y="3" width="18" height="18" rx="5"/><circle cx="12" cy="12" r="4"/><circle cx="17.5" cy="6.5" r="1" fill="currentColor" stroke="none"/></svg>
                        </a>
                        @endif
                        @if(!empty($storeSettings['tiktok_url']))
                        <a href="{{ $storeSettings['tiktok_url'] }}" target="_blank" rel="noopener" class="footer-social" aria-label="TikTok">
                            <svg class="w-5 h-5" viewBox="0 0 24 24" fill="currentColor"><path d="M14 3h3a5 5 0 0 0 4 4v3a8 8 0 0 1-4-1.1V15a6 6 0 1 1-6-6c.4 0 .7 0 1 .1v3.1A3 3 0 1 0 14 15V3z"/></svg>
                        </a>
                        @endif
                        @if(!empty($storeSettings['youtube_url']))
                        <a href="{{ $storeSettings['youtube_url'] }}" target="_blank" rel="noopener" class="footer-social" aria-label="YouTube">
                            <svg class="w-5 h-5" viewBox="0 0 24 24" fill="currentColor"><path d="M22 12s0-4-1-6c-.6-1.6-1.8-1.8-3-2-2.2-.2-6-.2-6-.2s-3.8 0-6 .2c-1.2.2-2.4.4-3 2-1 2-1 6-1 6s0 4 1 6c.6 1.6 1.8 1.8 3 2 2.2.2 6 .2 6 .2s3.8 0 6-.2c1.2-.2 2.4-.4 3-2 1-2 1-6 1-6Zm-12.5 3.6V8.4L16 12l-6.5 3.6Z"/></svg>
                        </a>
                        @endif
                    </div>
                </div>

                <div>
                    <h4 class="font-semibold text-[#C9A227] mb-4">Quick Links</h4>
                    <div class="grid gap-3 text-white/65 text-sm">
                        <a class="hover:text-white" href="{{ route('shop') }}">Shop</a>
                        <a class="hover:text-white" href="{{ route('track.form') }}">Track Order</a>
                        <a class="hover:text-white" href="{{ route('cart.index') }}">Cart</a>
                        <a class="hover:text-white" href="{{ route('login') }}">Customer Login</a>
                    </div>
                </div>

                <div>
                    <h4 class="font-semibold text-[#C9A227] mb-4">Customer Support</h4>
                    <div class="grid gap-3 text-white/65 text-sm">
                        <a class="hover:text-white" href="tel:{{ $storeSettings['support_phone'] ?? config('store.phone') }}">
                            {{ $storeSettings['support_phone'] ?? config('store.phone') }}
                        </a>
                        <a class="hover:text-white break-all" href="mailto:{{ $storeSettings['support_email'] ?? config('store.email') }}">
                            {{ $storeSettings['support_email'] ?? config('store.email') }}
                        </a>
                        <span>{{ $storeSettings['address'] ?? 'Dhaka, Bangladesh' }}</span>
                    </div>
                </div>
            </div>
        </div>

        <div class="border-t border-white/10">
            <div class="as-container py-4 flex flex-col sm:flex-row gap-2 items-center justify-between text-xs text-white/40">
                <span>© {{ date('Y') }} Aesthetic Store. All rights reserved.</span>
                <span>Imported finds • Bangladesh</span>
            </div>
        </div>
    </footer>

<script>
(() => {
    const endpoint = @json(route('shop'));
    const money = value => '৳' + Number(value || 0).toLocaleString('en-BD', {maximumFractionDigits: 2});

    function escapeHtml(value) {
        const div = document.createElement('div');
        div.textContent = value ?? '';
        return div.innerHTML;
    }

    document.querySelectorAll('[data-live-search-form]').forEach(form => {
        const input = form.querySelector('[data-live-search-input]');
        const box = form.querySelector('[data-live-search-box]');
        const loader = form.querySelector('[data-live-search-loader]');
        let timer = null;
        let controller = null;
        let selectedIndex = -1;

        const close = () => {
            box.classList.remove('is-open');
            selectedIndex = -1;
        };

        const open = () => box.classList.add('is-open');

        const setLoading = state => loader?.classList.toggle('hidden', !state);

        const render = (data, query) => {
            const products = data.products || [];

            if (!products.length) {
                box.innerHTML = `<div class="live-search-empty">No products found for “${escapeHtml(query)}”</div>`;
                open();
                return;
            }

            const rows = products.map(product => `
                <a href="${product.url}" class="live-search-item" data-live-search-result>
                    ${product.image
                        ? `<img src="${product.image}" alt="" class="live-search-thumb">`
                        : `<div class="live-search-thumb grid place-items-center text-xl">📦</div>`}
                    <div class="min-w-0 flex-1">
                        <div class="text-sm font-semibold truncate">${escapeHtml(product.name)}</div>
                        <div class="text-[11px] text-black/45 mt-0.5 truncate">${escapeHtml(product.category || 'Product')}${product.sku ? ' • ' + escapeHtml(product.sku) : ''}</div>
                        <div class="mt-1 flex items-center gap-2">
                            <span class="text-sm font-bold text-[#B91C2C]">${money(product.price)}</span>
                            ${product.compare_price ? `<span class="text-xs text-black/35 line-through">${money(product.compare_price)}</span>` : ''}
                        </div>
                    </div>
                    <span class="text-black/25">›</span>
                </a>
            `).join('');

            box.innerHTML = rows + `
                <a href="${endpoint}?q=${encodeURIComponent(query)}" class="block text-center px-4 py-3 text-sm font-semibold text-[#B91C2C] bg-[#fcfaf4] hover:bg-[#f8f2df]">
                    View all search results →
                </a>`;
            open();
        };

        async function search() {
            const query = input.value.trim();

            if (!query) {
                box.innerHTML = '';
                close();
                return;
            }

            if (controller) controller.abort();
            controller = new AbortController();

            setLoading(true);

            try {
                const response = await fetch(`${endpoint}?q=${encodeURIComponent(query)}&live=1`, {
                    headers: {'Accept': 'application/json'},
                    signal: controller.signal
                });
                if (!response.ok) throw new Error('Search failed');
                render(await response.json(), query);
            } catch (error) {
                if (error.name !== 'AbortError') {
                    box.innerHTML = `<div class="live-search-empty">Search is temporarily unavailable.</div>`;
                    open();
                }
            } finally {
                setLoading(false);
            }
        }

        input.addEventListener('input', () => {
            clearTimeout(timer);
            timer = setTimeout(search, 180);
        });

        input.addEventListener('focus', () => {
            if (input.value.trim() && box.innerHTML.trim()) open();
        });

        input.addEventListener('keydown', e => {
            const results = [...box.querySelectorAll('[data-live-search-result]')];
            if (!results.length) return;

            if (e.key === 'ArrowDown') {
                e.preventDefault();
                selectedIndex = Math.min(selectedIndex + 1, results.length - 1);
            } else if (e.key === 'ArrowUp') {
                e.preventDefault();
                selectedIndex = Math.max(selectedIndex - 1, 0);
            } else if (e.key === 'Enter' && selectedIndex >= 0) {
                e.preventDefault();
                results[selectedIndex].click();
                return;
            } else if (e.key === 'Escape') {
                close();
                return;
            } else {
                return;
            }

            results.forEach((el, i) => el.classList.toggle('is-active', i === selectedIndex));
            results[selectedIndex]?.scrollIntoView({block:'nearest'});
        });

        document.addEventListener('click', e => {
            if (!form.contains(e.target)) close();
        });
    });
})();
</script>

</body>
</html>