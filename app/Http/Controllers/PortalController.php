<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;

class PortalController extends Controller
{
    public function index(Request $request)
    {
        $categories = Category::with('products')->get();
        $shippings = [
            "AMBIL SENDIRI", "DIANTAR"
        ];
        return view('portal.index',compact('categories','shippings'));
    }
}
