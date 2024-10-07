<?php

namespace App\Http\Controllers;

use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
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
        return view('permissions.index');
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
    public function store(Request $request)
    {
        $request->validate([
            'name' => ['required'],
        ]);

        try {
            try {
                DB::beginTransaction();
                $insert = Permission::create([
                    "name" => $request->input('name')
                ]);

                if (!$insert) {
                    throw new Exception("Failed Insert Permission");
                }

                DB::commit();
            } catch (\Exception $th) {
                DB::rollBack();
                throw new Exception($th->getMessage());
            }
            return redirect()->route('permissions.index')->with('success', "Success");
        } catch (\Exception $th) {
            return redirect()->back()->with('error', "Failed");
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Permission $permission)
    {
        try {
            return view('permissions.show', compact('permission'));
        } catch (\Exception $th) {
            return redirect()->back()->with('error', "Data Not Found");
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Permission $permission)
    {
        try {
            return view('permissions.edit', compact('permission'));
        } catch (\Exception $th) {
            return redirect()->back()->with('error', "Data Not Found");
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Permission $permission)
    {
        $request->validate([
            'name' => ['required'],
        ]);

        try {
            try {
                DB::beginTransaction();
                $update = $permission->update([
                    'name' => $request->input('name')
                ]);

                if (!$update) {
                    throw new Exception("Failed Update Permission");
                }

                DB::commit();
            } catch (\Exception $th) {
                DB::rollBack();
                throw new Exception($th->getMessage());
            }
            return redirect()->route('permissions.index')->with('success', "Success");
        } catch (\Exception $th) {
            return redirect()->back()->with('error', "Failed");
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Permission $permission)
    {
        try {
            $delete = $permission->delete();
            if (!$delete) {
                throw new Exception("Failed Delete Permission");
            }

            return response()->json([
                "status" => true,
                "message" => "Success Delete Permission"
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
                'data' => Permission::all()->map(function ($permission) {
                    $action = "";
                    if (Auth::user()->can('show permissions')) {
                        $route = route('permissions.show', ['permission' => $permission]);
                        $action .= "<a href='$route' class='btn btn-success btn-sm mr-1' alt='View Detail' title='View Detail'><i class='fa fa-eye'></i></a>";
                    }
                    if (Auth::user()->can('edit permissions')) {
                        $route = route('permissions.edit', ['permission' => $permission]);
                        $action .= "<a href='$route' class='btn btn-warning btn-sm mr-1' alt='View Edit' title='View Edit'><i class='fa fa-edit'></i></a>";
                    }
                    if (Auth::user()->can('delete permissions')) {
                        $route = route('permissions.destroy', ['permission' => $permission]);
                        $action .= "<a href='javascript:void(0)' onclick='deletePermission(\"{$route}\")' class='btn btn-danger btn-sm mr-1' alt='Delete' title='Delete'><i class='fa fa-trash'></i></a>";
                    }

                    return (object)[
                        'id' => $permission->id,
                        'name' => $permission->name,
                        'created_at' => $permission->created_at,
                        'updated_at' => $permission->updated_at,
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
