<section class="as-container py-10 md:py-14">
    <div class="flex items-end justify-between gap-4 mb-6">
        <h2 class="text-2xl md:text-3xl font-black tracking-tight">Shop by Categories</h2>
        <a href="{{ route('shop') }}" class="text-sm font-semibold hover:text-[#B91C2C]">View All →</a>
    </div>

    <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-5 gap-4">
        @foreach($categories as $category)
            <a href="{{ route('shop',['category'=>$category->slug]) }}" class="group">
                <div class="aspect-[4/3] rounded-2xl overflow-hidden border border-[#e9e6df] bg-[#f5f4ef]">
                    @if($category->image)
                        <img src="{{ asset('storage/'.$category->image) }}"
                             alt="{{ $category->name }}"
                             class="w-full h-full object-cover transition duration-300 group-hover:scale-[1.04]">
                    @else
                        <div class="w-full h-full grid place-items-center text-4xl text-black/15">◇</div>
                    @endif
                </div>
                <div class="flex items-center justify-between gap-3 mt-3">
                    <span class="font-semibold text-sm md:text-base">{{ $category->name }}</span>
                    <span class="transition group-hover:translate-x-1">→</span>
                </div>
            </a>
        @endforeach
    </div>
</section>
