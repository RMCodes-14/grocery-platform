<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;

class ManagerController extends Controller
{
    public function dashboard()
    {
        $lowStock = DB::table('low_stock_products')->get();
        $dailySales = DB::table('daily_sales')->get();

        return view('manager.dashboard', compact('lowStock', 'dailySales'));
    }

    public function updateOrderStatus(Request $request, $orderId)
    {
        DB::table('orders')
            ->where('id', $orderId)
            ->update(['status' => $request->status]);

        return redirect('/manager/dashboard')->with('success', 'Order status updated.');
    }
}