<?php

namespace App\Http\Controllers;

use Exception;
use App\Models\User;
use App\Models\Order;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
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
            "CASH", "TRANSFER"
        ];
        $shippings = [
            "AMBIL SENDIRI", "DIANTAR"
        ];
        $status = [
            "PENDING", "PROCESS", "DONE", "DELIVERED", "CANCEL"
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
            }
            return redirect()->route('orders.index')->with('success', "Success");
        } catch (\Exception $th) {
            return redirect()->back()->with('error', "Failed");
        }
    }

    /**
     * Display the specified resource.
     */
    public function show($order)
    {
        try {
            $findOrder = Order::with(['user','lists'])->find($order);
            return view('orders.show', compact('findOrder'));
        } catch (\Exception $th) {
            return redirect()->back()->with('error', "Data Not Found");
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($order)
    {
        try {
            $findOrder = Order::find($order);
            $users = User::all();
            $payments = [
                "CASH", "TRANSFER"
            ];
            $shippings = [
                "AMBIL SENDIRI", "DIANTAR"
            ];
            $status = [
                "PENDING", "PROCESS", "DONE", "DELIVERED", "CANCEL"
            ];

            return view('orders.edit', compact('findOrder', 'users', 'payments', 'shippings', 'status'));
        } catch (\Exception $th) {
            return redirect()->back()->with('error', "Data Not Found");
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $order)
    {
        $request->validate([
            'user' => ['required'],
            'payment' => ['required'],
            'shipping' => ['required'],
            'status' => ['required'],
            // 'pickup_at' => ['required']
        ]);

        try {
            $findOrder = Order::find($order);
            if ($findOrder == null) {
                throw new Exception("Order Not Found");
            }

            try {
                DB::beginTransaction();
                $update = $findOrder->update([
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
            }
            return redirect()->route('orders.index')->with('success', "Success");
        } catch (\Exception $th) {
            return redirect()->back()->with('error', "Failed");
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($order)
    {
        try {
            $findOrder = Order::find($order);
            if ($findOrder == null) {
                throw new Exception("Order Not Found");
            }

            $delete = $findOrder->delete();
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
                'data' => Order::with('user')->orderBy('created_at','asc')->get()
            ]);
        } catch (\Throwable $th) {
            return response()->json([
                'data' => []
            ]);
        }
    }
}
