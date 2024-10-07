<?php

namespace App\Http\Controllers;

use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\StoreRoleRequest;
use App\Http\Requests\UpdateRoleRequest;
use Spatie\Permission\Models\Permission;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Routing\Controllers\HasMiddleware;

class RoleController extends Controller implements HasMiddleware
{
    public static function middleware(): array
    {
        return [
            new Middleware('permission:view roles', ['index']),
            new Middleware('permission:edit roles', ['edit', 'update']),
            new Middleware('permission:create roles', ['create', 'store']),
            new Middleware('permission:delete roles', ['destroy']),
            new Middleware('permission:show roles', ['show']),
        ];
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('roles.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        DB::statement("SET SQL_MODE=''");
        $role_permission = Permission::select('name', 'id')->groupBy('name')->get();
        $permissions = [];
        foreach ($role_permission as $per) {

            $name = explode(' ', $per->name)[1];
            $key = substr($name, 0);

            if (str_starts_with($name, $key)) {
                $permissions[$key][] = $per;
            }
        }
        return view('roles.create', compact('permissions'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreRoleRequest $request)
    {
        DB::transaction(function () use ($request) {
            $validated = $request->validated();
            $role = Role::create($validated);

            if (!empty($request->permissions)) {
                foreach ($request->permissions as $key => $permission) {
                    $role->givePermissionTo($permission);
                }
            }
        });

        return redirect()->route('roles.index')->with('success', 'Success Created');
    }

    /**
     * Display the specified resource.
     */
    public function show(Role $role)
    {
        try {
            return view('roles.show', compact('role'));
        } catch (\Exception $th) {
            return redirect()->back()->with('error', "Data Not Found");
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Role $role)
    {
        try {
            DB::statement("SET SQL_MODE=''");
            $role_permission = Permission::with(['roles' => function ($q) use ($role) {
                $q->where('role_id', $role->id);
            }])->groupBy('name')->get();
            $permissions = [];
            foreach ($role_permission as $per) {

                $name = explode(' ', $per->name)[1];
                $key = substr($name, 0);

                if (str_starts_with($name, $key)) {
                    $permissions[$key][] = $per;
                }
            }
            return view('roles.edit', compact('role', 'permissions'));
        } catch (\Exception $th) {
            return redirect()->back()->with('error', "Data Not Found");
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateRoleRequest $request, Role $role)
    {
        DB::transaction(function () use ($request, $role) {
            $validated = $request->validated();
            $role->update($validated);

            if (!empty($request->permissions)) {
                $role->syncPermissions($request->permissions);
            } else {
                $role->syncPermissions([]);
            }
        });

        return redirect()->route('roles.index')->with('success', 'Success Updated');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Role $role)
    {
        try {
            $delete = $role->delete();
            if (!$delete) {
                throw new Exception("Failed Delete Role");
            }

            return response()->json([
                "status" => true,
                "message" => "Success Delete Role"
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
                'data' => Role::all()->map(function ($role) {
                    $action = "";
                    if (Auth::user()->can('show roles')) {
                        $route = route('roles.show', ['role' => $role]);
                        $action .= "<a href='$route' class='btn btn-success btn-sm mr-1' alt='View Detail' title='View Detail'><i class='fa fa-eye'></i></a>";
                    }
                    if (Auth::user()->can('edit roles')) {
                        $route = route('roles.edit', ['role' => $role]);
                        $action .= "<a href='$route' class='btn btn-warning btn-sm mr-1' alt='View Edit' title='View Edit'><i class='fa fa-edit'></i></a>";
                    }
                    if (Auth::user()->can('delete roles')) {
                        $route = route('roles.destroy', ['role' => $role]);
                        $action .= "<a href='javascript:void(0)' onclick='deleteRole(\"{$route}\")' class='btn btn-danger btn-sm mr-1' alt='Delete' title='Delete'><i class='fa fa-trash'></i></a>";
                    }

                    return (object)[
                        'id' => $role->id,
                        'name' => $role->name,
                        'permissions' => count($role->permissions) > 0 ? $role->permissions->pluck('name')->implode(',') : 'No Permission',
                        'created_at' => $role->created_at,
                        'updated_at' => $role->updated_at,
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
