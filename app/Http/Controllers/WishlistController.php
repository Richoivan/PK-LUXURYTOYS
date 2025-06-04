<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Wishlist;
use Illuminate\Http\Request;

class WishlistController extends Controller
{
    //
  
     public function index(){
        $wishlists = Wishlist::with(['car.manufacturer', 'user'])
            ->where('user_id', auth()->id())
            ->get();
        return view('user.wishlist', compact('wishlists'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'car_id' => 'required|exists:cars,id',
        ]);

        $exist = Wishlist::where('user_id', auth()->id())
            ->where('car_id', $request->car_id)
            ->first();

        if ($exist) {
             return redirect()->back()->with('info', 'Car already in your wishlist.');
        } else {
            Wishlist::create([
                'user_id' => auth()->id(),
                'car_id' => $request->car_id,
            ]);
        }

        return redirect()->back()->with('success', 'Car added to wishlist successfully');
    }
    
    public function destroy($id)
    {
        $wishlist = Wishlist::where('user_id', auth()->id())->findOrFail($id);
        $wishlist->delete();
        
        return redirect()->route('wishlist.index')->with('success', 'Item removed from wishlist.');
    }
}
