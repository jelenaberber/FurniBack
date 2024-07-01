<?php

namespace App\Http\Controllers;

use App\Models\Image;
use App\Models\Product;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $query = Product::query();

        if ($request->has('category') && $request->get('category') != 0) {
            $query->where('category_id', $request->input('category'));
        }

        if ($request->has('sort')) {
            $sortDirection = $request->input('sort');
            if ($sortDirection === 'asc') {
                $query->orderBy('price');
            } elseif ($sortDirection === 'desc') {
                $query->orderByDesc('price');
            }
        }

        $products = $query->get()->map(function ($product) {
            $product->image = $product->images()->where('front_img', 1)->select('path', 'alt')->first();
            return $product;
        });

        return response()->json($products);
    }



    public function show($id): JsonResponse
    {
        $product = Product::find($id);
        if (!$product) {
            return response()->json(['message' => 'Product not found'], 404);
        }
        return response()->json($product);
    }

    public function getAllProductsAdmin(): JsonResponse
    {
        $products = Product::leftJoin('categories', 'products.category_id', '=', 'categories.id')
            ->select('products.id', 'products.name', 'products.price', 'products.available', 'categories.name as category')
            ->get();

        return response()->json($products);
    }
    public function store(Request $request): JsonResponse
    {
        $alreadyExists = Product::where('name', $request->name)->exists();
        if($alreadyExists){
            return response()->json([
                'status' => 'error',
                'message' => 'product with this name already exists.',
            ], 401);
        }
        $product = Product::create([
            'name' => $request->name,
            'category_id' => $request->category_id,
            'description' => $request->description,
            'price' => $request->price
        ]);

        Image::create([
            'product_id' => $product->id,
            'path' => 'Nordic-chair.jpg',
            'alt' => $request->name
        ]);

        return response()->json([
            'status' => 'created',
            'message' => 'Successfully added new product',
            'product' => $product
        ], 201);
    }
    public function destroy($id)
    {
        $product = Product::find($id);
        if (!$product) {
            return response()->json(['message' => 'Product not found'], 404);
        }

        $product->delete();
        return response()->json(['message' => 'Product deleted']);
    }

    public function update(Request $request, $id): JsonResponse
    {
        $product = Product::find($id);

        if (!$product) {
            return response()->json(['message' => 'Product not found'], 404);
        }

        $alreadyExists = Product::where('name', $request->name)
            ->where('id', '!=', $id)
            ->exists();

        if ($alreadyExists) {
            return response()->json([
                'status' => 'error',
                'message' => 'Product with this name already exists.',
            ], 401);
        }

        $product->update([
            'name' => $request->name,
            'category_id' => $request->category_id,
            'description' => $request->description,
            'price' => $request->price
        ]);
        return response()->json([
            'status' => 'updated',
            'message' => 'Successfully changed product',
            'product' => $product
        ], 200);
    }

    public function changeAvailability($id): JsonResponse
    {
        $product = Product::findOrFail($id);

        if ($product->available == 1) {
            $product->available = 0;
        } elseif ($product->available == 0) {
            $product->available = 1;
        }

        $product->save();

        return response()->json(['message' => 'Availability changed successfully']);
    }

}
