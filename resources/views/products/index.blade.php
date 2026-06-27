<!DOCTYPE html>
<html>
<head>
    <title>Grocery Store</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; background: #f5f5f5; }
        .navbar { background: #2d6a4f; padding: 15px; color: white; display: flex; justify-content: space-between; align-items: center; }
        .navbar a { color: white; text-decoration: none; margin-left: 15px; }
        .filters { margin: 20px 0; display: flex; gap: 10px; }
        .filters input, .filters select { padding: 8px; border: 1px solid #ddd; border-radius: 4px; }
        .filters button { padding: 8px 16px; background: #2d6a4f; color: white; border: none; border-radius: 4px; cursor: pointer; }
        .grid { display: grid; grid-template-columns: repeat(4, 1fr); gap: 20px; }
        .card { background: white; padding: 15px; border-radius: 8px; box-shadow: 0 2px 4px rgba(0,0,0,0.1); }
        .card h3 { margin: 0 0 8px 0; font-size: 16px; }
        .card .category { color: #666; font-size: 13px; }
        .card .price { font-size: 20px; font-weight: bold; color: #2d6a4f; margin: 10px 0; }
        .card button { width: 100%; padding: 8px; background: #2d6a4f; color: white; border: none; border-radius: 4px; cursor: pointer; }
    </style>
</head>
<body>

<div class="navbar">
    <span>🛒 Grocery Store</span>
    <div>
        @auth
            <span>{{ auth()->user()->name }}</span>
            @if(auth()->user()->role === 'manager')
                <a href="/manager/dashboard">Manager Dashboard</a>
            @endif
            <a href="/cart">Cart</a>
            <form action="/logout" method="POST" style="display:inline">
                @csrf
                <button type="submit" style="background:none; border:none; color:white; cursor:pointer">Logout</button>
            </form>
        @else
            <a href="/login">Login</a>
            <a href="/register">Register</a>
        @endauth
    </div>
</div>

<div style="margin: 20px;">
    <form method="GET" action="/" class="filters">
        <input type="text" name="search" placeholder="Search products..." value="{{ request('search') }}">
        <select name="category">
            <option value="">All Categories</option>
            @foreach($categories as $cat)
                <option value="{{ $cat }}" {{ request('category') == $cat ? 'selected' : '' }}>{{ $cat }}</option>
            @endforeach
        </select>
        <button type="submit">Search</button>
        <a href="/" style="padding: 8px 16px; background: #666; color: white; text-decoration: none; border-radius: 4px;">Clear</a>
    </form>

    <div class="grid">
        @forelse($products as $product)
            <div class="card">
                <h3>{{ $product->name }}</h3>
                <p class="category">{{ $product->category }}</p>
                <p class="price">Rs. {{ number_format($product->price, 0) }}</p>
                <form action="/cart/add" method="POST">
                    @csrf
                    <input type="hidden" name="product_id" value="{{ $product->id }}">
                    <input type="number" name="quantity" value="1" min="1" style="width:50px; margin-bottom:8px;">
                    <button type="submit">Add to Cart</button>
                </form>
            </div>
        @empty
            <p>No products found.</p>
        @endforelse
    </div>
</div>

</body>
</html>