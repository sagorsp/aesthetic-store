<?php
namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller; use App\Models\Review;
class ReviewController extends Controller { public function index(){return view('admin.reviews',['reviews'=>Review::with(['user','product'])->latest()->paginate(30)]);} public function approve(Review $review){$review->update(['is_approved'=>true]);return back()->with('success','Review approved.');} public function destroy(Review $review){$review->delete();return back()->with('success','Review deleted.');} }
