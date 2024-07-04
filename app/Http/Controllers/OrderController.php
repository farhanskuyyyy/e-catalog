<?php

namespace App\Http\Controllers;

use Exception;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('order.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('order.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => ['required'],
        ]);

        try {
            try {
                DB::beginTransaction();
                $insert = Order::create([
                    "name" => $request->input('name')
                ]);

                if (!$insert) {
                    throw new Exception("Failed Insert Order");
                }

                DB::commit();
            } catch (\Exception $th) {
                DB::rollBack();
            }
            return redirect()->route('order.index')->with('success', "Success");
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
            $findOrder = Order::find($order);
            return view('order.show', compact('findOrder'));
        } catch (\Exception $th) {
            return redirect()->back()->with('error',"Data Not Found");
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($order)
    {
        try {
            $findOrder = Order::find($order);
            return view('order.edit', compact('findOrder'));
        } catch (\Exception $th) {
            return redirect()->back()->with('error',"Data Not Found");
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $order)
    {
        $request->validate([
            'name' => ['required'],
        ]);

        try {
            $findOrder = Order::find($order);
            if ($findOrder == null) {
                throw new Exception("Order Not Found");
            }

            try {
                DB::beginTransaction();
                $update = $findOrder->update([
                    'name' => $request->input('name')
                ]);

                if (!$update) {
                    throw new Exception("Failed Update Order");
                }

                DB::commit();
            } catch (\Exception $th) {
                DB::rollBack();
            }
            return redirect()->route('order.index')->with('success', "Success");
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
                'data' => Order::all()
            ]);
        } catch (\Throwable $th) {
            return response()->json([
                'data' => []
            ]);
        }
    }
}
