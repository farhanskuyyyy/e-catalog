<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $orderStats = DB::select("select
        sum(CASE WHEN status = 'CANCEL' THEN 1 ELSE 0 END) AS status_cancel,
        sum(CASE WHEN status = 'PENDING' THEN 1 ELSE 0 END) AS status_pending,
        sum(CASE WHEN status = 'PROCESS' THEN 1 ELSE 0 END) AS status_process,
        sum(CASE WHEN status = 'SUCCESS' THEN 1 ELSE 0 END) AS status_success,
        sum(CASE WHEN status = 'DELIVERED' THEN 1 ELSE 0 END) AS status_delivered,
        count(id) as total
        from orders");

        $totalRevenue = DB::select("select sum(total_amount) as total from orders where status = 'DELIVERED'");
        $orders = Order::with('user')->orderBy('created_at','asc')->limit(5)->get();
        return view('dashboard',compact('orderStats','totalRevenue','orders'));
    }
}
