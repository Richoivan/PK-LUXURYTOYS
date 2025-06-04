<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Car;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Midtrans\Config;
use Midtrans\Transaction;


class AdminController extends Controller
{

    public function showLoginForm()
    {
        return view('admin.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->only('email', 'password');
        $credentials['role'] = 'admin';

        if (Auth::attempt($credentials, $request->boolean('remember'))) {
            $request->session()->regenerate();
            return redirect()->intended('/admin/dashboard')->with('success', 'Login successful');
        }

        return back()->withErrors([
            'email' => 'Invalid credentials or not an admin.',
        ]);
    }
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/admin/login')->with('success', 'Logout successful');
    }

    public function dashboard(Request $request)
    {
  
        $totalCars = Car::count();
        $totalOrders = Order::count();
        $totalSales = Order::where('status', 'paid')->sum('grand_total');
        $totalPaidOrders = Order::where('status', '=', 'paid')->count();
        $availableCars = Car::where('status', 'available')->count();

        $search = $request->get('search');


        $carsQuery = Car::with(['manufacturer', 'cartype']);

        if ($search) {
            $carsQuery->where(function ($query) use ($search) {
                $query->where('name', 'like', '%' . $search . '%')
                    ->orWhere('color', 'like', '%' . $search . '%')
                    ->orWhereHas('manufacturer', function ($q) use ($search) {
                        $q->where('name', 'like', '%' . $search . '%');
                    });
            });
        }

        $cars = $carsQuery->orderBy('created_at', 'desc')->paginate(8);

        return view('admin.dashboard', compact(
            'cars',
            'totalCars',
            'totalOrders',
            'totalSales',
            'availableCars',
            'search',
            'totalPaidOrders',

        ));
    }

    public function order(Request $request)
    {
        $status = $request->get('status');

        $ordersQuery = Order::with(['orderCars.car', 'user'])
            ->orderBy('created_at', 'desc');

        if ($status && in_array($status, ['paid', 'pending', 'expired', 'cancelled'])) {
            $ordersQuery->where('status', $status);
        }

        $orders = $ordersQuery->paginate(10);

        $statusCounts = [
            'all' => Order::count(),
            'paid' => Order::where('status', 'paid')->count(),
            'pending' => Order::where('status', 'pending')->count(),
            'expired' => Order::where('status', 'expired')->count(),
            'cancelled' => Order::where('status', 'cancelled')->count(),
        ];

        return view('admin.order', compact('orders', 'status', 'statusCounts'));
    }

    public function orderDetail($id)
    {
        $order = Order::with(['orderCars.car', 'user'])->findOrFail($id);

        if (!$order) {
            return redirect()->route('admin.order')->with('error', 'Order not found.');
        }

        return view('admin.order-detail', compact('order'));
    }


    public function showForgetPasswordForm()
    {
        return view('admin.forget-password');
    }
    public function forgetPassword(Request $request)
    {
        $request->validate([
            'email' => 'required|email'
        ]);

        $user = User::where('email', $request->email)->where('role', 'admin')->first();

        if (!$user) {
            return back()->withErrors([
                'email' => 'Email not found in our records.'
            ]);
        }

        $request->session()->put('reset_email', $request->email);

        return redirect()->route('admin.update-password')->with('success', 'Email verified! Please enter your new password.');
    }

    public function showUpdatePasswordForm(Request $request)
    {
        if (!$request->session()->has('reset_email')) {
            return redirect()->route('admin.forget-password')->with('error', 'Please verify your email first.');
        }

        return view('admin.change-password', [
            'email' => $request->session()->get('reset_email')
        ]);
    }
    public function updatePassword(Request $request)
    {
        if (!$request->session()->has('reset_email')) {
            return redirect()->route('admin.forget-password')->with('error', 'Session expired. Please verify your email again.');
        }

        $request->validate([
            'password' => 'required|string|min:8|confirmed',
        ]);

        $email = $request->session()->get('reset_email');
        $user = User::where('email', $email)->where('role', 'admin')->first();

        if (!$user) {
            return redirect()->route('admin.forget-password')->with('error', 'email not found not found.');
        }

        $user->update([
            'password' => Hash::make($request->password)
        ]);

        $request->session()->forget('reset_email');

        return redirect()->route('admin.login')->with('success', 'Password updated successfully! Please login with your new password.');
    }


    public function checkPaymentStatus(Order $order)
    {

        Config::$serverKey = config('midtrans.server_key');
        Config::$isProduction = config('midtrans.is_production');

        try {

            $status = Transaction::status($order->order_number);

            $oldStatus = $order->status;

            if ($status->transaction_status == 'settlement' || $status->transaction_status == 'capture') {
                $order->status = 'paid';

                foreach ($order->orderCars as $orderCar) {
                    if ($orderCar->car) {
                        $orderCar->car->status = 'sold';
                        $orderCar->car->save();
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

            $message = $oldStatus !== $order->status
                ? "Status updated from '{$oldStatus}' to '{$order->status}'"
                : "Status confirmed: {$order->status}";

            return redirect()->back()->with('success', $message);
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Failed to check status: ' . $e->getMessage());
        }
    }
}
