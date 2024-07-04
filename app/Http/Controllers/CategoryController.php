<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('category.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('category.create');
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
            }
            return redirect()->route('category.index')->with('success', "Success");
        } catch (\Exception $th) {
            return redirect()->back()->with('error', "Failed");
        }
    }

    /**
     * Display the specified resource.
     */
    public function show($category)
    {
        try {
            $findCategory = Category::find($category);
            return view('category.show', compact('findCategory'));
        } catch (\Exception $th) {
            return redirect()->back()->with('error',"Data Not Found");
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($category)
    {
        try {
            $findCategory = Category::find($category);
            return view('category.edit', compact('findCategory'));
        } catch (\Exception $th) {
            return redirect()->back()->with('error',"Data Not Found");
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $category)
    {
        $request->validate([
            'name' => ['required'],
        ]);

        try {
            $findCategory = Category::find($category);
            if ($findCategory == null) {
                throw new Exception("Category Not Found");
            }

            try {
                DB::beginTransaction();
                $update = $findCategory->update([
                    'name' => $request->input('name')
                ]);

                if (!$update) {
                    throw new Exception("Failed Update Category");
                }

                DB::commit();
            } catch (\Exception $th) {
                DB::rollBack();
            }
            return redirect()->route('category.index')->with('success', "Success");
        } catch (\Exception $th) {
            return redirect()->back()->with('error', "Failed");
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($category)
    {
        try {
            $findCategory = Category::find($category);
            if ($findCategory == null) {
                throw new Exception("Category Not Found");
            }

            $delete = $findCategory->delete();
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
                'data' => Category::all()
            ]);
        } catch (\Throwable $th) {
            return response()->json([
                'data' => []
            ]);
        }
    }
}
