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
        $userId = Auth::user()->id;
        $cart = Cart::where('user_id', $userId)->first();
        $product = Product::find($id);

        if(!$cart){
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
        }
        else{
            $cart->number_of_products = ($cart->number_of_products) + 1;
            $cart->price = ($cart->price) + $product->price;
            $cart->save();

            $productAlreadyInCart = CartProduct::where('cart_id', $cart->id)->where('product_id', $id)->first();
            if($productAlreadyInCart){
                $productAlreadyInCart->quantity = ($productAlreadyInCart->quantity) + 1;
                $productAlreadyInCart->save();
            }
            else{
                $newProductInCart = CartProduct::create([
                    'cart_id' => $cart->id,
                    'product_id' => $product->id,
                    'price' => $product->price
                ]);
            }
            return response()->json(['success' => 'Successfully added'], 200);
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

    public function destroy($id){
        $cart = Cart::find($id);
        if (!$cart) {
            return response()->json(['message' => 'Cart not found'], 404);
        }
        Cart::destroy($id);
        return response()->json(['message' => 'Cart deleted']);
    }
}
