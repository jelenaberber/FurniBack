<?php

namespace App\Http\Controllers;

use App\Models\Message;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class MessageController extends Controller
{
    public function store(Request $request): JsonResponse
    {
        $message = Message::create([
            'user_id' => $request->user_id,
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
