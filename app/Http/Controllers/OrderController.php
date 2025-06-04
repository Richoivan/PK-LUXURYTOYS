<?php


namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Midtrans\Config;
use Midtrans\Transaction;

class OrderController extends Controller
{

    public function index()
    {
        $orders = Order::where('user_id', auth()->id())
            ->with(['orderCars.car'])
            ->orderBy('created_at', 'desc')
            ->paginate(10);
            
        return view('user.order', compact('orders'));
    }
    
    public function orderDetail($id)
    {
        $order = Order::with(['orderCars.car'])->findOrFail($id);
        if ($order->user_id != auth()->id()) {
            return redirect()->route('user.order')->with('error', 'You are not authorized to view this order.');
        }
        return view('user.order-detail', compact('order'));
    }
    
    public function checkStatus(Order $order)
    {
        if ($order->user_id != auth()->id()) {
            return redirect()->route('user.order')->with('error', 'You are not authorized to view this order.');
        }
    
        Config::$serverKey = config('midtrans.server_key');
        Config::$isProduction = config('midtrans.is_production');
 
        try {

            $status = Transaction::status($order->order_number);
            if (!$status) {
                return redirect()->route('user.order')->with('error', 'Payment status still pending.');
            }
            if ($status->transaction_status == 'settlement' || $status->transaction_status == 'capture') {
                $order->status = 'paid';

                foreach ($order->orderCars as $orderCar) {
                    if ($orderCar->car) {
                        $orderCar->car->update(['status' => 'sold']);
                    }
                }
            } elseif ($status->transaction_status == 'pending') {
                $order->status = 'pending';
            } elseif ($status->transaction_status == 'expire') {
                $order->status = 'expired';
                
                foreach ($order->orderCars as $orderCar) {
                    if ($orderCar->car && $orderCar->car->status == 'reserved') {
                        $orderCar->car->update(['status' => 'available']);
                    }
                }
            } elseif ($status->transaction_status == 'cancel') {
                $order->status = 'cancelled';
                
             
                foreach ($order->orderCars as $orderCar) {
                    if ($orderCar->car && $orderCar->car->status == 'reserved') {
                        $orderCar->car->update(['status' => 'available']);
                    }
                }
            } else {
                $order->status = $status->transaction_status;
            }
 
            $order->save();
 
            return redirect()->back()->with('success', 'Payment status updated successfully.');
 
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Failed to check payment status: ' . $e->getMessage());
        }
    }
    public function cancelOrder(Order $order)
    {
        Config::$serverKey = config('midtrans.server_key');
        Config::$isProduction = config('midtrans.is_production');
        try {
        if ($order->user_id != auth()->id()) {
            return redirect()->route('user.order')->with('error', 'You are not authorized to cancel this order.');
        }

        if ($order->status == 'paid') {
            return redirect()->route('user.order')->with('error', 'Cannot cancel a paid order.');
        }
        Transaction::cancel($order->order_number);

        $order->status = 'cancelled';
        $order->save();

        foreach ($order->orderCars as $orderCar) {
            if ($orderCar->car && $orderCar->car->status == 'reserved') {
                $orderCar->car->update(['status' => 'available']);
            }
        }

        return redirect()->route('user.order')->with('success', 'Order cancelled successfully.');
    }catch (\Exception $e) {
        return redirect()->route('user.order')->with('error', 'Failed to cancel order: ' . $e->getMessage());
    }
    }
}