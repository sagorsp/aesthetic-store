@extends('layouts.app')

@section('title','My Account')

@section('content')
<style>
    .account-card{background:#fff;border:1px solid #eceae4;border-radius:1.35rem;box-shadow:0 10px 32px rgba(17,17,17,.035)}
    .account-muted{color:rgba(17,17,17,.48)}
    .account-input{border-radius:.85rem!important;border-color:#deddd7!important}
    .account-input:focus{outline:none!important;border-color:#C9A227!important;box-shadow:0 0 0 4px rgba(201,162,39,.1)!important}
    .status-pill{display:inline-flex;align-items:center;padding:.3rem .6rem;border-radius:999px;font-size:.7rem;font-weight:800;text-transform:capitalize}
</style>

<div class="as-container py-8 md:py-12">
    <div class="flex flex-col md:flex-row md:items-end justify-between gap-4 mb-8">
        <div>
            <p class="text-xs font-bold tracking-[.18em] uppercase text-[#B91C2C] mb-2">Customer Account</p>
            <h1 class="text-3xl md:text-4xl font-bold tracking-tight">Welcome, {{ $user->name }}</h1>
            <p class="account-muted mt-2 text-sm">Manage your profile, orders, wishlist and delivery addresses.</p>
        </div>

        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button class="btn btn-outline rounded-xl">Logout</button>
        </form>
    </div>

    {{-- SUMMARY --}}
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-3 md:gap-4 mb-7">
        <div class="account-card p-4 md:p-5">
            <div class="text-xs account-muted">Total Orders</div>
            <div class="text-2xl font-bold mt-1">{{ $user->orders()->count() }}</div>
        </div>
        <div class="account-card p-4 md:p-5">
            <div class="text-xs account-muted">Wishlist</div>
            <div class="text-2xl font-bold mt-1">{{ $wishlistCount }}</div>
        </div>
        <div class="account-card p-4 md:p-5">
            <div class="text-xs account-muted">Saved Addresses</div>
            <div class="text-2xl font-bold mt-1">{{ $addresses->count() }}</div>
        </div>
        <div class="account-card p-4 md:p-5">
            <div class="text-xs account-muted">Latest Order</div>
            <div class="text-sm font-bold mt-2 truncate">
                {{ optional($recentOrders->first())->order_number ?? 'No orders yet' }}
            </div>
        </div>
    </div>

    <div class="grid lg:grid-cols-[300px_minmax(0,1fr)] gap-6">
        {{-- SIDEBAR --}}
        <aside class="space-y-4">
            <div class="account-card p-5">
                <div class="w-16 h-16 rounded-full bg-[#111] text-[#C9A227] grid place-items-center text-xl font-bold mb-4">
                    {{ strtoupper(substr($user->name,0,1)) }}
                </div>
                <h2 class="font-bold text-lg">{{ $user->name }}</h2>
                <p class="text-sm account-muted break-all">{{ $user->email }}</p>

                <div class="grid gap-2 mt-5">
                    <a href="#orders" class="btn btn-sm justify-start btn-ghost rounded-lg">My Orders</a>
                    <a href="{{ route('wishlist.index') }}" class="btn btn-sm justify-start btn-ghost rounded-lg">Wishlist</a>
                    <a href="#addresses" class="btn btn-sm justify-start btn-ghost rounded-lg">Saved Addresses</a>
                    <a href="#profile" class="btn btn-sm justify-start btn-ghost rounded-lg">Profile Settings</a>
                    <a href="{{ route('shop') }}" class="btn btn-sm justify-start rounded-lg bg-[#111] text-white border-0">Continue Shopping</a>
                </div>
            </div>

            {{-- RECENT ORDERS --}}
            <div class="account-card p-5">
                <h3 class="font-bold mb-4">Recent Orders</h3>
                <div class="space-y-3">
                    @forelse($recentOrders as $order)
                        <div class="pb-3 border-b border-base-200 last:border-0 last:pb-0">
                            <div class="flex items-center justify-between gap-2">
                                <span class="font-semibold text-sm">{{ $order->order_number }}</span>
                                <span class="text-xs account-muted">৳{{ number_format((float)$order->total) }}</span>
                            </div>
                            <div class="text-xs account-muted mt-1">{{ $order->created_at->format('d M Y') }} • {{ ucfirst($order->status) }}</div>
                        </div>
                    @empty
                        <p class="text-sm account-muted">No orders yet.</p>
                    @endforelse
                </div>
            </div>
        </aside>

        {{-- CONTENT --}}
        <div class="space-y-6">
            {{-- ORDERS --}}
            <section id="orders" class="account-card overflow-hidden">
                <div class="p-5 md:p-6 border-b border-base-200 flex items-center justify-between gap-3">
                    <div>
                        <h2 class="text-xl font-bold">Order History</h2>
                        <p class="text-sm account-muted mt-1">Your orders placed while logged in.</p>
                    </div>
                    <a href="{{ route('track.form') }}" class="btn btn-sm btn-outline rounded-lg">Track Order</a>
                </div>

                <div class="overflow-x-auto">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Order</th>
                                <th>Date</th>
                                <th>Status</th>
                                <th>Payment</th>
                                <th class="text-right">Total</th>
                            </tr>
                        </thead>
                        <tbody>
                        @forelse($orders as $order)
                            @php
                                $statusClass = match($order->status) {
                                    'delivered' => 'bg-green-100 text-green-700',
                                    'cancelled' => 'bg-red-100 text-red-700',
                                    'shipped' => 'bg-blue-100 text-blue-700',
                                    'processing','confirmed' => 'bg-amber-100 text-amber-700',
                                    default => 'bg-gray-100 text-gray-700'
                                };
                            @endphp
                            <tr>
                                <td>
                                    <div class="font-semibold">{{ $order->order_number }}</div>
                                    <div class="text-xs account-muted">{{ $order->items->sum('quantity') }} item(s)</div>
                                </td>
                                <td class="text-sm">{{ $order->created_at->format('d M Y') }}</td>
                                <td><span class="status-pill {{ $statusClass }}">{{ $order->status }}</span></td>
                                <td class="text-sm capitalize">{{ $order->payment_status }}</td>
                                <td class="text-right font-bold">৳{{ number_format((float)$order->total, 2) }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center py-12">
                                    <div class="text-4xl mb-3">🛍️</div>
                                    <div class="font-semibold">No orders yet</div>
                                    <a href="{{ route('shop') }}" class="btn btn-sm mt-4 bg-[#B91C2C] text-white border-0">Start Shopping</a>
                                </td>
                            </tr>
                        @endforelse
                        </tbody>
                    </table>
                </div>

                @if($orders->hasPages())
                    <div class="p-5 border-t border-base-200">{{ $orders->links() }}</div>
                @endif
            </section>

            {{-- ADDRESSES --}}
            <section id="addresses" class="account-card p-5 md:p-6">
                <div class="flex flex-col md:flex-row md:items-start justify-between gap-3 mb-6">
                    <div>
                        <h2 class="text-xl font-bold">Saved Addresses</h2>
                        <p class="text-sm account-muted mt-1">Save commonly used delivery addresses.</p>
                    </div>
                </div>

                <div class="grid md:grid-cols-2 gap-4 mb-6">
                    @forelse($addresses as $address)
                        <div class="rounded-2xl border {{ $address->is_default ? 'border-[#C9A227] bg-[#fffdf6]' : 'border-base-200' }} p-4">
                            <div class="flex items-start justify-between gap-3">
                                <div>
                                    <div class="flex items-center gap-2">
                                        <h3 class="font-bold">{{ $address->label }}</h3>
                                        @if($address->is_default)
                                            <span class="badge badge-sm bg-[#C9A227] border-0 text-[#111]">Default</span>
                                        @endif
                                    </div>
                                    <p class="text-sm account-muted mt-2">{{ $address->phone }}</p>
                                    <p class="text-sm mt-2 leading-6">
                                        {{ $address->address }}, {{ $address->area }}, {{ $address->district }}
                                    </p>
                                </div>

                                <form method="POST" action="{{ route('account.address.destroy',$address) }}" onsubmit="return confirm('Remove this address?')">
                                    @csrf @method('DELETE')
                                    <button class="btn btn-xs btn-ghost text-error">Remove</button>
                                </form>
                            </div>
                        </div>
                    @empty
                        <div class="md:col-span-2 rounded-2xl border border-dashed border-base-300 p-6 text-center account-muted">
                            You have no saved addresses yet.
                        </div>
                    @endforelse
                </div>

                <div class="rounded-2xl bg-[#faf9f5] border border-base-200 p-4 md:p-5">
                    <h3 class="font-bold mb-4">Add New Address</h3>
                    <form method="POST" action="{{ route('account.address.store') }}" class="grid md:grid-cols-2 gap-4">
                        @csrf
                        <div>
                            <label class="text-xs font-semibold mb-1.5 block">Label</label>
                            <input class="input input-bordered account-input w-full" name="label" placeholder="Home / Office" required>
                        </div>
                        <div>
                            <label class="text-xs font-semibold mb-1.5 block">Phone</label>
                            <input class="input input-bordered account-input w-full" name="phone" placeholder="01XXXXXXXXX" required>
                        </div>
                        <div>
                            <label class="text-xs font-semibold mb-1.5 block">District</label>
                            <input class="input input-bordered account-input w-full" name="district" placeholder="Dhaka" required>
                        </div>
                        <div>
                            <label class="text-xs font-semibold mb-1.5 block">Area</label>
                            <input class="input input-bordered account-input w-full" name="area" placeholder="Uttara" required>
                        </div>
                        <div class="md:col-span-2">
                            <label class="text-xs font-semibold mb-1.5 block">Full Address</label>
                            <textarea class="textarea textarea-bordered account-input w-full min-h-24" name="address" placeholder="House, road, block, landmark..." required></textarea>
                        </div>
                        <label class="md:col-span-2 flex items-center gap-2 text-sm cursor-pointer">
                            <input type="checkbox" name="is_default" value="1" class="checkbox checkbox-sm checkbox-warning">
                            Make this my default delivery address
                        </label>
                        <div class="md:col-span-2">
                            <button class="btn bg-[#111] text-white border-0 rounded-xl">Save Address</button>
                        </div>
                    </form>
                </div>
            </section>

            {{-- PROFILE --}}
            <section id="profile" class="account-card p-5 md:p-6">
                <div class="mb-5">
                    <h2 class="text-xl font-bold">Profile Settings</h2>
                    <p class="text-sm account-muted mt-1">Update your customer name and email.</p>
                </div>

                <form method="POST" action="{{ route('account.profile') }}" class="grid md:grid-cols-2 gap-4">
                    @csrf @method('PUT')

                    <div>
                        <label class="text-xs font-semibold mb-1.5 block">Name</label>
                        <input class="input input-bordered account-input w-full" name="name" value="{{ old('name',$user->name) }}" required>
                    </div>

                    <div>
                        <label class="text-xs font-semibold mb-1.5 block">Email</label>
                        <input class="input input-bordered account-input w-full" type="email" name="email" value="{{ old('email',$user->email) }}" required>
                    </div>

                    <div class="md:col-span-2">
                        <button class="btn bg-[#B91C2C] hover:bg-[#9f1826] text-white border-0 rounded-xl">Update Profile</button>
                    </div>
                </form>
            </section>
        </div>
    </div>
</div>
@endsection
