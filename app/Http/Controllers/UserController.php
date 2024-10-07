<?php

namespace App\Http\Controllers;

use Exception;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Routing\Controllers\HasMiddleware;

class UserController extends Controller implements HasMiddleware
{
    public static function middleware(): array
    {
        return [
            new Middleware('permission:view users', ['index']),
            new Middleware('permission:edit users', ['edit', 'update']),
            new Middleware('permission:create users', ['create', 'store']),
            new Middleware('permission:delete users', ['destroy']),
            new Middleware('permission:show users', ['show']),
        ];
    }
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
            return redirect()->back()->with('error', "Failed");
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(User $user)
    {
        try {
            return view('users.show', compact('user'));
        } catch (\Exception $th) {
            return redirect()->back()->with('error', "Data Not Found");
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(User $user)
    {
        try {
            return view('users.edit', compact('user'));
        } catch (\Exception $th) {
            return redirect()->back()->with('error', "Data Not Found");
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, User $user)
    {
        $request->validate([
            'name' => ['required'],
        ]);

        try {
            try {
                DB::beginTransaction();
                $update = $user->update([
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
                throw new Exception($th->getMessage());
            }
            return redirect()->route('users.index')->with('success', "Success");
        } catch (\Exception $th) {
            return redirect()->back()->with('error', "Failed");
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user)
    {
        try {
            $delete = $user->delete();
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
                'data' => User::all()->map(function ($user) {
                    $action = "";
                    if (Auth::user()->can('show users')) {
                        $route = route('users.show', ['user' => $user]);
                        $action .= "<a href='$route' class='btn btn-success btn-sm mr-1' alt='View Detail' title='View Detail'><i class='fa fa-eye'></i></a>";
                    }
                    if (Auth::user()->can('edit users')) {
                        $route = route('users.edit', ['user' => $user]);
                        $action .= "<a href='$route' class='btn btn-warning btn-sm mr-1' alt='View Edit' title='View Edit'><i class='fa fa-edit'></i></a>";
                    }
                    if (Auth::user()->can('delete users')) {
                        $route = route('users.destroy', ['user' => $user]);
                        $action .= "<a href='javascript:void(0)' onclick='deleteUser(\"{$route}\")' class='btn btn-danger btn-sm mr-1' alt='Delete' title='Delete'><i class='fa fa-trash'></i></a>";
                    }
                    return (object)[
                        'id' => $user->id,
                        'name' => $user->name,
                        'email' => $user->email,
                        'phonenumber' => $user->phonenumber,
                        'roles' => count($user->roles) > 0 ? $user->roles->pluck('name')->implode(',') : 'No Role',
                        'created_at' => $user->created_at,
                        'updated_at' => $user->updated_at,
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
