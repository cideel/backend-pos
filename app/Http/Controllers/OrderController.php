<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Api\BaseController;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class OrderController extends BaseController
{
    protected $model = Order::class;

    public function addToCart(Request $request)
    {
        $customer = Auth::user();

        if (!$customer) {
            \Log::error('Unauthorized access attempt.');
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        \Log::info('Customer Data:', ['customer' => $customer]);

        $itemId = $request->input('item_id');
        $quantity = $request->input('quantity', 1);

        $order = Order::firstOrCreate(
            ['customer_id' => $customer->customer_id, 'order_status' => 'pending'],
            ['order_date' => now(), 'total_price' => 0]
        );

        $orderItem = OrderItem::where('order_id', $order->id)->where('item_id', $itemId)->first();

        if ($orderItem) {
            $orderItem->quantity += $quantity;
            $orderItem->save();
        } else {
            OrderItem::create([
                'order_id' => $order->id,
                'item_id' => $itemId,
                'quantity' => $quantity,
            ]);
        }

        $this->updateOrderTotal($order->id);

        return response()->json(['message' => 'Item added to cart successfully.']);
    }

    public function removeFromCart(Request $request)
    {
        $customer = Auth::user();
        if (!$customer) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        $orderId = $request->input('order_id');
        $itemId = $request->input('item_id');
        $quantity = $request->input('quantity', 1);

        $order = Order::where('id', $orderId)
            ->where('customer_id', $customer->customer_id)
            ->where('order_status', 'pending')
            ->first();

        if (!$order) {
            return response()->json(['message' => 'No pending order found'], 404);
        }

        $orderItem = OrderItem::where('order_id', $order->id)->where('item_id', $itemId)->first();

        if ($orderItem) {
            if ($orderItem->quantity > $quantity) {
                $orderItem->quantity -= $quantity;
                $orderItem->save();
            } else {
                $orderItem->delete();
            }
        } else {
            return response()->json(['message' => 'Item not found in cart'], 404);
        }

        $this->updateOrderTotal($order->id);

        return response()->json(['message' => 'Item removed from cart successfully.']);
    }

    public function getCart(Request $request)
    {
        $customer = Auth::user();
        if (!$customer) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        $orderId = $request->input('order_id');

        \Log::info('Customer ID: ', ['customer_id' => $customer->customer_id]);
        \Log::info('Order ID: ', ['order_id' => $orderId]);

        $order = Order::with('orderItems.menuItem')
            ->where('id', $orderId)
            ->where('customer_id', $customer->customer_id)
            ->where('order_status', 'pending')
            ->first();

        if (!$order) {
            \Log::info('No pending order found for customer ID: ' . $customer->customer_id . ' with order ID: ' . $orderId);
            return response()->json(['message' => 'Cart is empty.'], 404);
        }

        return response()->json($order);
    }

    public function addOneCartItem(Request $request)
    {
        $customer = Auth::user();

        if (!$customer) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        $orderId = $request->input('order_id');
        $itemId = $request->input('item_id');

        // Cari pesanan berdasarkan ID pesanan dan ID pelanggan
        $order = Order::where('id', $orderId)
            ->where('customer_id', $customer->customer_id)
            ->first();

        if (!$order) {
            return response()->json(['message' => 'Order not found'], 404);
        }

        // Cari item pesanan berdasarkan ID pesanan dan ID item
        $orderItem = OrderItem::where('order_id', $orderId)
            ->where('item_id', $itemId)
            ->first();

        if (!$orderItem) {
            return response()->json(['message' => 'Item not found in order'], 404);
        }

        // Tambahkan jumlah item pesanan
        $orderItem->quantity += 1;
        $orderItem->save();

        // Perbarui total harga pesanan
        $this->updateOrderTotal($order->id);

        return response()->json(['message' => 'Item quantity increased successfully.']);
    }

    public function minusOneCartItem(Request $request)
    {
        $customer = Auth::user();

        if (!$customer) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        $orderId = $request->input('order_id');
        $itemId = $request->input('item_id');

        // Cari pesanan berdasarkan ID pesanan dan ID pelanggan
        $order = Order::where('id', $orderId)
            ->where('customer_id', $customer->customer_id)
            ->first();

        if (!$order) {
            return response()->json(['message' => 'Order not found'], 404);
        }

        // Cari item pesanan berdasarkan ID pesanan dan ID item
        $orderItem = OrderItem::where('order_id', $orderId)
            ->where('item_id', $itemId)
            ->first();

        if (!$orderItem) {
            return response()->json(['message' => 'Item not found in order'], 404);
        }

        // Kurangi jumlah item pesanan
        if ($orderItem->quantity > 1) {
            $orderItem->quantity -= 1;
            $orderItem->save();
        } else {
            $orderItem->delete();
        }

        // Perbarui total harga pesanan
        $this->updateOrderTotal($order->id);

        return response()->json(['message' => 'Item quantity decreased successfully.']);
    }

    private function updateOrderTotal($orderId)
    {
        $order = Order::find($orderId);
        $totalPrice = $order->orderItems->sum(function ($orderItem) {
            return $orderItem->menuItem->item_price * $orderItem->quantity;
        });
        $order->total_price = $totalPrice;
        $order->save();
    }
}
