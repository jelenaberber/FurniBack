<?php

namespace App\Http\Controllers;

use App\Models\Testimony;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use PHPOpenSourceSaver\JWTAuth\Facades\JWTAuth;

class TestimonyController extends Controller
{
    public function store(Request $request): JsonResponse
    {
        $userId = null;

        if ($request->hasHeader('Authorization')) {
            try {
                if ($user = JWTAuth::parseToken()->authenticate()) {
                    $userId = $user->id;
                }
            } catch (\Tymon\JWTAuth\Exceptions\JWTException $e) {
                $userId = null;
            }
        }

        $testimony = Testimony::create([
            'name' => $request->name,
            'img' => $request->img,
            'position' => $request->position,
            'testimony'=> $request->testimony
        ]);

        return response()->json($testimony, 201);
    }


    public function index(Request $request): JsonResponse
    {
        $testimony = Testimony::get();

        return response()->json($testimony);
    }

    public function destroy($id): JsonResponse
    {
        $testimony = Testimony::find($id);
        if (!$testimony) {
            return response()->json(['testimony' => 'Testimony not found'], 404);
        }

        $testimony->delete();
        return response()->json(['testimony' => 'Testimony deleted']);
    }

}
