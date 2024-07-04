<?php

namespace App\Http\Controllers;

use Exception;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('product.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('product.create');
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
                $insert = Product::create([
                    "name" => $request->input('name')
                ]);

                if (!$insert) {
                    throw new Exception("Failed Insert Product");
                }

                DB::commit();
            } catch (\Exception $th) {
                DB::rollBack();
            }
            return redirect()->route('product.index')->with('success', "Success");
        } catch (\Exception $th) {
            return redirect()->back()->with('error', "Failed");
        }
    }

    /**
     * Display the specified resource.
     */
    public function show($product)
    {
        try {
            $findCategory = Product::find($product);
            return view('product.show', compact('findCategory'));
        } catch (\Exception $th) {
            return redirect()->back()->with('error',"Data Not Found");
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($product)
    {
        try {
            $findCategory = Product::find($product);
            return view('product.edit', compact('findCategory'));
        } catch (\Exception $th) {
            return redirect()->back()->with('error',"Data Not Found");
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $product)
    {
        $request->validate([
            'name' => ['required'],
        ]);

        try {
            $findCategory = Product::find($product);
            if ($findCategory == null) {
                throw new Exception("Product Not Found");
            }

            try {
                DB::beginTransaction();
                $update = $findCategory->update([
                    'name' => $request->input('name')
                ]);

                if (!$update) {
                    throw new Exception("Failed Update Product");
                }

                DB::commit();
            } catch (\Exception $th) {
                DB::rollBack();
            }
            return redirect()->route('product.index')->with('success', "Success");
        } catch (\Exception $th) {
            return redirect()->back()->with('error', "Failed");
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($product)
    {
        try {
            $findCategory = Product::find($product);
            if ($findCategory == null) {
                throw new Exception("Product Not Found");
            }

            $delete = $findCategory->delete();
            if (!$delete) {
                throw new Exception("Failed Delete Product");
            }

            return response()->json([
                "status" => true,
                "message" => "Success Delete Product"
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
                'data' => Product::all()
            ]);
        } catch (\Throwable $th) {
            return response()->json([
                'data' => []
            ]);
        }
    }
}
