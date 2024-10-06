<?php

namespace App\Http\Controllers;

use Exception;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('users.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('users.create');
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
            $insert = User::create([
                "name" => $request->input('name'),
                "phonenumber" => $request->input('phonenumber'),
                "email" => $request->input('email'),
                "password" => Hash::make("password")
            ]);

            if (!$insert) {
                throw new Exception("Failed Insert User");
            }

            return redirect()->route('users.index')->with('success', "Success");
        } catch (\Exception $th) {
            dd($th->getMessage());
            return redirect()->back()->with('error', "Failed");
        }
    }

    /**
     * Display the specified resource.
     */
    public function show($user)
    {
        try {
            $findUser = User::find($user);
            return view('users.show', compact('findUser'));
        } catch (\Exception $th) {
            return redirect()->back()->with('error', "Data Not Found");
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($user)
    {
        try {
            $findUser = User::find($user);
            return view('users.edit', compact('findUser'));
        } catch (\Exception $th) {
            return redirect()->back()->with('error', "Data Not Found");
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
            $findUser = User::find($user);
            if ($findUser == null) {
                throw new Exception("User Not Found");
            }

            try {
                DB::beginTransaction();
                $update = $findUser->update([
                    'name' => $request->input('name'),
                    "phonenumber" => $request->input('phonenumber'),
                    "email" => $request->input('email')
                ]);

                if (!$update) {
                    throw new Exception("Failed Update User");
                }

                DB::commit();
            } catch (\Exception $th) {
                DB::rollBack();
            }
            return redirect()->route('users.index')->with('success', "Success");
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
            $findUser = User::find($user);
            if ($findUser == null) {
                throw new Exception("User Not Found");
            }

            $delete = $findUser->delete();
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
