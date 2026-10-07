@extends('layouts.app')
@section('title',$product->name)

@section('content')
<style>
  .pd-card{background:#fff;border:1px solid #eceae4;border-radius:1.5rem}
  .variant-card{border:1.5px solid #e8e6df;border-radius:1rem;padding:.85rem 1rem;cursor:pointer;transition:.18s ease;background:#fff}
  .variant-card:hover{border-color:#C9A227;background:#fffdf7}
  .variant-card.is-selected{border-color:#B91C2C;background:#fff8f8;box-shadow:0 0 0 3px rgba(185,28,44,.08)}
  .variant-card.is-disabled{opacity:.45;cursor:not-allowed;background:#f7f7f5}
  .thumb-btn{border:1px solid #e8e6df;border-radius:.85rem;overflow:hidden;transition:.18s ease;background:#fff}
  .thumb-btn.is-active{border-color:#B91C2C;box-shadow:0 0 0 2px rgba(185,28,44,.12)}
  .qty-btn{width:42px;height:42px;border:1px solid #e4e2dc;background:#faf9f6;font-weight:700}
  .qty-input{width:58px;height:42px;text-align:center;border-top:1px solid #e4e2dc;border-bottom:1px solid #e4e2dc;outline:none}
</style>

<div class="as-container py-6 md:py-10">
  <div class="grid lg:grid-cols-[minmax(0,1.05fr)_minmax(0,.95fr)] gap-8 lg:gap-12 items-start">

    {{-- GALLERY --}}
    <section>
      @php
        $gallery = collect([$product->image])->filter()->merge($product->images->pluck('path'))->unique()->values();
      @endphp

      <div class="lg:sticky lg:top-28">
        <div class="relative aspect-square rounded-[1.75rem] bg-[#f7f4ef] overflow-hidden border border-[#ece8df]">
          @if($gallery->first())
            <img id="mainProductImage"
                 src="{{ asset('storage/'.$gallery->first()) }}"
                 class="w-full h-full object-cover"
                 alt="{{ $product->name }}">
          @else
            <div class="w-full h-full grid place-items-center text-7xl text-black/10 font-bold">AS</div>
          @endif

          @if($product->compare_price && $product->compare_price > $product->price)
            @php
              $discount = round((($product->compare_price-$product->price)/$product->compare_price)*100);
            @endphp
            <div class="absolute left-4 top-4 badge border-0 bg-[#B91C2C] text-white font-bold px-3 py-3">-{{ $discount }}%</div>
          @endif
        </div>

        @if($gallery->count()>1)
          <div class="grid grid-cols-5 gap-2.5 mt-3">
            @foreach($gallery as $index => $img)
              <button type="button"
                      class="thumb-btn aspect-square {{ $index===0 ? 'is-active' : '' }}"
                      data-thumb
                      data-src="{{ asset('storage/'.$img) }}">
                <img src="{{ asset('storage/'.$img) }}" class="w-full h-full object-cover" alt="">
              </button>
            @endforeach
          </div>
        @endif
      </div>
    </section>

    {{-- PRODUCT INFO --}}
    <section>
      <div class="flex flex-wrap items-center gap-2 text-xs text-black/45 mb-3">
        <span>{{ $product->category?->name }}</span>
        <span>•</span>
        <span>SKU: {{ $product->sku }}</span>
      </div>

      <h1 class="text-3xl md:text-5xl font-black leading-[1.08] tracking-tight">{{ $product->name }}</h1>

      <div class="flex flex-wrap items-center gap-3 mt-4">
        <div class="flex items-center gap-1 text-sm">
          <span class="text-[#C9A227]">{{ str_repeat('★', max(1,(int)round($product->approvedReviews->avg('rating') ?: 0))) }}</span>
          <span class="text-black/45">{{ number_format($product->approvedReviews->avg('rating') ?: 0,1) }} ({{ $product->approvedReviews->count() }})</span>
        </div>

        <span id="stockBadge" class="badge badge-outline text-xs">
          {{ $product->activeVariants->count() ? 'Select an option' : ($product->stock>0 ? $product->stock.' in stock' : 'Out of stock') }}
        </span>
      </div>

      <div class="flex items-end gap-3 mt-5">
        <span id="dynamicPrice" class="text-3xl md:text-4xl font-black text-[#B91C2C]">
          {{ config('store.symbol') }}{{ number_format($product->price) }}
        </span>
        @if($product->compare_price)
          <span class="line-through text-black/35 mb-1">{{ config('store.symbol') }}{{ number_format($product->compare_price) }}</span>
        @endif
      </div>

      @if($product->short_description)
        <p class="mt-5 text-black/60 leading-7">{{ $product->short_description }}</p>
      @endif

      <form action="{{ route('cart.add',$product) }}" method="post" class="mt-7" id="addToCartForm">
        @csrf

        @if($product->activeVariants->count())
          <div class="mb-6">
            <div class="flex items-center justify-between gap-3 mb-3">
              <div>
                <div class="font-bold">Choose an option</div>
                <div class="text-xs text-black/40 mt-1">Select the exact color/size you want.</div>
              </div>
              <span id="selectedVariantText" class="text-xs font-semibold text-[#B91C2C]">Not selected</span>
            </div>

            <input type="hidden" name="variant_id" id="variantId" required>

            <div class="grid sm:grid-cols-2 gap-2.5">
              @foreach($product->activeVariants as $v)
                @php
                  $variantPrice = (float)($v->price ?? $product->price);
                  $optionText = collect([$v->color,$v->size])->filter()->join(' · ');
                @endphp
                <button type="button"
                        class="variant-card text-left {{ $v->stock<1 ? 'is-disabled' : '' }}"
                        data-variant
                        data-id="{{ $v->id }}"
                        data-name="{{ $v->name }}"
                        data-price="{{ $variantPrice }}"
                        data-stock="{{ $v->stock }}"
                        @disabled($v->stock<1)>
                  <div class="flex items-start justify-between gap-3">
                    <div class="min-w-0">
                      <div class="font-bold text-sm truncate">{{ $v->name }}</div>
                      @if($optionText)
                        <div class="text-xs text-black/45 mt-1">{{ $optionText }}</div>
                      @endif
                      <div class="text-[11px] text-black/35 mt-1">SKU: {{ $v->sku }}</div>
                    </div>
                    <div class="text-right shrink-0">
                      <div class="font-black text-[#B91C2C]">{{ config('store.symbol') }}{{ number_format($variantPrice) }}</div>
                      <div class="text-[11px] mt-1 {{ $v->stock<1 ? 'text-error' : 'text-black/40' }}">
                        {{ $v->stock<1 ? 'Out of stock' : $v->stock.' left' }}
                      </div>
                    </div>
                  </div>
                </button>
              @endforeach
            </div>
          </div>
        @endif

        <div class="pd-card p-4 md:p-5">
          <div class="flex flex-col sm:flex-row sm:items-center gap-4">
            <div>
              <div class="text-xs font-semibold text-black/50 mb-2">Quantity</div>
              <div class="inline-flex overflow-hidden rounded-xl">
                <button type="button" class="qty-btn rounded-l-xl" id="qtyMinus">−</button>
                <input type="number"
                       name="quantity"
                       id="quantityInput"
                       value="1"
                       min="1"
                       max="{{ $product->activeVariants->count() ? 1 : max(1,$product->stock) }}"
                       class="qty-input"
                       readonly>
                <button type="button" class="qty-btn rounded-r-xl" id="qtyPlus">+</button>
              </div>
            </div>

            <button id="addCartButton"
                    class="btn flex-1 h-12 bg-[#B91C2C] hover:bg-[#9f1826] text-white border-0 rounded-xl"
                    @disabled($product->activeVariants->count() || (!$product->activeVariants->count() && $product->stock<1))>
              <span>🛒</span>
              <span id="addCartText">
                @if($product->activeVariants->count()) Select option first
                @elseif($product->stock<1) Out of stock
                @else Add to cart
                @endif
              </span>
            </button>
          </div>

          <div class="grid grid-cols-3 gap-2 mt-4 text-center text-[11px] text-black/50">
            <div class="rounded-xl bg-[#faf9f5] p-3">🚚<br><span class="mt-1 inline-block">Fast delivery</span></div>
            <div class="rounded-xl bg-[#faf9f5] p-3">💵<br><span class="mt-1 inline-block">COD available</span></div>
            <div class="rounded-xl bg-[#faf9f5] p-3">✓<br><span class="mt-1 inline-block">Imported item</span></div>
          </div>
        </div>
      </form>

      @auth
        <form method="post" action="{{ route('wishlist.toggle',$product) }}" class="mt-3">
          @csrf
          <button class="btn btn-outline w-full rounded-xl">♡ Add / remove wishlist</button>
        </form>
      @else
        <a href="{{ route('login') }}" class="btn btn-ghost w-full mt-3 rounded-xl">Login to save to wishlist</a>
      @endauth

      @if($product->description)
        <div class="divider my-8"></div>
        <div>
          <h2 class="text-xl font-bold mb-3">Product details</h2>
          <div class="prose max-w-none text-black/65 leading-7">{!! nl2br(e($product->description)) !!}</div>
        </div>
      @endif
    </section>
  </div>

  {{-- REVIEWS --}}
  <section class="mt-14 max-w-4xl">
    <div class="flex items-end justify-between gap-3 mb-5">
      <div>
        <h2 class="text-2xl font-bold">Customer reviews</h2>
        <p class="text-sm text-black/45 mt-1">{{ $product->approvedReviews->count() }} approved review(s)</p>
      </div>
    </div>

    <div class="space-y-3">
      @forelse($product->approvedReviews as $review)
        <div class="pd-card p-4 md:p-5">
          <div class="font-semibold">{{ $review->user->name }}</div>
          <div class="text-[#C9A227] text-sm mt-1">{{ str_repeat('★',$review->rating) }}</div>
          <p class="text-black/60 mt-2">{{ $review->comment }}</p>
        </div>
      @empty
        <div class="pd-card p-6 text-center text-black/45">No reviews yet.</div>
      @endforelse
    </div>

    @auth
      <form method="post" action="{{ route('reviews.store',$product) }}" class="mt-6 pd-card p-5">
        @csrf
        <h3 class="font-bold mb-4">Write a review</h3>
        <div class="grid gap-3">
          <select name="rating" class="select select-bordered rounded-xl" required>
            <option value="">Select rating</option>
            @for($i=5;$i>=1;$i--)<option value="{{ $i }}">{{ $i }} star</option>@endfor
          </select>
          <textarea name="comment" class="textarea textarea-bordered rounded-xl min-h-28" placeholder="Share your experience"></textarea>
          <button class="btn bg-[#111] text-white border-0 rounded-xl">Submit review</button>
        </div>
      </form>
    @else
      <p class="mt-5"><a class="link text-[#B91C2C]" href="{{ route('login') }}">Login</a> to write a review.</p>
    @endauth
  </section>
</div>

<script>
document.querySelectorAll('[data-thumb]').forEach(btn=>{
  btn.addEventListener('click',()=>{
    document.getElementById('mainProductImage').src=btn.dataset.src;
    document.querySelectorAll('[data-thumb]').forEach(x=>x.classList.remove('is-active'));
    btn.classList.add('is-active');
  });
});

const variantButtons=[...document.querySelectorAll('[data-variant]')];
const variantInput=document.getElementById('variantId');
const dynamicPrice=document.getElementById('dynamicPrice');
const stockBadge=document.getElementById('stockBadge');
const selectedVariantText=document.getElementById('selectedVariantText');
const qty=document.getElementById('quantityInput');
const addBtn=document.getElementById('addCartButton');
const addText=document.getElementById('addCartText');

variantButtons.forEach(btn=>{
  btn.addEventListener('click',()=>{
    if(btn.disabled) return;
    variantButtons.forEach(x=>x.classList.remove('is-selected'));
    btn.classList.add('is-selected');

    variantInput.value=btn.dataset.id;
    dynamicPrice.textContent='{{ config('store.symbol') }}'+Number(btn.dataset.price).toLocaleString('en-BD');
    stockBadge.textContent=btn.dataset.stock+' in stock';
    selectedVariantText.textContent=btn.dataset.name;
    qty.value=1;
    qty.max=btn.dataset.stock;
    addBtn.disabled=false;
    addText.textContent='Add to cart';
  });
});

document.getElementById('qtyMinus')?.addEventListener('click',()=>{
  qty.value=Math.max(1,Number(qty.value)-1);
});
document.getElementById('qtyPlus')?.addEventListener('click',()=>{
  qty.value=Math.min(Number(qty.max||999),Number(qty.value)+1);
});
</script>
@endsection
