<?php
namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller; use App\Models\HeroSlide; use Illuminate\Http\Request; use Illuminate\Support\Facades\Storage;
class HeroSlideController extends Controller {
    public function index(){return view('admin.hero-slides',['slides'=>HeroSlide::orderBy('sort_order')->get()]);}
    public function store(Request $r){$d=$this->data($r); if($r->hasFile('image_file'))$d['image']=$r->file('image_file')->store('banners','public'); $d['is_active']=$r->boolean('is_active'); HeroSlide::create($d); return back()->with('success','Hero slide added.');}
    public function update(Request $r,HeroSlide $heroSlide){$d=$this->data($r);if($r->hasFile('image_file')){if($heroSlide->image)Storage::disk('public')->delete($heroSlide->image);$d['image']=$r->file('image_file')->store('banners','public');}$d['is_active']=$r->boolean('is_active');$heroSlide->update($d);return back()->with('success','Hero slide updated.');}
    public function destroy(HeroSlide $heroSlide){if($heroSlide->image)Storage::disk('public')->delete($heroSlide->image);$heroSlide->delete();return back()->with('success','Hero slide deleted.');}
    private function data(Request $r):array{return $r->validate(['eyebrow'=>'nullable|string|max:80','title'=>'required|string|max:190','subtitle'=>'nullable|string|max:500','button_text'=>'nullable|string|max:60','button_url'=>'nullable|string|max:500','sort_order'=>'nullable|integer|min:0','image_file'=>'nullable|image|max:5120']);}
}
