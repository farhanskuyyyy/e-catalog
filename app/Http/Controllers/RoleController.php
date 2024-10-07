<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Role;
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
        $roles = Role::all();
        return view('roles.index', compact('roles'));
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

            $name = explode(' ',$per->name)[1];
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

        return redirect()->route('roles.index')->with('success','Success Created');;
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Role $role)
    {
        DB::statement("SET SQL_MODE=''");
        $role_permission = Permission::with(['roles' => function ($q) use ($role) {
            $q->where('role_id', $role->id);
        }])->groupBy('name')->get();
        $permissions = [];
        foreach ($role_permission as $per) {

            $name = explode(' ',$per->name)[1];
            $key = substr($name, 0);

            if (str_starts_with($name, $key)) {
                $permissions[$key][] = $per;
            }
        }
        return view('roles.edit', compact('role', 'permissions'));
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

        return redirect()->route('roles.index')->with('success','Success Updated');;
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Role $role)
    {
        try {
            DB::beginTransaction();
            $role->delete();
            DB::commit();
        } catch (\Throwable $th) {
            DB::rollBack();
        }
        return redirect()->route('roles.index')->with('success','Success Deleted');;
    }
}
