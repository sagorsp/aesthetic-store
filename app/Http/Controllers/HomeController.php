<?php
namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\HeroSlide;
use App\Models\Product;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        return view('home',[
            'featured'=>Product::with(['category','images','approvedReviews'])
                ->where('is_active',true)->where('is_featured',true)
                ->latest()->take(8)->get(),
            'categories'=>Category::where('is_active',true)->orderBy('sort_order')->take(8)->get(),
            'heroSlides'=>HeroSlide::where('is_active',true)->orderBy('sort_order')->get()
        ]);
    }

    public function shop(Request $request)
    {
        $q = Product::with(['category','approvedReviews'])->where('is_active',true);

        if ($request->filled('q')) {
            $search = trim((string) $request->q);
            $q->where(function ($x) use ($search) {
                $x->where('name','like','%'.$search.'%')
                  ->orWhere('sku','like','%'.$search.'%')
                  ->orWhere('short_description','like','%'.$search.'%')
                  ->orWhereHas('category', fn($c) => $c->where('name','like','%'.$search.'%'));
            });
        }

        // Header live-search response. Reuses /shop, so no extra route is needed.
        if ($request->boolean('live')) {
            $products = $q->latest()->limit(8)->get()->map(function ($product) {
                return [
                    'id' => $product->id,
                    'name' => $product->name,
                    'sku' => $product->sku,
                    'price' => (float) $product->price,
                    'compare_price' => $product->compare_price ? (float) $product->compare_price : null,
                    'category' => optional($product->category)->name,
                    'image' => $product->image ? asset('storage/'.$product->image) : null,
                    'url' => route('product.show', $product),
                ];
            });

            return response()->json(['products' => $products]);
        }

        if ($request->filled('category')) {
            $q->whereHas('category',fn($x)=>$x->where('slug',$request->category));
        }

        match($request->get('sort')){
            'price_asc'=>$q->orderBy('price'),
            'price_desc'=>$q->orderByDesc('price'),
            'oldest'=>$q->oldest(),
            default=>$q->latest()
        };

        return view('shop',[
            'products'=>$q->paginate(16)->withQueryString(),
            'categories'=>Category::where('is_active',true)->orderBy('name')->get()
        ]);
    }

    public function product(Product $product)
    {
        abort_unless($product->is_active,404);
        $product->load(['category','images','activeVariants','approvedReviews.user']);
        return view('product',compact('product'));
    }
}
