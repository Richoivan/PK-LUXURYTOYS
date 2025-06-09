<?php


namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Midtrans\Config;
use Midtrans\Transaction;
use Illuminate\Http\Request;


class OrderController extends Controller
{

    public function index(Request $request)
    {
        $status = $request->get('status');  // mengambil parameter status dari url (paid,pending,canceled,expired) dari user yang sedang login

        $ordersQuery = Order::where('user_id', auth()->id()) // ambil data pesanan dari database user yang sedang login
            ->with(['orderCars.car', 'user']) //ambil relasi antara order dengan mobil yang dipesan, pakai with() agar lebih simple
            ->orderBy('created_at', 'desc'); // mengurutkan order dari yang baru ke paling lama berdasarkan waktu pembuatan order

        if ($status && in_array($status, ['paid', 'pending', 'expired', 'cancelled'])) {
            $ordersQuery->where('status', $status); //check apakah ada status tersebut jika ada akan di filter berdsarkan status tersebut
        }

        $orders = $ordersQuery->paginate(10); // atur pagination di halaman order 10

        $statusCounts = [
            'all' => Order::where('user_id', auth()->id())->count(), // hitung semua order yang dimiliki user tanpa filter statusnya
            'paid' => Order::where('status', 'paid')->where('user_id', auth()->id())->count(), // hitung order dengan status paid
            'pending' => Order::where('status', 'pending')->where('user_id', auth()->id())->count(), // hitung order dengan status pending
            'expired' => Order::where('status', 'expired')->where('user_id', auth()->id())->count(), // hitung order dengan status order yang kadaluarsa
            'cancelled' => Order::where('status', 'cancelled')->where('user_id', auth()->id())->count(), // hitung order yang dibatalkan baik oleh user atapun sistem
        ];

        return view('user.order', compact('orders', 'status', 'statusCounts')); // semua data di order,status dan status count dikirim ke user.order
    }
    
    public function orderDetail($id) // fungsi untuk nampilin detail pesanan dari id
    {
        $order = Order::with(['orderCars.car'])->findOrFail($id); // ambil data dari order, mobilnya apa, kalau ga ketemu bakal munculin eror 404
        if ($order->user_id != auth()->id()) { // check apakah order ini milik user yang sedang login
            return redirect()->route('user.order')->with('error', 'You are not authorized to view this order.'); // jika bukan maka akan balik ke halama daftar pesanan dan bakal munculin message
        }
        return view('user.order-detail', compact('order')); // kalau  itu order kita bakal masuk ke order detail.blade.php
    }
    
    public function checkStatus(Order $order) // check status
    {
        if ($order->user_id != auth()->id()) { // fungsi untuk melakukan order ini apakah milik user yang sedang login, jika tidak langung balik ke halaman daftar pesanan
            return redirect()->route('user.order')->with('error', 'You are not authorized to view this order.'); //balik ke halam order, tampilkan pesan error
        }
    
        Config::$serverKey = config('midtrans.server_key'); // setting koneksi dari server midtrans api
        Config::$isProduction = config('midtrans.is_production');
 
        try {

            $status = Transaction::status($order->order_number); // cek status transaksi berdasrkan order number lewat midtrans
            if (!$status) {
                return redirect()->route('user.order')->with('error', 'Payment status still pending.'); // kalau midtrans error / kita blm ke payment sandbox maka pending
            }
            if ($status->transaction_status == 'settlement' || $status->transaction_status == 'capture') {
                $order->status = 'paid'; // jika pembayaran berhasil maka akan midtans akan memunculkan gambar transaksi sukes

                foreach ($order->orderCars as $orderCar) {
                    if ($orderCar->car) {
                        $orderCar->car->update(['status' => 'sold']); // mobil yang pembayarnya sudah berhasi; maka statusnya akan berubah menjadi sold
                    }
                }
            } elseif ($status->transaction_status == 'pending') {
                $order->status = 'pending'; // jika pembayaranya, blm dibayar maka statusnya akan menjadi pending
            } elseif ($status->transaction_status == 'expire') {
                $order->status = 'expired'; // jika pembayaranya telat, maka statusnya akan dirubah menjadi expire
                
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