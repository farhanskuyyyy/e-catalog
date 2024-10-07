<?php

namespace App\Http\Controllers;

use Exception;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Routing\Controllers\HasMiddleware;

class CategoryController extends Controller implements HasMiddleware
{
    public static function middleware(): array {
        return [
            new Middleware('permission:view categories',['index']),
            new Middleware('permission:edit categories',['edit','update']),
            new Middleware('permission:create categories',['create','store']),
            new Middleware('permission:delete categories',['destroy']),
            new Middleware('permission:show categories',['show']),
        ];
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('categories.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('categories.create');
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
                $insert = Category::create([
                    "name" => $request->input('name')
                ]);

                if (!$insert) {
                    throw new Exception("Failed Insert Category");
                }

                DB::commit();
            } catch (\Exception $th) {
                DB::rollBack();
                throw new Exception($th->getMessage());
            }
            return redirect()->route('categories.index')->with('success', "Success");
        } catch (\Exception $th) {
            return redirect()->back()->with('error', "Failed");
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Category $category)
    {
        try {
            return view('categories.show', compact('category'));
        } catch (\Exception $th) {
            return redirect()->back()->with('error', "Data Not Found");
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Category $category)
    {
        try {
            return view('categories.edit', compact('category'));
        } catch (\Exception $th) {
            return redirect()->back()->with('error', "Data Not Found");
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Category $category)
    {
        $request->validate([
            'name' => ['required'],
        ]);

        try {
            try {
                DB::beginTransaction();
                $update = $category->update([
                    'name' => $request->input('name')
                ]);

                if (!$update) {
                    throw new Exception("Failed Update Category");
                }

                DB::commit();
            } catch (\Exception $th) {
                DB::rollBack();
                throw new Exception($th->getMessage());
            }
            return redirect()->route('categories.index')->with('success', "Success");
        } catch (\Exception $th) {
            return redirect()->back()->with('error', "Failed");
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Category $category)
    {
        try {
            $delete = $category->delete();
            if (!$delete) {
                throw new Exception("Failed Delete Category");
            }

            return response()->json([
                "status" => true,
                "message" => "Success Delete Category"
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
                'data' => Category::all()->map(function ($category) {
                    $action = "";
                    if (Auth::user()->can('show categories')) {
                        $route = route('categories.show', ['category' => $category]);
                        $action .= "<a href='$route' class='btn btn-success btn-sm mr-1' alt='View Detail' title='View Detail'><i class='fa fa-eye'></i></a>";
                    }
                    if (Auth::user()->can('edit categories')) {
                        $route = route('categories.edit', ['category' => $category]);
                        $action .= "<a href='$route' class='btn btn-warning btn-sm mr-1' alt='View Edit' title='View Edit'><i class='fa fa-edit'></i></a>";
                    }
                    if (Auth::user()->can('delete categories')) {
                        $route = route('categories.destroy', ['category' => $category]);
                        $action .= "<a href='javascript:void(0)' onclick='deleteCategory(\"{$route}\")' class='btn btn-danger btn-sm mr-1' alt='Delete' title='Delete'><i class='fa fa-trash'></i></a>";
                    }

                    return (object)[
                        'id' => $category->id,
                        'name' => $category->name,
                        'created_at' => $category->created_at,
                        'updated_at' => $category->updated_at,
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
