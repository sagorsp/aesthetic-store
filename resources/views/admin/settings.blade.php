@extends('layouts.admin')
@section('title','Store Settings')

@section('content')
<style>
  .settings-wrap{max-width:1180px;margin:0 auto}
  .settings-card{
    background:#fff;
    border:1px solid #ece9e2;
    border-radius:22px;
    box-shadow:0 12px 34px rgba(17,17,17,.035);
  }
  .settings-head{
    display:flex;
    align-items:flex-start;
    gap:14px;
    margin-bottom:20px;
  }
  .settings-icon{
    width:42px;height:42px;border-radius:14px;
    display:grid;place-items:center;
    background:linear-gradient(135deg,#fff0e9,#fff0f5);
    font-size:18px;
    flex:0 0 auto;
  }
  .field-label{display:block;font-size:.78rem;font-weight:800;margin-bottom:7px;color:#272727}
  .field-help{font-size:.72rem;color:rgba(17,17,17,.42);margin-top:6px;line-height:1.5}
  .settings-input{
    width:100%;
    border:1px solid #dedbd3!important;
    border-radius:12px!important;
    background:#fff!important;
  }
  .settings-input:focus{
    outline:none!important;
    border-color:#ff4d61!important;
    box-shadow:0 0 0 4px rgba(255,77,97,.08)!important;
  }
  .preview-box{
    border:1px dashed #d8d4cb;
    background:#faf9f6;
    border-radius:16px;
    overflow:hidden;
  }
  .settings-save{
    background:linear-gradient(135deg,#ff5b2f 0%,#ff4d61 52%,#ff3f86 100%);
    color:#fff;
    border:0;
    border-radius:14px;
    min-height:48px;
    padding:0 24px;
    font-weight:900;
    box-shadow:0 10px 24px rgba(255,77,97,.18);
  }
  .settings-save:hover{filter:brightness(.98);transform:translateY(-1px)}
  .section-divider{height:1px;background:#efede7;margin:22px 0}
</style>

<div class="settings-wrap pb-10">
  <div class="flex flex-col lg:flex-row lg:items-end justify-between gap-4 mb-7">
    <div>
      <p class="text-xs uppercase tracking-[.18em] font-bold text-[#ff4d61] mb-2">Aesthetic Store</p>
      <h1 class="text-3xl md:text-4xl font-black tracking-tight">Store Settings</h1>
      <p class="text-sm text-base-content/50 mt-2">
        Manage your branding, support information, social links and homepage hero content.
      </p>
    </div>

    <div class="text-xs text-base-content/45 rounded-xl bg-base-100 border px-4 py-3">
      Changes apply across the storefront
    </div>
  </div>

  @if(session('success'))
    <div class="alert alert-success mb-5 rounded-2xl">
      <span>{{ session('success') }}</span>
    </div>
  @endif

  @if($errors->any())
    <div class="alert alert-error mb-5 rounded-2xl">
      <div>
        <div class="font-bold">Please check the form</div>
        <ul class="text-sm mt-1 list-disc pl-5">
          @foreach($errors->all() as $error)
            <li>{{ $error }}</li>
          @endforeach
        </ul>
      </div>
    </div>
  @endif

  <form method="POST"
        action="{{ route('admin.settings.update') }}"
        enctype="multipart/form-data"
        class="grid lg:grid-cols-[minmax(0,1fr)_360px] gap-6">
    @csrf
    @method('PUT')

    <div class="space-y-6">

      {{-- BRAND --}}
      <section class="settings-card p-5 md:p-6">
        <div class="settings-head">
          <div class="settings-icon">✦</div>
          <div>
            <h2 class="text-xl font-black">Brand & Store Identity</h2>
            <p class="text-sm text-base-content/45 mt-1">Upload your logo and keep the storefront identity consistent.</p>
          </div>
        </div>

        <div class="grid md:grid-cols-[180px_minmax(0,1fr)] gap-5 items-start">
          <div>
            <div class="preview-box aspect-[4/3] grid place-items-center p-4">
              @if(!empty($storeSettings['store_logo']))
                <img src="{{ asset('storage/'.$storeSettings['store_logo']) }}"
                     alt="Store logo"
                     class="max-h-full max-w-full object-contain">
              @else
                <img src="{{ asset('images/aesthetic-store-logo.png') }}"
                     alt="Store logo"
                     class="max-h-full max-w-full object-contain">
              @endif
            </div>
            <div class="field-help">Current storefront logo</div>
          </div>

          <div>
            <label class="field-label">Store Logo</label>
            <input type="file"
                   name="store_logo"
                   accept="image/*"
                   class="file-input file-input-bordered w-full">
            <p class="field-help">Recommended: transparent PNG/WebP, around 600×250px.</p>
          </div>
        </div>
      </section>

      {{-- SUPPORT --}}
      <section class="settings-card p-5 md:p-6">
        <div class="settings-head">
          <div class="settings-icon">☎</div>
          <div>
            <h2 class="text-xl font-black">Support Information</h2>
            <p class="text-sm text-base-content/45 mt-1">These details appear in the footer and customer support areas.</p>
          </div>
        </div>

        <div class="grid md:grid-cols-2 gap-4">
          <div>
            <label class="field-label">Support Phone</label>
            <input class="input settings-input"
                   name="support_phone"
                   value="{{ old('support_phone',$storeSettings['support_phone'] ?? '') }}"
                   placeholder="01XXXXXXXXX">
          </div>

          <div>
            <label class="field-label">Support Email</label>
            <input type="email"
                   class="input settings-input"
                   name="support_email"
                   value="{{ old('support_email',$storeSettings['support_email'] ?? '') }}"
                   placeholder="support@example.com">
          </div>

          <div class="md:col-span-2">
            <label class="field-label">Store Address</label>
            <textarea class="textarea settings-input min-h-24"
                      name="support_address"
                      placeholder="Full store / office address">{{ old('support_address',$storeSettings['support_address'] ?? '') }}</textarea>
          </div>
        </div>
      </section>

      {{-- SOCIAL --}}
      <section class="settings-card p-5 md:p-6">
        <div class="settings-head">
          <div class="settings-icon">◎</div>
          <div>
            <h2 class="text-xl font-black">Social Media</h2>
            <p class="text-sm text-base-content/45 mt-1">Add your profile links. Empty fields stay hidden on the storefront.</p>
          </div>
        </div>

        <div class="grid md:grid-cols-2 gap-4">
          <div>
            <label class="field-label">Facebook URL</label>
            <input type="url"
                   class="input settings-input"
                   name="facebook_url"
                   value="{{ old('facebook_url',$storeSettings['facebook_url'] ?? '') }}"
                   placeholder="https://facebook.com/...">
          </div>

          <div>
            <label class="field-label">Instagram URL</label>
            <input type="url"
                   class="input settings-input"
                   name="instagram_url"
                   value="{{ old('instagram_url',$storeSettings['instagram_url'] ?? '') }}"
                   placeholder="https://instagram.com/...">
          </div>

          <div>
            <label class="field-label">TikTok URL</label>
            <input type="url"
                   class="input settings-input"
                   name="tiktok_url"
                   value="{{ old('tiktok_url',$storeSettings['tiktok_url'] ?? '') }}"
                   placeholder="https://tiktok.com/@...">
          </div>

          <div>
            <label class="field-label">YouTube URL</label>
            <input type="url"
                   class="input settings-input"
                   name="youtube_url"
                   value="{{ old('youtube_url',$storeSettings['youtube_url'] ?? '') }}"
                   placeholder="https://youtube.com/@...">
          </div>
        </div>
      </section>

      {{-- HERO --}}
      <section class="settings-card p-5 md:p-6">
        <div class="settings-head">
          <div class="settings-icon">▣</div>
          <div>
            <h2 class="text-xl font-black">Homepage Hero</h2>
            <p class="text-sm text-base-content/45 mt-1">Control the main promotional content shown on the homepage.</p>
          </div>
        </div>

        <div class="grid md:grid-cols-2 gap-4">
          <div>
            <label class="field-label">Eyebrow Text</label>
            <input class="input settings-input"
                   name="hero_eyebrow"
                   value="{{ old('hero_eyebrow',$storeSettings['hero_eyebrow'] ?? '') }}"
                   placeholder="New Collection">
          </div>

          <div>
            <label class="field-label">Button Text</label>
            <input class="input settings-input"
                   name="hero_button"
                   value="{{ old('hero_button',$storeSettings['hero_button'] ?? '') }}"
                   placeholder="Shop Now">
          </div>

          <div class="md:col-span-2">
            <label class="field-label">Hero Title</label>
            <input class="input settings-input"
                   name="hero_title"
                   value="{{ old('hero_title',$storeSettings['hero_title'] ?? '') }}"
                   placeholder="Discover aesthetic everyday essentials">
          </div>

          <div class="md:col-span-2">
            <label class="field-label">Hero Subtitle</label>
            <textarea class="textarea settings-input min-h-24"
                      name="hero_subtitle"
                      placeholder="A short supporting line for the hero section">{{ old('hero_subtitle',$storeSettings['hero_subtitle'] ?? '') }}</textarea>
          </div>

          <div class="md:col-span-2">
            <label class="field-label">Hero Button URL</label>
            <input class="input settings-input"
                   name="hero_url"
                   value="{{ old('hero_url',$storeSettings['hero_url'] ?? '') }}"
                   placeholder="/shop">
          </div>

          <div class="md:col-span-2">
            <label class="field-label">Hero Image</label>
            <input type="file"
                   name="hero_image"
                   accept="image/*"
                   class="file-input file-input-bordered w-full">
            <p class="field-help">Recommended: 1600×700 or similar wide ratio, WebP/JPG.</p>
          </div>
        </div>
      </section>
    </div>

    {{-- RIGHT SIDEBAR --}}
    <aside class="space-y-5 lg:sticky lg:top-6 self-start">
      <section class="settings-card p-5">
        <div class="settings-head mb-4">
          <div class="settings-icon">◈</div>
          <div>
            <h3 class="font-black">Hero Preview</h3>
            <p class="text-xs text-base-content/45 mt-1">Current hero visual</p>
          </div>
        </div>

        <div class="preview-box aspect-[16/10]">
          @if(!empty($storeSettings['hero_image']))
            <img src="{{ asset('storage/'.$storeSettings['hero_image']) }}"
                 class="w-full h-full object-cover"
                 alt="Hero image">
          @else
            <div class="w-full h-full grid place-items-center text-base-content/25 text-sm">
              No hero image uploaded
            </div>
          @endif
        </div>

        <div class="mt-4">
          <div class="text-xs uppercase tracking-[.15em] text-[#ff4d61] font-bold">
            {{ $storeSettings['hero_eyebrow'] ?? 'Hero Eyebrow' }}
          </div>
          <div class="text-xl font-black mt-1 leading-tight">
            {{ $storeSettings['hero_title'] ?? 'Homepage hero title' }}
          </div>
          <div class="text-sm text-base-content/45 mt-2 line-clamp-3">
            {{ $storeSettings['hero_subtitle'] ?? 'Hero subtitle preview will appear here.' }}
          </div>
        </div>
      </section>

      <section class="settings-card p-5">
        <h3 class="font-black mb-3">Quick Tips</h3>
        <div class="space-y-3 text-sm text-base-content/55">
          <div class="flex gap-2"><span>•</span><span>Use transparent PNG/WebP for the logo.</span></div>
          <div class="flex gap-2"><span>•</span><span>Keep hero titles short for better mobile layout.</span></div>
          <div class="flex gap-2"><span>•</span><span>Only add social links you actively use.</span></div>
          <div class="flex gap-2"><span>•</span><span>Compress images before upload for faster loading.</span></div>
        </div>
      </section>

      <button type="submit" class="settings-save w-full">
        Save Store Settings
      </button>
    </aside>
  </form>
</div>
@endsection
