<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class CartController extends Controller
{
    public function addToCart(Request $request)
    {
        $product = Product::findOrFail($request->product_id);
        $quantity = $request->quantity ?? 1;

        // Inventory check
        $inventory = \DB::table('inventory')
            ->where('product_id', $product->id)
            ->first();

        if (!$inventory || $inventory->quantity < $quantity) {
            return redirect('/')->with('error', 'Insufficient stock.');
        }

        $cart = session()->get('cart', []);

        if (isset($cart[$product->id])) {
            $cart[$product->id]['quantity'] += $quantity;
        } else {
            $cart[$product->id] = [
                'name'     => $product->name,
                'price'    => $product->price,
                'quantity' => $quantity,
            ];
        }

        session()->put('cart', $cart);

        return redirect('/cart')->with('success', 'Item added to cart.');
    }

    public function viewCart()
    {
        $cart = session()->get('cart', []);
        $total = array_sum(array_map(fn($item) => $item['price'] * $item['quantity'], $cart));
        return view('cart.index', compact('cart', 'total'));
    }

    public function removeFromCart($productId)
    {
        $cart = session()->get('cart', []);
        unset($cart[$productId]);
        session()->put('cart', $cart);
        return redirect('/cart')->with('success', 'Item removed.');
    }

    public function clearCart()
    {
        session()->forget('cart');
        return redirect('/cart')->with('success', 'Cart cleared.');
    }
}