<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Cart;
use App\Models\Order;
use App\Models\OrderCar;
use Midtrans\Config;
use DB;
use Illuminate\Http\Request;
use Midtrans\Snap;

class CheckoutController extends Controller
{
    public function checkout(Request $request)
    {
        $request->validate([
            'address' => 'required|string|max:500',
        ]);

        $cartItems = Cart::where('user_id', auth()->id())
            ->with(['car.manufacturer'])
            ->get();

        if ($cartItems->isEmpty()) {
            return redirect()->route('cart.index')->with('error', 'Your cart is empty.');
        }

        DB::beginTransaction();

        try {
            $subtotal = $cartItems->sum(function ($cart) {
                return $cart->car->price * $cart->quantity;
            });
            $shippingCost = 1000000;
            $grandTotal = $subtotal + $shippingCost;

            $order = Order::create([
                'user_id' => auth()->id(),
                'order_number' => 'ORD-' . date('Ymd') . '-' . str_pad((Order::count() + 1), 4, '0', STR_PAD_LEFT),
                'total_price' => $subtotal,
                'shipping_cost' => $shippingCost,
                'grand_total' => $grandTotal,
                'status' => 'pending',
                'shipping_address' => $request->address,
                'customer_name' => auth()->user()->name,
            ]);

            foreach ($cartItems as $cartItem) {
                OrderCar::create([
                    'order_id' => $order->id,
                    'car_id' => $cartItem->car_id,
                    'quantity' => $cartItem->quantity,
                    'car_name' => $cartItem->car->name,
                    'price' => $cartItem->car->price,
                    'total_price' => $cartItem->car->price * $cartItem->quantity,
                ]);
                $cartItem->car->update(['status' => 'reserved']);
            }

            Config::$serverKey = config('midtrans.server_key');
            Config::$isProduction = config('midtrans.is_production');
            Config::$isSanitized = true;
            Config::$is3ds = true;

            $itemDetails = $cartItems->map(function ($cart) {
                return [
                    'id' => $cart->car_id,
                    'price' => $cart->car->price,
                    'quantity' => $cart->quantity,
                    'name' => $cart->car->name
                ];
            })->toArray();

            $itemDetails[] = [
                'id' => 'SHIPPING',
                'price' => $shippingCost,
                'quantity' => 1,
                'name' => 'Shipping Fee'
            ];

            $params = [
                'transaction_details' => [
                    'order_id' => $order->order_number,
                    'gross_amount' => $grandTotal,
                ],
                'customer_details' => [
                    'first_name' => $request->customer_name,
                    'email' => $request->email,
                    'phone' => $request->phone,
                ],
                'item_details' => $itemDetails,
                'callbacks' => [
                    'finish' => route('user.order-detail', ['id' => $order->id]),
                  
                ]
            ];

            $snapUrl = Snap::createTransaction($params)->redirect_url;

            $order->payment_url = $snapUrl;
            $order->save();

            $cartItems->each->delete();

            DB::commit();

            return redirect($snapUrl);

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'An error occurred during checkout: ' . $e->getMessage());
        }
    }


}
