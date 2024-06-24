<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Message;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function store(Request $request): JsonResponse
    {
        $category = Category::create([
            'name' => $request->name,
        ]);

        return response()->json($category, 201);
    }

    public function index(Request $request): JsonResponse
    {
        $categories = Category::get();

        return response()->json($categories);
    }

    public function destroy($id): JsonResponse
    {
        $category = Category::find($id);
        if (!$category) {
            return response()->json(['category' => 'Category not found'], 404);
        }

        $category->delete();
        return response()->json(['category' => 'Category deleted']);
    }

}
