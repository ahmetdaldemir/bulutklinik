<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderProduct;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class OrderController extends Controller
{

    public function index()
    {
        try {
            $order = Order::all();

            return response()->json([
                'status' => true,
                'message' => 'All Orders',
                'data' => $order
            ], 200);

        } catch (\Throwable $th) {
            return response()->json([
                'status' => false,
                'message' => $th->getMessage()
            ], 500);
        }
    }

    public function show(int $productId)
    {
        try {
            $order = Order::find($productId);

            return response()->json([
                'status' => true,
                'message' => 'Order',
                'data' => $order
            ], 200);

        } catch (\Throwable $th) {
            return response()->json([
                'status' => false,
                'message' => $th->getMessage()
            ], 500);
        }
    }

    public function store(Request $request)
    {
        try {
            //Validated
            $validateUser = Validator::make($request->all(),
                [
                    'customerId' => 'required',
                    'address' => 'required',
                    'totalPrice' => 'required',
                ]);

            if ($validateUser->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => 'validation error',
                    'errors' => $validateUser->errors()
                ], 401);
            }

            if (isset($request->id)) {
                $order = Order::find($request->id);
                $order->customerId = $request->customerId;
                $order->totalPrice = $request->totalPrice;
                $order->address = $request->address;
                $order->save();

                OrderProduct::where('orderId',$request->id)->delete();
                foreach ($request->products as $product) {
                    $product_order = new OrderProduct();
                    $product_order->orderId = $request->id;
                    $product_order->productId = $product['productId'];
                    $product_order->quantity = $product['quantity'];
                    $product_order->price = Product::find($product['productId'])->price;
                    $product_order->save();
                }
            } else {
                $order = Order::create([
                    'customerId' => $request->customerId,
                    'totalPrice' => $request->totalPrice,
                    'address' => $request->address,
                ]);
                $id = $order->id;
                foreach ($request->products as $product) {
                    $product_order = new OrderProduct();
                    $product_order->orderId = $id;
                    $product_order->productId = $product['productId'];
                    $product_order->quantity = $product['quantity'];
                    $product_order->price = Product::find($product['productId'])->price;
                    $product_order->save();
                }
            }


            return response()->json([
                'status' => true,
                'message' => 'Order Created Successfully',
                'token' => $order->createToken("API TOKEN")->plainTextToken
            ], 200);

        } catch (\Throwable $th) {
            return response()->json([
                'status' => false,
                'message' => $th->getMessage()
            ], 500);
        }
    }

}
