<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
    public function checkout()
    {
        $cart = session()->get('cart', []);

        if (empty($cart)) {
            return redirect('/cart')->with('error', 'Your cart is empty.');
        }

        return view('orders.checkout', compact('cart'));
    }

    public function placeOrder(Request $request)
    {
        $cart = session()->get('cart', []);

        if (empty($cart)) {
            return redirect('/cart')->with('error', 'Your cart is empty.');
        }

        $userId = auth()->id();
        $lastOrderId = null;

        foreach ($cart as $productId => $item) {
            $orderId = null;
            $message = null;

            DB::statement('CALL place_order(?, ?, ?, @order_id, @message)', [
                $userId,
                $productId,
                $item['quantity']
            ]);

            $result = DB::select('SELECT @order_id as order_id, @message as message')[0];

            if ($result->message !== 'Order placed successfully') {
                return redirect('/cart')->with('error', $item['name'] . ': ' . $result->message);
            }

            $lastOrderId = $result->order_id;
        }
$notification = new \App\Services\NotificationService();
$notification->sendOrderConfirmation($lastOrderId, auth()->user()->name);
        session()->forget('cart');

        return redirect('/orders/history')->with('success', 'Order placed successfully!');
    }

    public function history()
    {
        $orders = DB::table('orders')
            ->where('user_id', auth()->id())
            ->orderBy('created_at', 'desc')
            ->get();

        foreach ($orders as $order) {
            $order->items = DB::table('order_items')
                ->join('products', 'order_items.product_id', '=', 'products.id')
                ->where('order_items.order_id', $order->id)
                ->select('products.name', 'order_items.quantity', 'order_items.price')
                ->get();

            $order->delivery = DB::table('delivery')
                ->where('order_id', $order->id)
                ->first();
        }

        return view('orders.history', compact('orders'));
    }
}