@extends('layouts.app')
@section('title','Chinese Imported Products in Bangladesh')
@section('content')
@php
  $slides = $heroSlides ?? collect();
  if($slides->isEmpty()) $slides=collect([(object)['eyebrow'=>$storeSettings['hero_eyebrow']??'TRENDING NOW','title'=>$storeSettings['hero_title']??'Discover Products You’ll Love','subtitle'=>$storeSettings['hero_subtitle']??'Shop the latest trending imported products, curated for modern lifestyles.','button_text'=>$storeSettings['hero_button_text']??'Shop Now','button_url'=>$storeSettings['hero_button_url']??'/shop','image'=>$storeSettings['hero_banner']??null]]);
@endphp
<section class="bg-[#f7f7f6] overflow-hidden">
  <div id="heroSlider" class="relative">
    @foreach($slides as $i=>$slide)
    <div class="hero-slide {{ $i?'hidden':'' }}" data-slide="{{ $i }}">
      <div class="as-container min-h-[520px] md:min-h-[610px] grid lg:grid-cols-[.88fr_1.12fr] items-center gap-8 py-10 md:py-14">
        <div class="z-10 order-2 lg:order-1">
          <p class="text-[#B91C2C] text-xs font-bold tracking-[.18em]">{{ $slide->eyebrow }}</p>
          <h1 class="text-4xl sm:text-5xl lg:text-6xl font-black leading-[1.02] mt-4 max-w-xl">{{ $slide->title }}</h1>
          <p class="mt-5 text-base md:text-lg text-black/55 max-w-lg">{{ $slide->subtitle }}</p>
          <div class="mt-7 flex gap-3"><a class="btn bg-[#B91C2C] hover:bg-[#991625] text-white border-0 px-7" href="{{ $slide->button_url ?: route('shop') }}">{{ $slide->button_text ?: 'Shop Now' }} →</a><a class="btn btn-outline px-7" href="{{ route('shop') }}">Explore Collection</a></div>
        </div>
        <div class="order-1 lg:order-2 relative min-h-[320px] md:min-h-[480px] rounded-[2rem] overflow-hidden bg-gradient-to-br from-white to-[#ffd7cf]">
          @if($slide->image)<img src="{{ asset('storage/'.$slide->image) }}" class="absolute inset-0 w-full h-full object-cover" alt="{{ $slide->title }}">@else<div class="absolute inset-0 grid place-items-center"><div class="text-8xl font-black text-black/10">AS</div></div>@endif
        </div>
      </div>
    </div>
    @endforeach
    @if($slides->count()>1)<div class="absolute bottom-5 left-1/2 -translate-x-1/2 flex gap-2 z-20">@foreach($slides as $i=>$s)<button class="hero-dot w-2.5 h-2.5 rounded-full {{ $i===0?'bg-[#B91C2C]':'bg-black/25' }}" onclick="showHero({{ $i }})" aria-label="Slide {{ $i+1 }}"></button>@endforeach</div>@endif
  </div>
</section>
<section class="border-y border-base-200"><div class="as-container py-5 grid grid-cols-2 lg:grid-cols-4 gap-5 text-sm"><div class="flex gap-3"><span>🚚</span><div><b>Fast Delivery</b><div class="text-xs text-black/45">Across Bangladesh</div></div></div><div class="flex gap-3"><span>🔒</span><div><b>Secure Payment</b><div class="text-xs text-black/45">COD & bKash</div></div></div><div class="flex gap-3"><span>↻</span><div><b>Easy Support</b><div class="text-xs text-black/45">Customer care</div></div></div><div class="flex gap-3"><span>☎</span><div><b>{{ $storeSettings['support_phone'] ?? config('store.phone') }}</b><div class="text-xs text-black/45">Local support</div></div></div></div></section>
@include('partials.featured-infinite-carousel', [
    'featured' => $featured ?? collect()
])
<section class="as-container py-10 md:py-12"><div class="flex justify-between items-end mb-5"><h2 class="text-2xl md:text-3xl font-bold">Shop by Categories</h2><a href="{{ route('shop') }}" class="text-sm">View All →</a></div><div class="flex gap-4 overflow-x-auto pb-2 hide-scrollbar md:grid md:grid-cols-3 lg:grid-cols-6">@foreach($categories->take(6) as $cat)<a href="{{ route('shop',['category'=>$cat->slug]) }}" class="min-w-[160px] md:min-w-0 group"><div class="aspect-[4/3] rounded-2xl bg-[#F3F2EF] overflow-hidden border">@if($cat->image)<img src="{{ asset('storage/'.$cat->image) }}" class="w-full h-full object-cover group-hover:scale-105 transition">@else<div class="w-full h-full grid place-items-center text-4xl">◈</div>@endif</div><div class="mt-2 flex justify-between text-sm font-semibold"><span>{{ $cat->name }}</span><span>→</span></div></a>@endforeach</div></section>
<section class="as-container py-6 md:py-8"><div class="flex justify-between items-end mb-5"><div><h2 class="text-2xl md:text-3xl font-bold">New Arrivals</h2><p class="text-sm text-black/50">Fresh imported products selected for you.</p></div><a href="{{ route('shop') }}" class="text-sm">View All →</a></div><div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-3 md:gap-5">@forelse($featured as $product)@include('partials.product-card',['product'=>$product])@empty<div class="col-span-full border border-dashed rounded-2xl p-10 text-center">Add featured products from admin.</div>@endforelse</div></section>
<section class="as-container py-10"><div class="grid md:grid-cols-2 gap-5"><div class="rounded-3xl p-8 bg-gradient-to-r from-[#B91C2C] to-[#ff7966] text-white"><p class="text-sm font-semibold">FLASH SALE</p><h3 class="text-3xl font-black mt-2">Useful finds at better prices.</h3><a class="btn bg-white text-black border-0 mt-6" href="{{ route('shop') }}">Shop Sale</a></div><div class="rounded-3xl p-8 bg-[#111] text-white"><p class="text-sm text-[#C9A227] font-semibold">NEW COLLECTION</p><h3 class="text-3xl font-black mt-2">Fresh Chinese imports every week.</h3><a class="btn btn-outline text-white mt-6" href="{{ route('shop') }}">Explore</a></div></div></section>
<script>
let heroIndex=0,heroTimer; function showHero(i){const slides=[...document.querySelectorAll('.hero-slide')],dots=[...document.querySelectorAll('.hero-dot')];if(!slides.length)return;slides.forEach((s,n)=>s.classList.toggle('hidden',n!==i));dots.forEach((d,n)=>{d.classList.toggle('bg-[#B91C2C]',n===i);d.classList.toggle('bg-black/25',n!==i)});heroIndex=i;clearInterval(heroTimer);heroTimer=setInterval(()=>showHero((heroIndex+1)%slides.length),6000)};document.addEventListener('DOMContentLoaded',()=>{if(document.querySelectorAll('.hero-slide').length>1)heroTimer=setInterval(()=>showHero((heroIndex+1)%document.querySelectorAll('.hero-slide').length),6000)});
</script>
@endsection
