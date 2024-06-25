<?php

namespace App\Http\Controllers;

use App\Models\Message;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use PHPOpenSourceSaver\JWTAuth\Facades\JWTAuth;

class MessageController extends Controller
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

        $message = Message::create([
            'user_id' => $userId,
            'email' => $request->email,
            'message' => $request->message
        ]);

        return response()->json($message, 201);
    }


    public function index(Request $request): JsonResponse
    {
        $message = Message::get();

        return response()->json($message);
    }

    public function destroy($id): JsonResponse
    {
        $message = Message::find($id);
        if (!$message) {
            return response()->json(['message' => 'Message not found'], 404);
        }

        $message->delete();
        return response()->json(['message' => 'Message deleted']);
    }

}
