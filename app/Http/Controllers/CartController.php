<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\CartProduct;
use App\Models\Product;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CartController extends Controller
{

    public function addProductToCart($id): JsonResponse
    {
        try {
            $userId = Auth::user()->id;
            $cart = Cart::where('user_id', $userId)->first();
            $product = Product::find($id);

            if (!$cart) {
                $newCart = Cart::create([
                    'user_id' => $userId,
                    'price' => $product->price
                ]);

                $newProductInCart = CartProduct::create([
                    'cart_id' => $newCart->id,
                    'product_id' => $product->id,
                    'price' => $product->price
                ]);
                return response()->json(['success' => 'Successfully added'], 200);
            } else {
                $cart->number_of_products = ($cart->number_of_products) + 1;
                $cart->price = ($cart->price) + $product->price;
                $cart->save();

                $productAlreadyInCart = CartProduct::where('cart_id', $cart->id)->where('product_id', $id)->first();
                if ($productAlreadyInCart) {
                    $productAlreadyInCart->quantity = ($productAlreadyInCart->quantity) + 1;
                    $productAlreadyInCart->save();
                } else {
                    $newProductInCart = CartProduct::create([
                        'cart_id' => $cart->id,
                        'product_id' => $product->id,
                        'price' => $product->price
                    ]);
                }
                return response()->json(['success' => 'Successfully added'], 200);
            }
        } catch (\Exception $e) {
            // Log the error message
            Log::error('Error adding product to cart: ' . $e->getMessage());
            // Return a JSON response with error message
            return response()->json(['error' => 'An error occurred while adding the product to the cart. Please try again later.'], 500);
        }
    }

    public function deleteProduct($id)
    {
        $userId = Auth::user()->id;
        $cart = Cart::Where('user_id', $userId)->first();
        $productForDelete = CartProduct::Where('cart_id', $cart->id)->Where('product_id', $id)->first();
        $cart->number_of_products = ($cart->number_of_products) - ($productForDelete->quantity);
        $cart->price = ($cart->price) - ($productForDelete->quantity) * ($productForDelete->price);
        $cart->save();
        CartProduct::destroy($productForDelete->id);
        return response()->json(['message' => 'Product deleted from cart']);
    }

    public function destroy($id)
    {
        $cart = Cart::find($id);

        if (!$cart) {
            return response()->json(['message' => 'Cart not found'], 404);
        }

        $cartProducts = CartProduct::where('cart_id', $cart->id)->get();

        if ($cartProducts->isEmpty()) {
            return response()->json(['message' => 'No products found in cart']);
        }

        foreach ($cartProducts as $cartProduct) {
            $product = Product::find($cartProduct->product_id);

            if (!$product) {
                continue;
            }

            $cart->number_of_products -= $cartProduct->quantity;
            $cart->final_price -= $product->price * $cartProduct->quantity;

            $cartProduct->delete();
        }

        $cart->save();

        return response()->json(['message' => 'Cart deleted', 'number_of_products' => $cart->number_of_products, 'final_price' => $cart->final_price]);
    }

    public function index(): JsonResponse
    {
        $userId = Auth::user()->id;
        $cart = Cart::where('user_id', $userId)->first();

        if (!$cart) {
            return response()->json(['message' => 'Cart not found for this user'], 404);
        }

        $products = $cart->products()->with(['images' => function ($query) {
            $query->where('front_img', true)->limit(1);
        }])->get();

        $productsWithDetails = $products->map(function ($product) {
            $image = $product->images->first();
            return [
                'id' => $product->id,
                'name' => $product->name,
                'price' => $product->price,
                'quantity' => $product->pivot->quantity,
                'image' => $image ? [
                    'alt' => $image->alt,
                    'path' => $image->path,
                ] : null,
            ];
        });

        $totalPrice = $cart->price;

        return response()->json([
            'products' => $productsWithDetails,
            'total_price' => $totalPrice,
        ]);
    }

    public function updateProductQuantity(Request $request, $id)
    {
        $userId = $request->user()->id;

        $cart = Cart::where('user_id', $userId)->first();

        if (!$cart) {
            return response()->json(['error' => 'Cart not found for the user'], 404);
        }

        $cartProduct = CartProduct::where('cart_id', $cart->id)
            ->where('product_id', $id)
            ->first();

        if (!$cartProduct) {
            return response()->json(['error' => 'Product not found in cart'], 404);
        }

        $product = Product::find($id);

        if (!$product) {
            return response()->json(['error' => 'Product not found'], 404);
        }

        if ($request->change === 'increase') {
            $cartProduct->quantity += 1;
            $cart->number_of_products += 1;
            $cart->price += $product->price;
        } elseif ($request->change === 'decrease') {
            if ($cartProduct->quantity > 1) {
                $cartProduct->quantity -= 1;
                $cart->number_of_products -= 1;
                $cart->price -= $product->price;
            } else {
                return response()->json(['error' => 'Quantity cannot be decreased further'], 400);
            }
        }

        $cartProduct->save();
        $cart->save();

        return response()->json(['message' => 'Quantity updated successfully', 'quantity' => $cartProduct->quantity, 'number_of_products' => $cart->number_of_products, 'final_price' => $cart->final_price]);
    }



}
