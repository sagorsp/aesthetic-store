@extends('layouts.admin')
@section('title',$product->exists?'Edit Product':'Add Product')

@section('content')
<style>
    .pf-card{background:#fff;border:1px solid #e9e8e3;border-radius:1.5rem;box-shadow:0 8px 30px rgba(17,17,17,.035)}
    .pf-title{font-size:1rem;font-weight:800;color:#171717}
    .pf-sub{font-size:.78rem;color:rgba(17,17,17,.48);margin-top:.2rem}
    .pf-label{display:block;font-size:.78rem;font-weight:700;color:#3b3b3b;margin-bottom:.45rem}
    .pf-input,.pf-select,.pf-textarea{width:100%;border:1px solid #deddd7!important;background:#fff!important;border-radius:.9rem!important;transition:.18s ease;box-shadow:none!important}
    .pf-input:focus,.pf-select:focus,.pf-textarea:focus{outline:none!important;border-color:#C9A227!important;box-shadow:0 0 0 4px rgba(201,162,39,.10)!important}
    .pf-help{font-size:.72rem;color:rgba(17,17,17,.42);margin-top:.4rem}
    .pf-badge{display:inline-flex;align-items:center;gap:.4rem;padding:.35rem .65rem;border-radius:999px;background:#f7f2df;color:#866b12;font-size:.72rem;font-weight:700}
    .pf-section-icon{height:2.35rem;width:2.35rem;border-radius:.85rem;background:#f8f6ef;display:grid;place-items:center;color:#a68112;flex:none}
    .variant-row{background:#fafaf8;border:1px solid #ebeae4;border-radius:1rem;padding:.85rem}
    .upload-box{border:1.5px dashed #d7d4ca;border-radius:1.15rem;background:#fcfbf7}
    .sticky-save{position:sticky;bottom:1rem;z-index:15}
</style>

<div class="flex flex-col lg:flex-row lg:items-end justify-between gap-4 mb-7">
    <div>
        <div class="pf-badge mb-3">✦ Product Management</div>
        <h1 class="text-3xl md:text-4xl font-bold tracking-tight">{{ $product->exists?'Edit Product':'Add New Product' }}</h1>
        <p class="text-sm text-black/45 mt-2 max-w-2xl">Add product details, pricing, inventory, variants and images in one place.</p>
    </div>
    <a class="btn btn-ghost rounded-xl" href="{{ route('admin.products.index') }}">← Back to Products</a>
</div>

<form method="post"
      action="{{ $product->exists?route('admin.products.update',$product):route('admin.products.store') }}"
      enctype="multipart/form-data"
      class="grid xl:grid-cols-[minmax(0,1fr)_360px] gap-6">
    @csrf
    @if($product->exists) @method('PUT') @endif

    <div class="space-y-6">
        {{-- BASIC INFO --}}
        <section class="pf-card p-5 md:p-7">
            <div class="flex items-start gap-3 mb-6">
                <div class="pf-section-icon">01</div>
                <div>
                    <h2 class="pf-title">Basic Information</h2>
                    <p class="pf-sub">Customer-facing name, category, SKU and product descriptions.</p>
                </div>
            </div>

            <div class="grid md:grid-cols-2 gap-5">
                <div class="md:col-span-2">
                    <label class="pf-label">Product Name <span class="text-error">*</span></label>
                    <input class="input pf-input h-12" name="name" value="{{ old('name',$product->name) }}" placeholder="e.g. Mini Portable Cooling Fan" required>
                    <p class="pf-help">Use a clear product title that customers can easily search.</p>
                </div>

                <div>
                    <label class="pf-label">Category <span class="text-error">*</span></label>
                    <select class="select pf-select h-12" name="category_id" required>
                        @foreach($categories as $c)
                            <option value="{{ $c->id }}" @selected(old('category_id',$product->category_id)==$c->id)>{{ $c->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="pf-label">SKU <span class="text-error">*</span></label>
                    <input class="input pf-input h-12" name="sku" value="{{ old('sku',$product->sku) }}" placeholder="e.g. AS-FAN-001" required>
                    <p class="pf-help">Keep SKU unique for stock and order tracking.</p>
                </div>

                <div class="md:col-span-2">
                    <label class="pf-label">Short Description</label>
                    <textarea class="textarea pf-textarea min-h-24" name="short_description" placeholder="A short summary for product cards and quick previews.">{{ old('short_description',$product->short_description) }}</textarea>
                </div>

                <div class="md:col-span-2">
                    <div class="flex items-center justify-between gap-3">
                        <label class="pf-label">Full Description</label>
                        <span class="text-[11px] text-black/35">Detailed features, benefits and specifications</span>
                    </div>
                    <textarea class="textarea pf-textarea min-h-48" name="description" placeholder="Write full product details here...">{{ old('description',$product->description) }}</textarea>
                </div>
            </div>
        </section>

        {{-- PRICE / INVENTORY --}}
        <section class="pf-card p-5 md:p-7">
            <div class="flex items-start gap-3 mb-6">
                <div class="pf-section-icon">02</div>
                <div>
                    <h2 class="pf-title">Pricing & Inventory</h2>
                    <p class="pf-sub">Set regular price, discount reference price and available stock.</p>
                </div>
            </div>

            <div class="grid md:grid-cols-3 gap-5">
                <div>
                    <label class="pf-label">Selling Price <span class="text-error">*</span></label>
                    <label class="input pf-input h-12 flex items-center gap-2">
                        <span class="text-black/40">৳</span>
                        <input class="grow" type="number" step="0.01" min="0" name="price" value="{{ old('price',$product->price) }}" placeholder="0.00" required>
                    </label>
                </div>

                <div>
                    <label class="pf-label">Compare Price</label>
                    <label class="input pf-input h-12 flex items-center gap-2">
                        <span class="text-black/40">৳</span>
                        <input class="grow" type="number" step="0.01" min="0" name="compare_price" value="{{ old('compare_price',$product->compare_price) }}" placeholder="0.00">
                    </label>
                    <p class="pf-help">Optional old price for showing discount.</p>
                </div>

                <div>
                    <label class="pf-label">Base Stock <span class="text-error">*</span></label>
                    <input class="input pf-input h-12" type="number" min="0" name="stock" value="{{ old('stock',$product->stock ?? 0) }}" required>
                    <p class="pf-help">Used when product has no variants.</p>
                </div>
            </div>
        </section>

        {{-- VARIANTS --}}
        <section class="pf-card p-5 md:p-7">
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-6">
                <div class="flex items-start gap-3">
                    <div class="pf-section-icon">03</div>
                    <div>
                        <h2 class="pf-title">Product Variants</h2>
                        <p class="pf-sub">Add color, size, option-specific price and stock.</p>
                    </div>
                </div>
                <button type="button" class="btn btn-sm rounded-xl bg-[#111] text-white border-0 hover:bg-black" onclick="addVariant()">+ Add Variant</button>
            </div>

            <input type="hidden" name="replace_variants" value="1">

            <div class="hidden md:grid grid-cols-6 gap-2 px-3 mb-2 text-[11px] font-bold uppercase tracking-wide text-black/35">
                <div>Name</div><div>SKU</div><div>Color</div><div>Size</div><div>Price</div><div>Stock</div>
            </div>

            <div id="variantRows" class="space-y-3">
                @foreach($product->variants ?? [] as $v)
                    <div class="grid md:grid-cols-6 gap-3 variant-row">
                        <input type="hidden" name="variant_id[]" value="{{ $v->id }}">
                        <input class="input pf-input input-sm" name="variant_name[]" value="{{ $v->name }}" placeholder="Variant name">
                        <input class="input pf-input input-sm" name="variant_sku[]" value="{{ $v->sku }}" placeholder="SKU">
                        <input class="input pf-input input-sm" name="variant_color[]" value="{{ $v->color }}" placeholder="Color">
                        <input class="input pf-input input-sm" name="variant_size[]" value="{{ $v->size }}" placeholder="Size">
                        <input class="input pf-input input-sm" type="number" step="0.01" name="variant_price[]" value="{{ $v->price }}" placeholder="Price">
                        <div class="flex gap-2">
                            <input class="input pf-input input-sm w-full" type="number" name="variant_stock[]" value="{{ $v->stock }}" placeholder="Stock">
                            <button type="button" onclick="this.closest('.variant-row').remove()" class="btn btn-sm btn-square btn-ghost text-error" title="Remove variant">×</button>
                        </div>
                    </div>
                @endforeach
            </div>

            <div id="variantEmpty" class="text-center py-7 text-sm text-black/40 {{ ($product->variants ?? collect())->count() ? 'hidden' : '' }}">
                No variants added yet. Use variants only if this product has options like color or size.
            </div>
        </section>

        {{-- GALLERY --}}
        <section class="pf-card p-5 md:p-7">
            <div class="flex items-start gap-3 mb-6">
                <div class="pf-section-icon">04</div>
                <div>
                    <h2 class="pf-title">Product Gallery</h2>
                    <p class="pf-sub">Upload multiple photos for customers to browse.</p>
                </div>
            </div>

            <label class="upload-box block p-6 text-center cursor-pointer hover:bg-[#faf8f0] transition">
                <div class="text-3xl mb-2">🖼️</div>
                <div class="font-semibold">Choose gallery images</div>
                <div class="text-xs text-black/40 mt-1">JPG, PNG or WEBP — multiple files supported</div>
                <input type="file" name="gallery_files[]" multiple accept="image/*" class="hidden" id="galleryInput">
            </label>

            <div id="galleryPreview" class="grid grid-cols-3 md:grid-cols-5 gap-3 mt-4"></div>

            @if($product->exists && $product->images->count())
                <div class="mt-6">
                    <div class="text-xs font-bold uppercase tracking-wide text-black/40 mb-3">Current Gallery</div>
                    <div class="grid grid-cols-3 md:grid-cols-5 gap-3">
                        @foreach($product->images as $img)
                            <label class="group cursor-pointer relative">
                                <img src="{{ asset('storage/'.$img->path) }}" class="aspect-square w-full object-cover rounded-xl border">
                                <span class="mt-2 text-xs flex items-center gap-1.5 text-black/60">
                                    <input type="checkbox" name="delete_image_ids[]" value="{{ $img->id }}" class="checkbox checkbox-xs checkbox-error">
                                    Remove
                                </span>
                            </label>
                        @endforeach
                    </div>
                </div>
            @endif
        </section>
    </div>

    {{-- RIGHT COLUMN --}}
    <aside class="space-y-6">
        <section class="pf-card p-5 md:p-6">
            <div class="flex items-center justify-between mb-4">
                <h2 class="pf-title">Main Product Image</h2>
                <span class="text-[11px] text-black/35">Cover photo</span>
            </div>

            <div class="aspect-square rounded-2xl overflow-hidden bg-[#f5f4ef] border border-[#e5e3dc] mb-4 grid place-items-center" id="mainImageBox">
                @if($product->image)
                    <img id="mainImagePreview" class="w-full h-full object-cover" src="{{ asset('storage/'.$product->image) }}">
                @else
                    <div id="mainImagePlaceholder" class="text-center text-black/35 px-6">
                        <div class="text-4xl mb-2">📦</div>
                        <div class="text-sm font-medium">No image selected</div>
                    </div>
                    <img id="mainImagePreview" class="w-full h-full object-cover hidden" alt="">
                @endif
            </div>

            <label class="btn btn-outline w-full rounded-xl cursor-pointer">
                Choose Main Image
                <input type="file" name="image_file" id="mainImageInput" class="hidden" accept="image/*">
            </label>
            <p class="pf-help text-center">Recommended: square image, at least 1000×1000px.</p>
        </section>

        <section class="pf-card p-5 md:p-6">
            <h2 class="pf-title mb-4">Product Status</h2>

            <label class="flex items-center justify-between py-3 border-b border-base-200 cursor-pointer">
                <div>
                    <div class="font-semibold text-sm">Active</div>
                    <div class="text-xs text-black/40">Visible on storefront</div>
                </div>
                <input type="checkbox" class="toggle toggle-success" name="is_active" value="1" @checked(old('is_active',$product->exists?$product->is_active:true))>
            </label>

            <label class="flex items-center justify-between py-3 cursor-pointer">
                <div>
                    <div class="font-semibold text-sm">Featured</div>
                    <div class="text-xs text-black/40">Highlight on homepage</div>
                </div>
                <input type="checkbox" class="toggle toggle-warning" name="is_featured" value="1" @checked(old('is_featured',$product->is_featured))>
            </label>
        </section>

        <section class="pf-card p-5 sticky-save">
            <div class="text-xs text-black/40 mb-3">{{ $product->exists?'Review your changes before saving.':'Ready to publish this product?' }}</div>
            <button class="btn w-full h-12 bg-[#B91C2C] hover:bg-[#9f1826] text-white border-0 rounded-xl">
                {{ $product->exists?'Save Product Changes':'Create Product' }}
            </button>
        </section>
    </aside>
</form>

<script>
function updateVariantEmpty(){
    const rows = document.querySelectorAll('#variantRows .variant-row').length;
    const empty = document.getElementById('variantEmpty');
    if(empty) empty.classList.toggle('hidden', rows > 0);
}

function addVariant(){
    document.getElementById('variantRows').insertAdjacentHTML('beforeend', `
        <div class="grid md:grid-cols-6 gap-3 variant-row">
            <input type="hidden" name="variant_id[]">
            <input class="input pf-input input-sm" name="variant_name[]" placeholder="e.g. Red / XL">
            <input class="input pf-input input-sm" name="variant_sku[]" placeholder="SKU">
            <input class="input pf-input input-sm" name="variant_color[]" placeholder="Color">
            <input class="input pf-input input-sm" name="variant_size[]" placeholder="Size">
            <input class="input pf-input input-sm" type="number" step="0.01" name="variant_price[]" placeholder="Price">
            <div class="flex gap-2">
                <input class="input pf-input input-sm w-full" type="number" name="variant_stock[]" value="0" placeholder="Stock">
                <button type="button" onclick="this.closest('.variant-row').remove();updateVariantEmpty()" class="btn btn-sm btn-square btn-ghost text-error" title="Remove variant">×</button>
            </div>
        </div>`);
    updateVariantEmpty();
}

const mainInput = document.getElementById('mainImageInput');
if(mainInput){
    mainInput.addEventListener('change', function(e){
        const file = e.target.files?.[0];
        if(!file) return;
        const img = document.getElementById('mainImagePreview');
        const placeholder = document.getElementById('mainImagePlaceholder');
        img.src = URL.createObjectURL(file);
        img.classList.remove('hidden');
        if(placeholder) placeholder.classList.add('hidden');
    });
}

const galleryInput = document.getElementById('galleryInput');
if(galleryInput){
    galleryInput.addEventListener('change', function(e){
        const preview = document.getElementById('galleryPreview');
        preview.innerHTML = '';
        [...e.target.files].forEach(file => {
            const wrap = document.createElement('div');
            wrap.className = 'aspect-square rounded-xl overflow-hidden border bg-base-100';
            const img = document.createElement('img');
            img.className = 'w-full h-full object-cover';
            img.src = URL.createObjectURL(file);
            wrap.appendChild(img);
            preview.appendChild(wrap);
        });
    });
}

updateVariantEmpty();
</script>
@endsection
