<?php

namespace App\Http\Controllers;

use Exception;
use App\Models\Product;
use App\Models\Category;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Routing\Controllers\HasMiddleware;

class ProductController extends Controller implements HasMiddleware
{
    public static function middleware(): array
    {
        return [
            new Middleware('permission:view products', ['index', 'getDataList']),
            new Middleware('permission:edit products', ['edit', 'update']),
            new Middleware('permission:create products', ['create', 'store']),
            new Middleware('permission:delete products', ['destroy']),
            new Middleware('permission:show products', ['show']),
        ];
    }

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
        return view('products.create', compact('categories'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => ['required'],
            'category_id' => ['required'],
            'price' => ['required', 'numeric'],
            'stock' => ['required', 'numeric'],
            'estimated_time' => ['required', 'numeric'],
            'image' => 'required|mimes:jpeg,png,jpg,gif,svg,pdf,doc,docx,txt|max:4096'
        ]);

        try {
            $findCategory = Category::find($request->input('category_id'));
            if (!$findCategory) {
                throw new Exception("Category Not Found");
            }

            if ($request->file('image')) {
                $imagePath = $request->file('image')->store('products', 'public');
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
                    "image" => $imagePath ?? null,
                ]);

                if (!$insert) {
                    throw new Exception("Failed Insert Product");
                }

                DB::commit();
            } catch (\Exception $th) {
                DB::rollBack();
                throw new Exception($th->getMessage());
            }
            return redirect()->route('products.index')->with('success', "Success");
        } catch (\Exception $th) {
            return redirect()->back()->with('error', "Failed");
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Product $product)
    {
        try {
            return view('products.show', compact('product'));
        } catch (\Exception $th) {
            return redirect()->back()->with('error', "Data Not Found");
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Product $product)
    {
        try {
            $categories = Category::all();
            return view('products.edit', compact('product', 'categories'));
        } catch (\Exception $th) {
            return redirect()->back()->with('error', "Data Not Found");
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Product $product)
    {
        $request->validate([
            'name' => ['required'],
            'category_id' => ['required'],
            'price' => ['required', 'numeric'],
            'stock' => ['required', 'numeric'],
            'estimated_time' => ['required'],
            'image' => ['sometimes', 'mimes:jpeg,png,jpg,gif,svg,pdf,doc,docx,txt', 'max:4096']
        ]);

        try {
            if ($request->file('image')) {
                $imagePath = $request->file('image')->store('products', 'public');
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
                    $data["image"] = $imagePath;
                    if ($product->image) {
                        if (Storage::exists("public/{$product->image}")) {
                            Storage::delete("public/{$product->image}");
                        }
                    }
                }

                $update = $product->update($data);

                if (!$update) {
                    throw new Exception("Failed Update Product");
                }

                DB::commit();
            } catch (\Exception $th) {
                DB::rollBack();
                throw new Exception($th->getMessage());
            }
            return redirect()->route('products.index')->with('success', "Success");
        } catch (\Exception $th) {
            return redirect()->back()->with('error', "Failed");
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Product $product)
    {
        try {
            $fileName = $product->image;
            $delete = $product->delete();
            if (!$delete) {
                throw new Exception("Failed Delete Product");
            }

            if ($fileName) {
                if (Storage::exists("public/{$fileName}")) {
                    Storage::delete("public/{$fileName}");
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
                'data' => Product::with('category')->get()->map(function ($product) {
                    $action = "";
                    if (Auth::user()->can('show products')) {
                        $route = route('products.show', ['product' => $product]);
                        $action .= "<a href='$route' class='btn btn-success btn-sm mr-1' alt='View Detail' title='View Detail'><i class='fa fa-eye'></i></a>";
                    }
                    if (Auth::user()->can('edit products')) {
                        $route = route('products.edit', ['product' => $product]);
                        $action .= "<a href='$route' class='btn btn-warning btn-sm mr-1' alt='View Edit' title='View Edit'><i class='fa fa-edit'></i></a>";
                    }
                    if (Auth::user()->can('delete products')) {
                        $route = route('products.destroy', ['product' => $product]);
                        $action .= "<a href='javascript:void(0)' onclick='deleteProduct(\"{$route}\")' class='btn btn-danger btn-sm mr-1' alt='Delete' title='Delete'><i class='fa fa-trash'></i></a>";
                    }
                    return (object)[
                        'id' => $product->id,
                        'name' => $product->name,
                        'price' => $product->price,
                        'stock' => $product->stock,
                        'description' => $product->description,
                        'image' => $product->image,
                        'estimated_time' => $product->estimated_time,
                        'category' => $product->category,
                        'created_at' => $product->created_at,
                        'updated_at' => $product->updated_at,
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
