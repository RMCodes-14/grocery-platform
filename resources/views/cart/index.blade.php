<!DOCTYPE html>
<html>
<head>
    <title>Cart - Grocery Store</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; background: #f5f5f5; }
        .navbar { background: #2d6a4f; padding: 15px; color: white; display: flex; justify-content: space-between; align-items: center; }
        .navbar a { color: white; text-decoration: none; margin-left: 15px; }
        table { width: 100%; border-collapse: collapse; background: white; margin-top: 20px; }
        th, td { padding: 12px; text-align: left; border-bottom: 1px solid #ddd; }
        th { background: #2d6a4f; color: white; }
        .total { font-size: 20px; font-weight: bold; text-align: right; margin-top: 15px; }
        .btn { padding: 8px 16px; border: none; border-radius: 4px; cursor: pointer; }
        .btn-red { background: #e63946; color: white; }
        .btn-green { background: #2d6a4f; color: white; }
        .alert { padding: 10px; margin: 10px 0; border-radius: 4px; }
        .alert-success { background: #d4edda; color: #155724; }
        .alert-error { background: #f8d7da; color: #721c24; }
    </style>
</head>
<body>

<div class="navbar">
    <span>🛒 Grocery Store</span>
    <div>
        <a href="/">Products</a>
        <span style="margin-left:15px">{{ auth()->user()->name }}</span>
        <form action="/logout" method="POST" style="display:inline">
            @csrf
            <button type="submit" style="background:none; border:none; color:white; cursor:pointer; margin-left:15px">Logout</button>
        </form>
    </div>
</div>

<div style="margin: 20px;">
    <h2>Your Cart</h2>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    @if(session('error'))
        <div class="alert alert-error">{{ session('error') }}</div>
    @endif

    @if(empty($cart))
        <p>Your cart is empty. <a href="/">Continue shopping</a></p>
    @else
        <table>
            <thead>
                <tr>
                    <th>Product</th>
                    <th>Price</th>
                    <th>Quantity</th>
                    <th>Subtotal</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                @foreach($cart as $productId => $item)
                <tr>
                    <td>{{ $item['name'] }}</td>
                    <td>Rs. {{ number_format($item['price'], 0) }}</td>
                    <td>{{ $item['quantity'] }}</td>
                    <td>Rs. {{ number_format($item['price'] * $item['quantity'], 0) }}</td>
                    <td>
                        <form action="/cart/remove/{{ $productId }}" method="POST">
                            @csrf
                            @method('DELETE')
                            <button class="btn btn-red">Remove</button>
                        </form>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>

        <div class="total">Total: Rs. {{ number_format($total, 0) }}</div>

        <div style="margin-top: 15px; display: flex; gap: 10px; justify-content: flex-end;">
            <form action="/cart/clear" method="POST">
                @csrf
                <button class="btn btn-red">Clear Cart</button>
            </form>
            <a href="/checkout" class="btn btn-green" style="text-decoration:none;">Proceed to Checkout</a>
        </div>
    @endif
</div>

</body>
</html>