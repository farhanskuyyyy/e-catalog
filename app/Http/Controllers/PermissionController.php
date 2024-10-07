<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Permission;
use App\Http\Requests\StorePermissionRequest;
use App\Http\Requests\UpdatePermissionRequest;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Routing\Controllers\HasMiddleware;

class PermissionController extends Controller implements HasMiddleware
{
    public static function middleware(): array
    {
        return [
            new Middleware('permission:view permissions', ['index']),
            new Middleware('permission:edit permissions', ['edit', 'update']),
            new Middleware('permission:create permissions', ['create', 'store']),
            new Middleware('permission:delete permissions', ['destroy']),
            new Middleware('permission:show permissions', ['show']),
        ];
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $permissions = Permission::all();
        return view('permissions.index', compact('permissions'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('permissions.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StorePermissionRequest $request)
    {
        DB::transaction(function () use ($request) {
            $validated = $request->validated();
            $permission = Permission::create($validated);
        });

        return redirect()->route('permissions.index')->with('success','Success Created');;
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
    public function edit(Permission $permission)
    {
        return view('permissions.edit', compact('permission'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdatePermissionRequest $request, Permission $permission)
    {
        DB::transaction(function () use ($request, $permission) {
            $validated = $request->validated();
            $permission->update($validated);
        });

        return redirect()->route('permissions.index')->with('success','Success Updated');;
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Permission $permission)
    {
        try {
            DB::beginTransaction();
            $permission->delete();
            DB::commit();
        } catch (\Throwable $th) {
            DB::rollBack();
        }
        return redirect()->route('permissions.index')->with('success','Success Deleted');;
    }
}
