@extends('layouts.admin')
@section('title','Categories')

@section('content')
<div class="mb-7">
  <h1 class="text-3xl md:text-4xl font-bold">Categories</h1>
  <p class="text-sm text-base-content/50 mt-2">Upload a featured image for each category.</p>
</div>

<div class="grid xl:grid-cols-[380px_minmax(0,1fr)] gap-6">
<section class="as-card p-5 md:p-6">
  <h2 class="font-bold text-lg mb-5">Add Category</h2>
  <form method="POST" action="{{ route('admin.categories.store') }}" enctype="multipart/form-data" class="space-y-4">
    @csrf
    <input class="input input-bordered w-full" name="name" placeholder="Category name" required>
    <input class="input input-bordered w-full" name="slug" placeholder="Slug (optional)">
    <input type="file" name="image_file" accept="image/*" class="file-input file-input-bordered w-full">
    <input class="input input-bordered w-full" type="number" min="0" name="sort_order" value="0">
    <label class="flex items-center gap-2"><input type="checkbox" name="is_active" value="1" class="checkbox checkbox-sm" checked> Active</label>
    <button class="btn w-full bg-[#B91C2C] text-white border-0">Add Category</button>
  </form>
</section>

<section class="space-y-4">
@foreach($categories as $category)
<form method="POST" action="{{ route('admin.categories.update',$category) }}" enctype="multipart/form-data" class="as-card p-4">
  @csrf @method('PUT')
  <div class="grid md:grid-cols-[110px_minmax(0,1fr)_120px] gap-4 items-start">
    <div class="aspect-square rounded-2xl overflow-hidden border bg-[#f5f4ef]">
      @if($category->image)
        <img src="{{ asset('storage/'.$category->image) }}" class="w-full h-full object-cover">
      @else
        <div class="w-full h-full grid place-items-center text-3xl text-black/20">◇</div>
      @endif
    </div>

    <div class="grid sm:grid-cols-2 gap-3">
      <input class="input input-bordered" name="name" value="{{ $category->name }}" required>
      <input class="input input-bordered" name="slug" value="{{ $category->slug }}">
      <input type="file" name="image_file" accept="image/*" class="file-input file-input-bordered sm:col-span-2 w-full">
      <input class="input input-bordered" type="number" min="0" name="sort_order" value="{{ $category->sort_order }}">
      <label class="flex items-center gap-2"><input type="checkbox" name="is_active" value="1" class="checkbox checkbox-sm" @checked($category->is_active)> Active</label>
    </div>

    <button class="btn btn-sm bg-[#111] text-white border-0">Save</button>
  </div>
</form>
@endforeach
</section>
</div>
@endsection
