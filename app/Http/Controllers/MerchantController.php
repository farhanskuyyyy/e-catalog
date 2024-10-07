<?php

namespace App\Http\Controllers;

use App\Models\Merchant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Routing\Controllers\HasMiddleware;

class MerchantController extends Controller implements HasMiddleware
{
    public static function middleware(): array
    {
        return [
            new Middleware('permission:view merchants', ['index', 'getDataList']),
            new Middleware('permission:edit merchants', ['edit', 'update']),
            new Middleware('permission:create merchants', ['create', 'store']),
            new Middleware('permission:delete merchants', ['destroy']),
            new Middleware('permission:show merchants', ['show']),
            new Middleware('permission:approve merchants', ['update']),
        ];
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        if (Auth::user()->can('approve merchants')) {
            return view('merchants.index');
        } else {
            return view('merchants.request-merchant');
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $user = Auth::user();
        DB::transaction(function () use ($user) {
            $validate['user_id'] = $user->id;
            $validate['is_active'] = false;

            Merchant::create($validate);
        });

        return redirect()->route('merchants.index')->with('success', 'Success Apply');
    }

    /**
     * Display the specified resource.
     */
    public function show(Merchant $merchant)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Merchant $merchant)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Merchant $merchant)
    {
        try {
            $user = $merchant->user;

            DB::transaction(function () use ($merchant, $user) {
                $merchant->update([
                    'is_active' => true
                ]);

                if (!$user->hasRole('merchant')) {
                    $user->assignRole('merchant');
                }
            });

            return response()->json([
                "status" => true,
                "message" => "Success Approve Merchant"
            ], 200);
        } catch (\Exception $th) {
            return response()->json([
                "status" => false,
                "message" => 'Failed Approve Merchant'
            ], 400);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Merchant $merchant)
    {
        //
    }

    public function getDataList(Request $request)
    {
        try {
            return response()->json([
                'data' => Merchant::all()->map(function ($merchant) {
                    $action = "";
                    if (!$merchant->is_active && Auth::user()->can('approve merchants')) {
                        $route = route('merchants.update', ['merchant' => $merchant]);
                        $action .= "<a href='javascript:void(0)' onclick='approveMerchant(\"{$route}\")' class='btn btn-primary btn-sm mr-1' alt='Edit' title='Edit'>Approve <i class='fa fa-edit'></i></a>";
                    }

                    return (object)[
                        'id' => $merchant->id,
                        'is_active' => $merchant->is_active,
                        'user' => $merchant->user,
                        'created_at' => $merchant->created_at,
                        'updated_at' => $merchant->updated_at,
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
