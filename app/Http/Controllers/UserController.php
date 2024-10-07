<?php

namespace App\Http\Controllers;

use Exception;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
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
        $roles = Role::get();
        return view('users.create', compact('roles'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => ['required'],
            'email' => ['required'],
            'phonenumber' => ['required'],
            'avatar' => 'required|mimes:jpeg,png,jpg,gif,svg,pdf,doc,docx,txt|max:2048',
        ]);

        try {
            if ($request->file('avatar')) {
                $imagePath = $request->file('avatar')->store('avatars', 'public');
            }

            $user = User::create([
                "name" => $request->input('name'),
                "phonenumber" => $request->input('phonenumber'),
                "email" => $request->input('email'),
                "password" => Hash::make("password"),
                "avatar" => $imagePath ?? null
            ]);

            if (!empty($request->roles)) {
                foreach ($request->roles as $key => $role) {
                    $user->assignRole($role);
                }
            }

            if (!$user) {
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
            $roles = Role::with(['users' => function ($q) use ($user) {
                $q->where('model_id', $user->id);
            }])->get();
            return view('users.show', compact('user', 'roles'));
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
            $roles = Role::with(['users' => function ($q) use ($user) {
                $q->where('model_id', $user->id);
            }])->get();
            return view('users.edit', compact('user', 'roles'));
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
            'email' => ['required'],
            'phonenumber' => ['required'],
            'image' => 'sometimes|mimes:jpeg,png,jpg,gif,svg,pdf,doc,docx,txt|max:2048'
        ]);

        try {
            if ($request->file('avatar')) {
                $imagePath = $request->file('avatar')->store('avatars', 'public');
            }

            try {
                DB::beginTransaction();
                $data = [
                    'name' => $request->input('name'),
                    "phonenumber" => $request->input('phonenumber'),
                    "email" => $request->input('email'),
                ];

                if ($request->file('avatar')) {
                    $data["avatar"] = $imagePath;
                    if ($user->avatar) {
                        if (Storage::exists("public/{$user->avatar}")) {
                            Storage::delete("public/{$user->avatar}");
                        }
                    }
                }

                $update = $user->update($data);

                if (!empty($request->roles)) {
                    $user->syncRoles($request->roles);
                } else {
                    $user->syncRoles([]);
                }

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
            $fileName = $user->avatar;
            $delete = $user->delete();
            if (!$delete) {
                throw new Exception("Failed Delete User");
            }

            if ($fileName) {
                if (Storage::exists("public/{$fileName}")) {
                    Storage::delete("public/{$fileName}");
                }
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
