@extends('layouts.app')
@section('title','Wishlist')
@section('content')<div class="as-container py-10"><h1 class="text-4xl font-bold mb-7">My wishlist</h1><div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-5">@forelse($products as $product)@include('partials.product-card',['product'=>$product])@empty<p>No wishlist items yet.</p>@endforelse</div><div class="mt-8">{{ $products->links() }}</div></div>@endsection
