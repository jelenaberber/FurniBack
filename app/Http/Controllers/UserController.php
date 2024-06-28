<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function register(Request $request)
    {
        $emailExists = User::where('email', $request->email)->first();

        if ($emailExists) {
            return response()->json([
                'status' => 'error',
                'message' => 'User with this email already exist',
            ], 404);
        }

        $usernameExists = User::where('username', $request->username)->first();

        if ($usernameExists) {
            return response()->json([
                'status' => 'error',
                'message' => 'User with this username already exist',
            ], 404);
        }
        $defaultProfileImg = 'avatar.jpg';
        $user = User::create([
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'username' => $request->username,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'profile_img' => $defaultProfileImg,
            'role_id' => '1'
        ]);

        $credentials = $request->only('email', 'password');
        $token = Auth::guard('api')->attempt($credentials);

        return response()->json([
            'status' => 'success',
            'message' => 'User created successfully',
            'user' => $user,
            'authorisation' => [
                'token' => $token,
                'type' => 'bearer',
            ]
        ], 201);
    }

    public function login(Request $request)
    {
        $user = User::where('email', $request->email)->first();

        if (!$user) {
            return response()->json([
                'status' => 'error',
                'message' => 'User with this email does not exist',
            ], 404);
        }
        if (!Hash::check($request->password, $user->password)) {
            return response()->json([
                'status' => 'error',
                'message' => 'Incorrect password',
            ], 401);
        }

        $credentials = $request->only('email', 'password');
        $token = Auth::guard('api')->attempt($credentials);

        if (!$token) {
            return response()->json([
                'status' => 'error',
                'message' => 'Unauthorized',
            ], 401);
        }

        $user = Auth::guard('api')->user();
        $role_id = $user->role->id;

        $customClaims = [
            'role_id' => $role_id
        ];

        $token = Auth::guard('api')->claims($customClaims)->attempt($credentials);

        return response()->json([
            'status' => 'success',
            'user' => $user,
            'authorisation' => [
                'token' => $token,
                'type' => 'bearer',
            ]
        ]);
    }

    public function logout()
    {
        Auth::logout();
        return response()->json([
            'status' => 'success',
            'message' => 'Successfully logged out',
        ]);
    }
    public function refresh()
    {
        return response()->json([
            'status' => 'success',
            'user' => Auth::user(),
            'authorisation' => [
                'token' => Auth::refresh(),
                'type' => 'bearer',
            ]
        ]);
    }

    public function index(): JsonResponse
    {
        $users = User::with('role:id,name')->get([
            'id', 'first_name', 'last_name', 'username', 'email', 'role_id'
        ]);

        return response()->json($users);
    }

    public function show($id): JsonResponse
    {
        $user = User::find($id);
        if (!$user) {
            return response()->json(['message' => 'User not found'], 404);
        }
        return response()->json($user);
    }

    public function destroy($id)
    {
        $user = User::find($id);
        if (!$user) {
            return response()->json(['message' => 'User not found'], 404);
        }

        $user->delete();
        return response()->json(['message' => 'User deleted']);
    }

    public function update(Request $request, $id): JsonResponse
    {
        $user = User::find($id);

        if (!$user) {
            return response()->json(['message' => 'User not found'], 404);
        }

        $user->update([
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'username' => $request->username,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'profile_img' => $request -> profile_img
        ]);
        return response()->json($user);
    }

    public function changeRole(Request $request, $id)
    {
        $user = User::find($id);

        if (!$user) {
            return response()->json(['message' => 'User not found'], 404);
        }

        $user->role_id = $request->role_id;

        $user->save();

        return response()->json($user);
    }
}
