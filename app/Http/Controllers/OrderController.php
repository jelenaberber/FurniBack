<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Order;
use App\Models\OrderProduct;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function store(Request $request)
    {
        $userId = Auth::user()->id;
        $cart = Cart::where('user_id', $userId)->firstOrFail();
        $delivery = $cart->price < 100 ? 50 : 0;

        DB::transaction(function () use ($request, $cart, $userId, $delivery) {
            $order = Order::create([
                'user_id' => $userId,
                'first_name' => $request->first_name,
                'last_name' => $request->last_name,
                'email' => $request->email,
                'city' => $request->city,
                'address' => $request->address,
                'zip_code' => $request->zip_code,
                'phone' => $request->phone,
                'final_price' => $cart->price,
                'delivery' => $delivery
            ]);

            $cartProducts = $cart->products;

            foreach ($cartProducts as $cartProduct) {
                OrderProduct::create([
                    'order_id' => $order->id,
                    'product_id' => $cartProduct->id,
                    'quantity' => $cartProduct->pivot->quantity,
                    'price' => $cartProduct->price,
                ]);
            }
            $cart->delete();
        });

        return response()->json(['message' => 'Order created successfully'], 201);
    }

    public function index(Request $request): JsonResponse
    {
        $orders = Order::get();

        return response()->json($orders);
    }

    public function destroy($id): JsonResponse
    {
        $order = Order::find($id);
        if (!$order) {
            return response()->json(['order' => 'Order not found'], 404);
        }

        $order->delete();
        return response()->json(['order' => 'Order deleted']);
    }

}
