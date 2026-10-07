<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class CategoryController extends Controller
{
    public function index() {
        return view('admin.categories', [
            'categories'=>Category::orderBy('sort_order')->orderBy('name')->get()
        ]);
    }

    public function store(Request $request) {
        $data=$request->validate([
            'name'=>['required','string','max:120'],
            'slug'=>['nullable','string','max:150','unique:categories,slug'],
            'image_file'=>['nullable','image','max:3072'],
            'sort_order'=>['nullable','integer','min:0'],
            'is_active'=>['nullable','boolean'],
        ]);

        $data['slug']=!empty($data['slug']) ? Str::slug($data['slug']) : Str::slug($data['name']);
        $data['sort_order']=$data['sort_order'] ?? 0;
        $data['is_active']=$request->boolean('is_active');

        if($request->hasFile('image_file')){
            $data['image']=$request->file('image_file')->store('categories','public');
        }
        unset($data['image_file']);

        Category::create($data);
        return back()->with('success','Category created.');
    }

    public function update(Request $request, Category $category) {
        $data=$request->validate([
            'name'=>['required','string','max:120'],
            'slug'=>['nullable','string','max:150','unique:categories,slug,'.$category->id],
            'image_file'=>['nullable','image','max:3072'],
            'sort_order'=>['nullable','integer','min:0'],
            'is_active'=>['nullable','boolean'],
        ]);

        $data['slug']=!empty($data['slug']) ? Str::slug($data['slug']) : $category->slug;
        $data['sort_order']=$data['sort_order'] ?? 0;
        $data['is_active']=$request->boolean('is_active');

        if($request->hasFile('image_file')){
            if($category->image) Storage::disk('public')->delete($category->image);
            $data['image']=$request->file('image_file')->store('categories','public');
        }
        unset($data['image_file']);

        $category->update($data);
        return back()->with('success','Category updated.');
    }

    public function destroy(Category $category) {
        if($category->image) Storage::disk('public')->delete($category->image);
        $category->delete();
        return back()->with('success','Category deleted.');
    }
}
