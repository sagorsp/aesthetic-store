<?php
namespace App\Http\Controllers;
use App\Models\Product; use App\Models\Review; use Illuminate\Http\Request;
class ReviewController extends Controller {
    public function store(Request $r, Product $product){ $d=$r->validate(['rating'=>'required|integer|min:1|max:5','comment'=>'nullable|string|max:2000']); Review::updateOrCreate(['user_id'=>auth()->id(),'product_id'=>$product->id],['rating'=>$d['rating'],'comment'=>$d['comment']??null,'is_approved'=>false]); return back()->with('success','Review submitted and waiting for approval.'); }
}
