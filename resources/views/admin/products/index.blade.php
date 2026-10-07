@extends('layouts.admin')
@section('title','Products')
@section('content')
<div class="flex flex-col sm:flex-row sm:items-end justify-between gap-4 mb-7">
    <div><p class="text-sm text-base-content/45 mb-1">Catalog management</p><h1 class="text-3xl md:text-4xl font-bold tracking-tight">Products</h1><p class="text-sm text-base-content/50 mt-2">Manage pricing, stock and visibility.</p></div>
    <a class="btn bg-[#B91C2C] hover:bg-[#9f1826] text-white border-0" href="{{ route('admin.products.create') }}">+ Add product</a>
</div>
<form class="as-card p-3 md:p-4 mb-5 flex flex-col md:flex-row gap-3">
    <label class="input input-bordered as-input bg-white flex items-center gap-2 grow"><svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 opacity-40" fill="none" viewBox="0 0 24 24" stroke="currentColor"><circle cx="11" cy="11" r="8"/><path d="m21 21-4.3-4.3"/></svg><input class="grow" name="q" value="{{ request('q') }}" placeholder="Search by product name or SKU"></label>
    <button class="btn bg-[#111] text-white border-0">Search</button>
    @if(request('q'))<a class="btn btn-ghost" href="{{ route('admin.products.index') }}">Clear</a>@endif
</form>
<div class="as-card overflow-hidden">
    <div class="overflow-x-auto">
        <table class="table">
            <thead><tr><th>Product</th><th>SKU</th><th>Price</th><th>Stock</th><th>Status</th><th class="text-right">Actions</th></tr></thead>
            <tbody>
            @forelse($products as $p)
                <tr class="hover:bg-base-100">
                    <td><div class="flex items-center gap-3"><div class="avatar"><div class="w-12 h-12 rounded-xl bg-base-200">@if($p->image)<img src="{{ asset('storage/'.$p->image) }}" alt="{{ $p->name }}">@else<div class="w-full h-full grid place-items-center text-base-content/30">◇</div>@endif</div></div><div><div class="font-semibold max-w-[260px] truncate">{{ $p->name }}</div><div class="text-xs text-base-content/45">{{ $p->category?->name ?? 'Uncategorized' }}</div></div></div></td>
                    <td class="font-mono text-xs">{{ $p->sku }}</td>
                    <td><div class="font-semibold">{{ config('store.symbol') }}{{ number_format($p->price) }}</div>@if($p->compare_price)<div class="text-xs line-through text-base-content/35">{{ config('store.symbol') }}{{ number_format($p->compare_price) }}</div>@endif</td>
                    <td><span class="font-semibold {{ $p->stock<=5?'text-error':'' }}">{{ $p->stock }}</span>@if($p->stock<=5)<div class="text-[10px] uppercase tracking-wide text-error">Low stock</div>@endif</td>
                    <td><span class="badge badge-sm {{ $p->is_active?'bg-emerald-50 text-emerald-700 border-emerald-200':'badge-ghost' }}">{{ $p->is_active?'Active':'Hidden' }}</span>@if($p->is_featured)<span class="badge badge-sm ml-1 bg-amber-50 text-amber-700 border-amber-200">Featured</span>@endif</td>
                    <td class="text-right whitespace-nowrap"><a class="btn btn-xs btn-ghost" href="{{ route('admin.products.edit',$p) }}">Edit</a><form class="inline" method="post" action="{{ route('admin.products.destroy',$p) }}" onsubmit="return confirm('Delete this product?')">@csrf @method('DELETE')<button class="btn btn-xs btn-ghost text-error">Delete</button></form></td>
                </tr>
            @empty
                <tr><td colspan="6"><div class="py-16 text-center"><div class="text-4xl opacity-20">◇</div><h3 class="font-semibold mt-3">No products found</h3><p class="text-sm text-base-content/45 mt-1">Add your first product or change the search.</p></div></td></tr>
            @endforelse
            </tbody>
        </table>
    </div>
</div>
<div class="mt-6">{{ $products->links() }}</div>
@endsection
