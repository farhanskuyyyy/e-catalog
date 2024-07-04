<?php

namespace App\Http\Controllers;

use Exception;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('user.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('user.create');
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
                $insert = User::create([
                    "name" => $request->input('name')
                ]);

                if (!$insert) {
                    throw new Exception("Failed Insert User");
                }

                DB::commit();
            } catch (\Exception $th) {
                DB::rollBack();
            }
            return redirect()->route('user.index')->with('success', "Success");
        } catch (\Exception $th) {
            return redirect()->back()->with('error', "Failed");
        }
    }

    /**
     * Display the specified resource.
     */
    public function show($user)
    {
        try {
            $findCategory = User::find($user);
            return view('user.show', compact('findCategory'));
        } catch (\Exception $th) {
            return redirect()->back()->with('error',"Data Not Found");
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($user)
    {
        try {
            $findCategory = User::find($user);
            return view('user.edit', compact('findCategory'));
        } catch (\Exception $th) {
            return redirect()->back()->with('error',"Data Not Found");
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $user)
    {
        $request->validate([
            'name' => ['required'],
        ]);

        try {
            $findCategory = User::find($user);
            if ($findCategory == null) {
                throw new Exception("User Not Found");
            }

            try {
                DB::beginTransaction();
                $update = $findCategory->update([
                    'name' => $request->input('name')
                ]);

                if (!$update) {
                    throw new Exception("Failed Update User");
                }

                DB::commit();
            } catch (\Exception $th) {
                DB::rollBack();
            }
            return redirect()->route('user.index')->with('success', "Success");
        } catch (\Exception $th) {
            return redirect()->back()->with('error', "Failed");
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($user)
    {
        try {
            $findCategory = User::find($user);
            if ($findCategory == null) {
                throw new Exception("User Not Found");
            }

            $delete = $findCategory->delete();
            if (!$delete) {
                throw new Exception("Failed Delete User");
            }

            return response()->json([
                "status" => true,
                "message" => "Success Delete User"
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
                'data' => User::all()
            ]);
        } catch (\Throwable $th) {
            return response()->json([
                'data' => []
            ]);
        }
    }
}
