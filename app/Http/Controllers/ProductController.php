<?php

namespace App\Http\Controllers;

use Exception;
use App\Models\Product;
use App\Models\Category;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('products.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $categories = Category::all();
        return view('products.create',compact('categories'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => ['required'],
            'category_id' => ['required'],
            'price' => ['required','numeric'],
            'stock' => ['required','numeric'],
            'estimated_time' => ['required','numeric'],
            'image' => 'required|mimes:jpeg,png,jpg,gif,svg,pdf,doc,docx,txt|max:4096'
        ]);

        try {
            $findCategory = Category::find($request->input('category_id'));
            if (!$findCategory) {
                throw new Exception("Category Not Found");
            }

            $fileName = '';
            $name = "product_" . Str::random(4) . time();

            if ($request->file('image')) {
                $fileName = $name . '.' . $request->image->extension();
                $request->image->storeAs('public/product', $fileName);
            }
            try {
                DB::beginTransaction();
                $insert = Product::create([
                    "name" => $request->input('name'),
                    "category_id" => $request->input('category_id'),
                    "price" => $request->input('price'),
                    "stock" => $request->input('stock'),
                    "estimated_time" => $request->input('estimated_time'),
                    "description" => $request->input('description') ?? null,
                    "image" => $fileName,
                ]);

                if (!$insert) {
                    throw new Exception("Failed Insert Product");
                }

                DB::commit();
            } catch (\Exception $th) {
                DB::rollBack();
            }
            return redirect()->route('products.index')->with('success', "Success");
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
            $findProduct = Product::find($product);
            return view('products.show', compact('findProduct'));
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
            $findProduct = Product::find($product);
            $categories = Category::all();
            return view('products.edit', compact('findProduct','categories'));
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
            'category_id' => ['required'],
            'price' => ['required','numeric'],
            'stock' => ['required','numeric'],
            'estimated_time' => ['required'],
        ]);

        try {
            $fileName = '';
            $name = "product_" . Str::random(4) . time();

            if ($request->file('image')) {
                $request->validate([
                    'image' => ['mimes:jpeg,png,jpg,gif,svg,pdf,doc,docx,txt|max:4096']
                ]);

                $fileName = $name . '.' . $request->image->extension();
                $request->image->storeAs('public/product', $fileName);

            }

            $findProduct = Product::find($product);
            if ($findProduct == null) {
                throw new Exception("Product Not Found");
            }

            try {
                DB::beginTransaction();
                $data = [
                    "name" => $request->input('name'),
                    "category_id" => $request->input('category_id'),
                    "price" => $request->input('price'),
                    "stock" => $request->input('stock'),
                    "estimated_time" => $request->input('estimated_time'),
                    "description" => $request->input('description') ?? null,
                ];

                if ($request->file('image')) {
                    $data["image"] = $fileName;
                    if ($findProduct->image) {
                        if (Storage::exists('public/product/' . $findProduct->image)) {
                            Storage::delete('public/product/' . $findProduct->image);
                        }
                    }
                }

                $update = $findProduct->update($data);

                if (!$update) {
                    throw new Exception("Failed Update Product");
                }

                DB::commit();
            } catch (\Exception $th) {
                DB::rollBack();
            }
            return redirect()->route('products.index')->with('success', "Success");
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
            $findProduct = Product::find($product);
            if ($findProduct == null) {
                throw new Exception("Product Not Found");
            }

            $fileName = $findProduct->image;
            $delete = $findProduct->delete();
            if (!$delete) {
                throw new Exception("Failed Delete Product");
            }

            if ($fileName) {
                if (Storage::exists('public/product/' . $fileName)) {
                    Storage::delete('public/product/' . $fileName);
                }
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
                'data' => Product::with('category')->get()
            ]);
        } catch (\Throwable $th) {
            return response()->json([
                'data' => []
            ]);
        }
    }
}
