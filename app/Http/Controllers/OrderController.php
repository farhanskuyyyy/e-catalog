<?php

namespace App\Http\Controllers;

use Exception;
use App\Models\User;
use App\Models\Order;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Routing\Controllers\HasMiddleware;

class OrderController extends Controller implements HasMiddleware
{
    public static function middleware(): array
    {
        return [
            new Middleware('permission:view orders', ['index']),
            new Middleware('permission:edit orders', ['edit', 'update']),
            new Middleware('permission:create orders', ['create', 'store']),
            new Middleware('permission:delete orders', ['destroy']),
            new Middleware('permission:show orders', ['show']),
        ];
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('orders.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $users = User::all();
        $payments = [
            "CASH",
            "TRANSFER"
        ];
        $shippings = [
            "AMBIL SENDIRI",
            "DIANTAR"
        ];
        $status = [
            "PENDING",
            "PROCESS",
            "DONE",
            "DELIVERED",
            "CANCEL"
        ];

        return view('orders.create', compact('users', 'payments', 'shippings', 'status'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'user' => ['required'],
            'payment' => ['required'],
            'shipping' => ['required'],
            'status' => ['required'],
        ]);

        try {
            try {
                DB::beginTransaction();
                $insert = Order::create([
                    "order_code" => "INV-" . time() . Str::random(5),
                    "user_id" => $request->input('user'),
                    "payment" => $request->input('payment'),
                    "shipping" => $request->input('shipping'),
                    "status" => $request->input('status'),
                    "pickup_at" => $request->input('pickup_at') ?? null,
                    "note" => $request->input('note') ?? null,
                ]);

                if (!$insert) {
                    throw new Exception("Failed Insert Order");
                }

                DB::commit();
            } catch (\Exception $th) {
                DB::rollBack();
                throw new Exception($th->getMessage());
            }
            return redirect()->route('orders.index')->with('success', "Success");
        } catch (\Exception $th) {
            return redirect()->back()->with('error', "Failed");
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Order $order)
    {
        try {
            return view('orders.show', compact('order'));
        } catch (\Exception $th) {
            return redirect()->back()->with('error', "Data Not Found");
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Order $order)
    {
        try {
            $users = User::all();
            $payments = [
                "CASH",
                "TRANSFER"
            ];
            $shippings = [
                "AMBIL SENDIRI",
                "DIANTAR"
            ];
            $status = [
                "PENDING",
                "PROCESS",
                "DONE",
                "DELIVERED",
                "CANCEL"
            ];

            return view('orders.edit', compact('order', 'users', 'payments', 'shippings', 'status'));
        } catch (\Exception $th) {
            return redirect()->back()->with('error', "Data Not Found");
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Order $order)
    {
        $request->validate([
            'user' => ['required'],
            'payment' => ['required'],
            'shipping' => ['required'],
            'status' => ['required'],
            // 'pickup_at' => ['required']
        ]);

        try {
            try {
                DB::beginTransaction();
                $update = $order->update([
                    "user_id" => $request->input('user'),
                    "payment" => $request->input('payment'),
                    "shipping" => $request->input('shipping'),
                    "status" => $request->input('status'),
                    "pickup_at" => $request->input('pickup_at') ?? null,
                    "note" => $request->input('note') ?? null,
                ]);

                if (!$update) {
                    throw new Exception("Failed Update Order");
                }

                DB::commit();
            } catch (\Exception $th) {
                DB::rollBack();
                throw new Exception($th->getMessage());
            }
            return redirect()->route('orders.index')->with('success', "Success");
        } catch (\Exception $th) {
            return redirect()->back()->with('error', "Failed");
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Order $order)
    {
        try {
            $delete = $order->delete();
            if (!$delete) {
                throw new Exception("Failed Delete Order");
            }

            return response()->json([
                "status" => true,
                "message" => "Success Delete Order"
            ], 200);
        } catch (\Exception $th) {
            return response()->json([
                "status" => false,
                "message" => 'Failed Delete Data'
            ], 400);
        }
    }

    public function getDataList(Request $request)
    {
        try {
            return response()->json([
                'data' => Order::with('user')->orderBy('created_at', 'asc')->get()->map(function ($order) {
                    $action = "";
                    if (Auth::user()->can('show orders')) {
                        $route = route('orders.show', ['order' => $order]);
                        $action .= "<a href='$route' class='btn btn-success btn-sm mr-1' alt='View Detail' title='View Detail'><i class='fa fa-eye'></i></a>";
                    }
                    if (Auth::user()->can('edit orders')) {
                        $route = route('orders.edit', ['order' => $order]);
                        $action .= "<a href='$route' class='btn btn-warning btn-sm mr-1' alt='View Edit' title='View Edit'><i class='fa fa-edit'></i></a>";
                    }
                    if (Auth::user()->can('delete orders')) {
                        $route = route('orders.destroy', ['order' => $order]);
                        $action .= "<a href='javascript:void(0)' onclick='deleteOrder(\"{$route}\")' class='btn btn-danger btn-sm mr-1' alt='Delete' title='Delete'><i class='fa fa-trash'></i></a>";
                    }
                    return (object)[
                        'id' => $order->id,
                        'order_code' => $order->order_code,
                        'payment' => $order->payment,
                        'shipping' => $order->shipping,
                        'status' => $order->status,
                        'pickup_at' => $order->pickup_at,
                        'user' => $order->user,
                        'created_at' => $order->created_at,
                        'updated_at' => $order->updated_at,
                        'action' => $action
                    ];
                })
            ]);
        } catch (\Throwable $th) {
            return response()->json([
                'data' => []
            ]);
        }
    }
}
