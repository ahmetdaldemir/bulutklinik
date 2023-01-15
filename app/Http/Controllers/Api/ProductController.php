<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ProductController extends Controller
{
    public function index()
    {
        try {
            $products = Product::all();

            return response()->json([
                'status' => true,
                'message' => 'All Products',
                'data' => $products
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
            $products = Product::find($productId);

            return response()->json([
                'status' => true,
                'message' => 'Product',
                'data' => $products
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
            $validateUser = Validator::make($request->all(),
                [
                    'productName' => 'required',
                    'quantity' => 'required',
                    'price' => 'required',
                ]);

            if ($validateUser->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => 'validation error',
                    'errors' => $validateUser->errors()
                ], 401);
            }

            if(!isset($request->id))
            {
                $product = Product::create([
                    'productName' => $request->productName,
                    'quantity' => $request->quantity,
                    'price' => $request->price,
                ]);
            }else{
                $product = Product::find($request->id);
                $product->productName = $request->productName;
                $product->quantity = $request->quantity;
                $product->price = $request->price;
                $product->save();
            }

            return response()->json([
                'status' => true,
                'message' => 'Product Process Successfully',
                'token' => $product->createToken("API TOKEN")->plainTextToken
            ], 200);

        } catch (\Throwable $th) {
            return response()->json([
                'status' => false,
                'message' => $th->getMessage()
            ], 500);
        }
    }

}
