<!DOCTYPE html>
<html>
<head>
    <title>Checkout - Grocery Store</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; background: #f5f5f5; }
        .navbar { background: #2d6a4f; padding: 15px; color: white; display: flex; justify-content: space-between; }
        .navbar a { color: white; text-decoration: none; margin-left: 15px; }
        table { width: 100%; border-collapse: collapse; background: white; margin-top: 20px; }
        th, td { padding: 12px; text-align: left; border-bottom: 1px solid #ddd; }
        th { background: #2d6a4f; color: white; }
        .total { font-size: 20px; font-weight: bold; text-align: right; margin-top: 15px; }
        .btn-green { background: #2d6a4f; color: white; padding: 12px 30px; border: none; border-radius: 4px; cursor: pointer; font-size: 16px; }
    </style>
</head>
<body>

<div class="navbar">
    <span>🛒 Grocery Store</span>
    <div>
        <a href="/">Products</a>
        <a href="/cart">Cart</a>
    </div>
</div>

<div style="margin: 20px;">
    <h2>Order Summary</h2>

    <table>
        <thead>
            <tr>
                <th>Product</th>
                <th>Price</th>
                <th>Quantity</th>
                <th>Subtotal</th>
            </tr>
        </thead>
        <tbody>
            @php $total = 0; @endphp
            @foreach($cart as $productId => $item)
            @php $total += $item['price'] * $item['quantity']; @endphp
            <tr>
                <td>{{ $item['name'] }}</td>
                <td>Rs. {{ number_format($item['price'], 0) }}</td>
                <td>{{ $item['quantity'] }}</td>
                <td>Rs. {{ number_format($item['price'] * $item['quantity'], 0) }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div class="total">Total: Rs. {{ number_format($total, 0) }}</div>

    <form action="/checkout" method="POST" style="text-align: right; margin-top: 20px;">
        @csrf
        <button type="submit" class="btn-green">Place Order</button>
    </form>
</div>

</body>
</html>