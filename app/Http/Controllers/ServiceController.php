<?php

namespace App\Http\Controllers;

use App\Models\Service;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use PHPOpenSourceSaver\JWTAuth\Facades\JWTAuth;

class ServiceController extends Controller
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

        $service = Service::create([
            'title' => $request->title,
            'img' => $request->img,
            'description' => $request->description
        ]);

        return response()->json($service, 201);
    }


    public function index(Request $request): JsonResponse
    {
        $services = Service::get();

        return response()->json($services);
    }

    public function destroy($id): JsonResponse
    {
        $service = Service::find($id);
        if (!$service) {
            return response()->json(['service' => 'Service not found'], 404);
        }

        $service->delete();
        return response()->json(['service' => 'Service deleted']);
    }

}
