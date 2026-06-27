<!DOCTYPE html>
<html>
<head>
    <title>Manager Dashboard</title>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; background: #f5f5f5; }
        .navbar { background: #2d6a4f; padding: 15px; color: white; display: flex; justify-content: space-between; }
        .navbar a { color: white; text-decoration: none; margin-left: 15px; }
        .card { background: white; padding: 20px; border-radius: 8px; box-shadow: 0 2px 4px rgba(0,0,0,0.1); margin-bottom: 20px; }
        table { width: 100%; border-collapse: collapse; }
        th, td { padding: 10px; text-align: left; border-bottom: 1px solid #ddd; }
        th { background: #2d6a4f; color: white; }
        .alert-success { background: #d4edda; color: #155724; padding: 10px; border-radius: 4px; margin-bottom: 15px; }
        .badge { padding: 4px 10px; border-radius: 20px; font-size: 12px; font-weight: bold; background: #f8d7da; color: #721c24; }
        select { padding: 6px; border-radius: 4px; border: 1px solid #ddd; }
        .btn { padding: 6px 12px; background: #2d6a4f; color: white; border: none; border-radius: 4px; cursor: pointer; }
    </style>
</head>
<body>

<div class="navbar">
    <span>🛒 Manager Dashboard</span>
    <div>
        <a href="/">Products</a>
        <form action="/logout" method="POST" style="display:inline">
            @csrf
            <button type="submit" style="background:none; border:none; color:white; cursor:pointer; margin-left:15px">Logout</button>
        </form>
    </div>
</div>

<div style="margin: 20px;">

    @if(session('success'))
        <div class="alert-success">{{ session('success') }}</div>
    @endif

    {{-- Sales Chart --}}
    <div class="card">
        <h2>Daily Sales Revenue</h2>
        @if($dailySales->isEmpty())
            <p style="color:#666;">No sales data yet.</p>
        @else
            <canvas id="salesChart" height="80"></canvas>
        @endif
    </div>

    {{-- Low Stock --}}
    <div class="card">
        <h2>Low Stock Products <span class="badge">{{ $lowStock->count() }} items</span></h2>
        @if($lowStock->isEmpty())
            <p style="color:#666;">All products are well stocked.</p>
        @else
            <table>
                <thead>
                    <tr>
                        <th>Product</th>
                        <th>Category</th>
                        <th>Stock</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($lowStock as $item)
                    <tr>
                        <td>{{ $item->name }}</td>
                        <td>{{ $item->category }}</td>
                        <td style="color: red; font-weight: bold;">{{ $item->quantity }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        @endif
    </div>

    {{-- Order Management --}}
    <div class="card">
        <h2>Order Management</h2>
        @php
            $orders = \DB::table('orders')
                ->join('users', 'orders.user_id', '=', 'users.id')
                ->select('orders.*', 'users.name as customer_name')
                ->orderBy('orders.created_at', 'desc')
                ->get();
        @endphp
        <table>
            <thead>
                <tr>
                    <th>Order #</th>
                    <th>Customer</th>
                    <th>Total</th>
                    <th>Status</th>
                    <th>Update</th>
                </tr>
            </thead>
            <tbody>
                @forelse($orders as $order)
                <tr>
                    <td>{{ $order->id }}</td>
                    <td>{{ $order->customer_name }}</td>
                    <td>Rs. {{ number_format($order->total, 0) }}</td>
                    <td>{{ ucfirst($order->status) }}</td>
                    <td>
                        <form action="/manager/orders/{{ $order->id }}/status" method="POST" style="display:flex; gap:8px;">
                            @csrf
                            <select name="status">
                                <option value="placed" {{ $order->status == 'placed' ? 'selected' : '' }}>Placed</option>
                                <option value="packed" {{ $order->status == 'packed' ? 'selected' : '' }}>Packed</option>
                                <option value="dispatched" {{ $order->status == 'dispatched' ? 'selected' : '' }}>Dispatched</option>
                                <option value="delivered" {{ $order->status == 'delivered' ? 'selected' : '' }}>Delivered</option>
                            </select>
                            <button type="submit" class="btn">Update</button>
                        </form>
                    </td>
                </tr>
                @empty
                    <tr><td colspan="5">No orders yet.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>

</div>

@if($dailySales->isNotEmpty())
<script>
    const labels = @json($dailySales->pluck('sale_date'));
    const data = @json($dailySales->pluck('revenue'));

    new Chart(document.getElementById('salesChart'), {
        type: 'bar',
        data: {
            labels: labels,
            datasets: [{
                label: 'Revenue (Rs.)',
                data: data,
                backgroundColor: '#2d6a4f',
            }]
        },
        options: {
            responsive: true,
            scales: { y: { beginAtZero: true } }
        }
    });
</script>
@endif

</body>
</html>