<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $query = Product::query();

        if ($request->has('category')) {
            $query->where('category_id', $request->input('category'));
        }

        if ($request->get('sort') == 'asc') {
            $query->orderBy('price');
        }
        elseif ($request->get('sort') == 'desc') {
            $query->orderByDesc('price');
        }
        $products = $query->get();

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
    public function store(Request $request): JsonResponse
    {
//        $request->validate([
//            'name' => 'required|string|max:40',
//            'category_id' => 'required|exists:categories,id',
//            'description' => 'required|string|max:255',
//            'price' => 'required',
//        ]);
        $product = Product::create([
            'name' => $request->name,
            'category_id' => $request->category_id,
            'description' => $request->description,
            'price' => $request->price
        ]);

        return response()->json($product, 201);
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

        $product->update([
            'name' => $request->name,
            'category_id' => $request->category_id,
            'description' => $request->description,
            'price' => $request->price
        ]);
        return response()->json($product);
    }

}
