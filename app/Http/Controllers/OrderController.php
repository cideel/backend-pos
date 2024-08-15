<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class OrderController extends Controller
{
    public function store(Request $request)
    {
        // Logging request headers
        Log::info('Request headers:', $request->headers->all());

        // Mendapatkan customer yang login
        $customer = Auth::user();
        if ($customer) {
            Log::info('Authenticated customer:', ['customer' => $customer]);
        } else {
            Log::info('No authenticated customer');
            return response()->json(['message' => 'Customer not authenticated'], 401);
        }

        $request->validate([
            'total_price' => 'required|numeric',
            'order_items' => 'required|array',
            'order_items.*.item_id' => 'required|exists:menu_items,item_id', // Gunakan 'item_id' sebagai primary key
            'order_items.*.quantity' => 'required|integer|min:1',
        ]);

        // Buat pesanan baru dengan customer_id dari customer yang login
        $order = Order::create([
            'customer_id' => $customer->customer_id,
            'total_price' => $request->total_price,
            'order_status' => 'pending',
            'payment_status' => 'unpaid',
            'order_date' => now(),
        ]);

        foreach ($request->order_items as $item) {
            OrderItem::create([
                'order_id' => $order->id,
                'item_id' => $item['item_id'], // Gunakan 'item_id'
                'quantity' => $item['quantity'],
            ]);
        }

        return response()->json(['message' => 'Order created successfully'], 201);
    }

    public function history()
    {
        // Mendapatkan customer yang login
        $customer = Auth::user();
        if (!$customer) {
            return response()->json(['message' => 'Customer not authenticated'], 401);
        }

        // Mendapatkan riwayat pesanan untuk customer yang login
        $orders = Order::with('orderItems.menuItem')
            ->where('customer_id', $customer->customer_id)
            ->orderBy('order_date', 'desc')
            ->get();

        return response()->json(['orders' => $orders], 200);
    }
}
