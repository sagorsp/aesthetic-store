<?php
namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Product;
class DashboardController extends Controller {
    public function index(){ return view('admin.dashboard',['stats'=>['products'=>Product::count(),'orders'=>Order::count(),'pending'=>Order::where('status','pending')->count(),'sales'=>Order::where(fn($q)=>$q->where('payment_status','paid')->orWhere('payment_method','cod'))->whereIn('status',['confirmed','processing','shipped','delivered'])->sum('total')],'recent'=>Order::latest()->take(8)->get()]); }
}
