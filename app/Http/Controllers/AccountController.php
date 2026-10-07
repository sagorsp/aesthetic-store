<?php

namespace App\Http\Controllers;

use App\Models\Address;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class AccountController extends Controller
{
    public function dashboard()
    {
        $user = auth()->user();

        $orders = $user->orders()
            ->with('items')
            ->latest()
            ->paginate(8, ['*'], 'orders_page');

        $recentOrders = $user->orders()
            ->latest()
            ->take(5)
            ->get();

        $wishlistCount = $user->wishlistProducts()->count();

        $addresses = Address::where('user_id', $user->id)
            ->orderByDesc('is_default')
            ->latest()
            ->get();

        return view('account.index', compact(
            'user',
            'orders',
            'recentOrders',
            'wishlistCount',
            'addresses'
        ));
    }

    public function profile(Request $request)
    {
        $user = $request->user();

        $data = $request->validate([
            'name' => ['required', 'string', 'max:120'],
            'email' => [
                'required',
                'email',
                'max:190',
                Rule::unique('users', 'email')->ignore($user->id),
            ],
        ]);

        $user->update($data);

        return back()->with('success', 'Profile updated successfully.');
    }

    public function storeAddress(Request $request)
    {
        $user = $request->user();

        $data = $request->validate([
            'label' => ['required', 'string', 'max:50'],
            'phone' => ['required', 'string', 'max:30'],
            'district' => ['required', 'string', 'max:100'],
            'area' => ['required', 'string', 'max:150'],
            'address' => ['required', 'string', 'max:500'],
            'is_default' => ['nullable', 'boolean'],
        ]);

        $data['user_id'] = $user->id;
        $data['is_default'] = $request->boolean('is_default');

        if ($data['is_default']) {
            Address::where('user_id', $user->id)->update(['is_default' => false]);
        }

        if (!Address::where('user_id', $user->id)->exists()) {
            $data['is_default'] = true;
        }

        Address::create($data);

        return back()->with('success', 'Address saved successfully.');
    }

    public function destroyAddress(Request $request, Address $address)
    {
        abort_unless($address->user_id === $request->user()->id, 403);

        $wasDefault = $address->is_default;
        $address->delete();

        if ($wasDefault) {
            $next = Address::where('user_id', $request->user()->id)->latest()->first();
            if ($next) {
                $next->update(['is_default' => true]);
            }
        }

        return back()->with('success', 'Address removed.');
    }
}
