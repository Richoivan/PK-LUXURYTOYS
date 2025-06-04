<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Cart;
use Illuminate\Http\Request;

class CartController extends Controller
{
    //
    public function index()
    {
        $carts = Cart::with(['car', 'user'])
            ->where('user_id', auth()->id())
            ->get();

            $total = $carts->sum(function($cart) {
            return $cart->car->price * $cart->quantity;
        });
        
       
        return view('user.cart', compact('carts', 'total'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'car_id' => 'required|exists:cars,id',
            'quantity' => 'required|integer|min:1|max:10'
        ]);

        $exist = Cart::where('user_id', auth()->id())
            ->where('car_id', $request->car_id)
            ->first();

        if ($exist) {
             return redirect()->back()->with('info', 'Car already in your Cart.');
        } else {
            
            Cart::create([
                'user_id' => auth()->id(),
                'car_id' => $request->car_id,
                'quantity' => $request->quantity,
            ]);
           
        }

        return redirect()->back()->with('success', 'Car added to cart successfully');
    }
    public function update(Request $request, $id)
    {
        $request->validate([
            'quantity' => 'required|integer|min:1|max:10'
        ]);

        $cart = Cart::where('user_id', auth()->id())->findOrFail($id);
        $cart->update(['quantity' => $request->quantity]);

        return redirect()->route('cart.index')->with('success', 'Cart updated successfully.');
    }
     public function destroy($id)
    {
        $cart = Cart::where('user_id', auth()->id())->findOrFail($id);
        $cart->delete();
        
        return redirect()->route('cart.index')->with('success', 'Car removed from cart.');
    }
}
