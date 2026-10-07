@extends('layouts.app')
@section('title','Shop')
@section('content')
<div class="as-container py-10"><div class="mb-8"><p class="text-[#B91C2C] uppercase tracking-widest font-semibold text-sm">Aesthetic Store</p><h1 class="brand-serif text-4xl font-bold">Shop imported products</h1></div>
<form class="grid md:grid-cols-[1fr_220px_180px_auto] gap-3 mb-8"><input class="input input-bordered w-full" name="q" value="{{ request('q') }}" placeholder="Search by product or SKU"><select class="select select-bordered" name="category"><option value="">All categories</option>@foreach($categories as $c)<option value="{{ $c->slug }}" @selected(request('category')===$c->slug)>{{ $c->name }}</option>@endforeach</select><select class="select select-bordered" name="sort"><option value="">Newest</option><option value="price_asc" @selected(request('sort')==='price_asc')>Price low to high</option><option value="price_desc" @selected(request('sort')==='price_desc')>Price high to low</option><option value="oldest" @selected(request('sort')==='oldest')>Oldest</option></select><button class="btn bg-[#111111] text-white border-0">Filter</button></form>
<div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-5">@forelse($products as $product)@include('partials.product-card',['product'=>$product])@empty<div class="col-span-full alert">No products found.</div>@endforelse</div>
<div class="mt-10">{{ $products->links() }}</div></div>
@endsection
