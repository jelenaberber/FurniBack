<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use PHPOpenSourceSaver\JWTAuth\Facades\JWTAuth;

class EmployeeController extends Controller
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

        $employee = Employee::create([
            'name' => $request->name,
            'img' => $request->img,
            'job' => $request->job,
            'description'=> $request->description
        ]);

        return response()->json($employee, 201);
    }


    public function index(Request $request): JsonResponse
    {
        $employee = Employee::get();

        return response()->json($employee);
    }

    public function destroy($id): JsonResponse
    {
        $employee = Employee::find($id);
        if (!$employee) {
            return response()->json(['employee' => 'Employee not found'], 404);
        }

        $employee->delete();
        return response()->json(['employee' => 'Employee deleted']);
    }

}
