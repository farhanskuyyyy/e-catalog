<?php

namespace App\Http\Controllers;

use Exception;
use App\Models\Order;
use App\Models\Category;
use App\Models\OrderList;
use App\Models\Product;
use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class PortalController extends Controller
{
    public function index(Request $request)
    {
        $categories = Category::with('products')->get();
        $payments = [
            "CASH", "TRANSFER"
        ];
        $shippings = [
            "AMBIL SENDIRI", "DIANTAR"
        ];
        return view('portal.index', compact('categories', 'shippings', 'payments'));
    }

    public function checkOrder(Request $request)
    {
        try {
            $order_code = $request->input('order_code');
            if ($order_code == "") {
                return view('portal.check-order');
            }

            $order = Order::with('lists')->where('order_code', $order_code)->first();
            if (!$order) {
                return view('portal.check-order', compact('order_code'))->with('error', "Order Not Found");
            }

            return view('portal.check-order', compact('order', 'order_code'));
        } catch (\Exception $th) {
            return view('portal.check-order');
        }
    }

    public function createOrder(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'name'  => 'required',
                'products'  => 'required',
                'phonenumber'  => 'required',
                'shipping'  => 'required',
            ]);

            if ($validator->fails()) {
                throw new Exception("Validation Error");
            }

            try {
                DB::beginTransaction();
                // create or update user
                $user = User::updateOrCreate(
                    ['phonenumber' => $request->input('phonenumber')],
                    ['name' => $request->input('name'), "password" => Hash::make("password")]
                );

                // create order
                $order = Order::create([
                    "order_code" => "INV-" . time() . Str::random(5),
                    "user_id" => $user->id,
                    "note" => $request->input('note') ?? null,
                    "payment" => $request->input('payment'),
                    "shipping" => $request->input('shipping'),
                    "status" => "PENDING",
                    "pickup_at" => null,
                ]);
                $order_code = $order->order_code;
                $total_amount = 0;

                foreach ($request->input('products') as $key => $product) {
                    // find product for safety
                    $findProduct = Product::find($product['id']);
                    if ($findProduct->stock < $product['quantity']) {
                        throw new Exception("Product {$findProduct->name} Out of Stock");
                    }
                    $total_amount += $product['quantity'] * $findProduct->price;

                    // save order list
                    $orderList = OrderList::create([
                        "order_id" => $order->id,
                        "product_id" => $findProduct->id,
                        "price" => $findProduct->price,
                        "quantity" => $product['quantity']
                    ]);

                    // minus stock
                    $findProduct->stock -= $product['quantity'];
                    $findProduct->save();
                }

                // update total amount order
                $updateOrder = Order::find($order->id)->update([
                    "total_amount" => $total_amount
                ]);

                DB::commit();
            } catch (\Throwable $th) {
                DB::rollBack();
                throw new Exception($th->getMessage() ?? "Something Error");
            }

            $response = [
                "status" => true,
                "code" => 200,
                "message" => "Success Create Order",
                "data" => [
                    "order_code" => $order_code
                ],
            ];
        } catch (Exception $th) {
            $response = [
                "status" => false,
                "code" => 200,
                "message" => $th->getMessage() ?? "Failed",
                "data" => null,
            ];
        }

        return response()->json($response, $response["code"]);
    }
}
