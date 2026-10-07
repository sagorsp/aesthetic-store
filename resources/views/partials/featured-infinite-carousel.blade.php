@php
$carouselProducts = isset($featured) && $featured instanceof \Illuminate\Support\Collection ? $featured : collect($featured ?? []);
if ($carouselProducts->isEmpty()) {
    $carouselProducts = \App\Models\Product::with('category')->where('is_active',true)->where('is_featured',true)->latest()->take(12)->get();
}
$loopProducts = $carouselProducts->concat($carouselProducts)->values();
@endphp
@if($carouselProducts->count())
<section class="as-container pt-4 md:pt-8 pb-5 md:pb-8">
  <div class="flex items-end justify-between gap-4 mb-5">
    <div><p class="text-xs uppercase tracking-[.2em] font-bold text-[#B91C2C] mb-2">Trending now</p><h2 class="text-2xl md:text-3xl font-black">Featured Picks</h2></div>
    <a href="{{ route('shop') }}" class="text-sm font-semibold hover:text-[#B91C2C]">View All →</a>
  </div>
  <div class="as-infinite-wrap relative overflow-hidden">
    <div class="as-infinite-track flex gap-4 w-max">
      @foreach($loopProducts as $product)
      <a href="{{ route('product.show',$product) }}" class="as-carousel-card group block w-[210px] sm:w-[230px] md:w-[250px] shrink-0 rounded-[1.4rem] border border-[#ebe8df] bg-white overflow-hidden">
        <div class="aspect-[4/3] overflow-hidden bg-[#f6f4ef]">
          @if($product->image)<img src="{{ asset('storage/'.$product->image) }}" class="w-full h-full object-cover transition duration-300 group-hover:scale-[1.045]" alt="{{ $product->name }}">@else<div class="w-full h-full grid place-items-center text-4xl text-black/10 font-black">AS</div>@endif
        </div>
        <div class="p-4"><div class="text-[11px] uppercase tracking-[.12em] text-black/35 mb-1">{{ $product->category?->name ?? 'Aesthetic Store' }}</div><h3 class="font-bold text-sm md:text-base leading-5 line-clamp-2 min-h-[40px]">{{ $product->name }}</h3><div class="flex items-end gap-2 mt-3"><span class="font-black text-[#B91C2C]">{{ config('store.symbol') }}{{ number_format((float)$product->price) }}</span>@if($product->compare_price && $product->compare_price > $product->price)<span class="text-xs text-black/30 line-through">{{ config('store.symbol') }}{{ number_format((float)$product->compare_price) }}</span>@endif</div></div>
      </a>
      @endforeach
    </div>
  </div>
</section>
<style>
.as-infinite-track{animation:asInfiniteScroll 28s linear infinite;will-change:transform}.as-infinite-wrap:hover .as-infinite-track{animation-play-state:paused}.as-carousel-card{box-shadow:0 10px 28px rgba(17,17,17,.035);transition:.2s}.as-carousel-card:hover{transform:translateY(-3px);box-shadow:0 16px 34px rgba(17,17,17,.07)}@keyframes asInfiniteScroll{from{transform:translateX(0)}to{transform:translateX(calc(-50% - .5rem))}}@media(max-width:767px){.as-infinite-track{animation-duration:22s}}@media(prefers-reduced-motion:reduce){.as-infinite-track{animation:none}}
</style>
@endif
