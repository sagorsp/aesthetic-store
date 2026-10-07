@extends('layouts.admin')
@section('title','Dashboard')
@section('content')
<div class="flex flex-col sm:flex-row sm:items-end justify-between gap-4 mb-7">
    <div>
        <p class="text-sm text-base-content/45 mb-1">Store overview</p>
        <h1 class="text-3xl md:text-4xl font-bold tracking-tight">Dashboard</h1>
        <p class="text-sm text-base-content/50 mt-2">Products, orders and store performance at a glance.</p>
    </div>
    <div class="flex gap-2">
        <a class="btn bg-[#111] text-white border-0 hover:bg-black" href="{{ route('admin.orders.index') }}">View orders</a>
        <a class="btn bg-[#B91C2C] text-white border-0 hover:bg-[#9f1826]" href="{{ route('admin.products.create') }}">+ Add product</a>
    </div>
</div>

<div class="grid sm:grid-cols-2 xl:grid-cols-4 gap-4">
    <div class="as-card p-5"><div class="flex justify-between items-start"><div><p class="text-sm text-base-content/50">Total products</p><p class="text-3xl font-bold mt-2">{{ number_format($stats['products']) }}</p></div><div class="w-11 h-11 rounded-2xl bg-[#111] text-[#C9A227] grid place-items-center text-xl">◇</div></div><a href="{{ route('admin.products.index') }}" class="text-xs text-[#B91C2C] font-semibold mt-5 inline-block">Manage catalog →</a></div>
    <div class="as-card p-5"><div class="flex justify-between items-start"><div><p class="text-sm text-base-content/50">Total orders</p><p class="text-3xl font-bold mt-2">{{ number_format($stats['orders']) }}</p></div><div class="w-11 h-11 rounded-2xl bg-red-50 text-[#B91C2C] grid place-items-center text-xl">▤</div></div><a href="{{ route('admin.orders.index') }}" class="text-xs text-[#B91C2C] font-semibold mt-5 inline-block">View all orders →</a></div>
    <div class="as-card p-5"><div class="flex justify-between items-start"><div><p class="text-sm text-base-content/50">Pending orders</p><p class="text-3xl font-bold mt-2">{{ number_format($stats['pending']) }}</p></div><div class="w-11 h-11 rounded-2xl bg-amber-50 text-amber-700 grid place-items-center text-xl">◷</div></div><a href="{{ route('admin.orders.index',['status'=>'pending']) }}" class="text-xs text-amber-700 font-semibold mt-5 inline-block">Needs attention →</a></div>
    <div class="as-card p-5"><div class="flex justify-between items-start"><div><p class="text-sm text-base-content/50">Sales value</p><p class="text-3xl font-bold mt-2">{{ config('store.symbol') }}{{ number_format($stats['sales']) }}</p></div><div class="w-11 h-11 rounded-2xl bg-[#C9A227]/15 text-[#9d7d17] grid place-items-center text-xl">৳</div></div><p class="text-xs text-base-content/40 mt-5">Confirmed / fulfilled order value</p></div>
</div>

<div class="grid xl:grid-cols-[1fr_340px] gap-5 mt-6">
    <div class="as-card overflow-hidden">
        <div class="p-5 md:p-6 flex items-center justify-between border-b border-base-200">
            <div><h2 class="font-bold text-lg">Recent orders</h2><p class="text-sm text-base-content/45">Latest customer activity</p></div>
            <a class="btn btn-sm btn-ghost" href="{{ route('admin.orders.index') }}">View all →</a>
        </div>
        <div class="overflow-x-auto">
            <table class="table">
                <thead><tr><th>Order</th><th>Customer</th><th>Total</th><th>Status</th><th></th></tr></thead>
                <tbody>
                @forelse($recent as $o)
                    <tr class="hover:bg-base-100">
                        <td><div class="font-semibold">{{ $o->order_number }}</div><div class="text-xs text-base-content/45">{{ $o->created_at->format('d M, h:i A') }}</div></td>
                        <td>{{ $o->customer_name }}<div class="text-xs text-base-content/45">{{ $o->phone }}</div></td>
                        <td class="font-semibold">{{ config('store.symbol') }}{{ number_format($o->total) }}</td>
                        <td><span class="badge badge-sm capitalize {{ $o->status==='delivered'?'badge-success text-white':($o->status==='cancelled'?'badge-error text-white':($o->status==='pending'?'badge-warning':'badge-ghost')) }}">{{ $o->status }}</span></td>
                        <td><a class="btn btn-xs btn-ghost" href="{{ route('admin.orders.show',$o) }}">Open →</a></td>
                    </tr>
                @empty
                    <tr><td colspan="5"><div class="py-12 text-center text-base-content/45">No orders yet. New orders will appear here.</div></td></tr>
                @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="space-y-5">
        <div class="as-card p-6 bg-[#111] text-white border-0">
            <div class="w-11 h-11 rounded-2xl bg-[#C9A227] text-[#111] grid place-items-center font-bold mb-5">AS</div>
            <h3 class="text-xl font-bold">Quick actions</h3>
            <p class="text-sm text-white/55 mt-1">Common store management tasks.</p>
            <div class="grid gap-2 mt-5">
                <a class="btn justify-between bg-white text-[#111] border-0" href="{{ route('admin.products.create') }}">Add a product <span>+</span></a>
                <a class="btn justify-between bg-white/10 text-white border-white/10" href="{{ route('admin.categories.index') }}">Manage categories <span>→</span></a>
                <a class="btn justify-between bg-white/10 text-white border-white/10" href="{{ route('admin.settings.edit') }}">Store settings <span>→</span></a>
            </div>
        </div>
        <div class="as-card p-5"><p class="text-sm text-base-content/45">Store contact</p><p class="font-semibold mt-2">{{ config('store.phone') }}</p><p class="text-sm text-base-content/60">{{ config('store.email') }}</p></div>
    </div>
</div>
@endsection
