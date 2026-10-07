<?php
namespace App\Http\Controllers;
use App\Models\Product;
class WishlistController extends Controller {
    public function index(){ return view('wishlist',['products'=>auth()->user()->wishlistProducts()->with('category')->latest('wishlists.created_at')->paginate(20)]); }
    public function toggle(Product $product){ $user=auth()->user(); $exists=$user->wishlistProducts()->where('product_id',$product->id)->exists(); $exists?$user->wishlistProducts()->detach($product->id):$user->wishlistProducts()->attach($product->id); return back()->with('success',$exists?'Removed from wishlist.':'Added to wishlist.'); }
}
