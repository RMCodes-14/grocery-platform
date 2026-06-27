<!DOCTYPE html>
<html>
<head>
    <title>Order History</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; background: #f5f5f5; }
        .navbar { background: #2d6a4f; padding: 15px; color: white; display: flex; justify-content: space-between; }
        .navbar a { color: white; text-decoration: none; margin-left: 15px; }
        .order-card { background: white; padding: 20px; margin: 15px 0; border-radius: 8px; box-shadow: 0 2px 4px rgba(0,0,0,0.1); }
        .status { padding: 4px 10px; border-radius: 20px; font-size: 13px; font-weight: bold; }
        .status-placed { background: #cce5ff; color: #004085; }
        .status-delivered { background: #d4edda; color: #155724; }
        .alert-success { background: #d4edda; color: #155724; padding: 10px; border-radius: 4px; margin-bottom: 15px; }
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
    <h2>Order History</h2>

    @if(session('success'))
        <div class="alert-success">{{ session('success') }}</div>
    @endif

    @forelse($orders as $order)
    <div class="order-card">
        <div style="display: flex; justify-content: space-between; align-items: center;">
            <div>
                <strong>Order #{{ $order->id }}</strong>
                <span style="color:#666; margin-left:15px">{{ $order->created_at }}</span>
            </div>
            <span class="status status-{{ $order->status }}">{{ ucfirst($order->status) }}</span>
        </div>

        <table style="width:100%; margin-top:10px; border-collapse:collapse;">
            <tr style="color:#666; font-size:13px;">
                <th style="text-align:left; padding:5px;">Product</th>
                <th style="text-align:left; padding:5px;">Qty</th>
                <th style="text-align:left; padding:5px;">Price</th>
            </tr>
            @foreach($order->items as $item)
            <tr>
                <td style="padding:5px;">{{ $item->name }}</td>
                <td style="padding:5px;">{{ $item->quantity }}</td>
                <td style="padding:5px;">Rs. {{ number_format($item->price, 0) }}</td>
            </tr>
            @endforeach
        </table>

        <div style="text-align:right; font-weight:bold; margin-top:10px;">
            Total: Rs. {{ number_format($order->total, 0) }}
        </div>

        @if($order->delivery)
        <div style="margin-top:10px; color:#666; font-size:13px;">
            Delivery Status: <strong>{{ ucfirst($order->delivery->status) }}</strong>
        </div>
        @endif
    </div>
    @empty
        <p>No orders yet. <a href="/">Start shopping</a></p>
    @endforelse
</div>

</body>
</html>