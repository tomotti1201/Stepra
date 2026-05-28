<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class SignupController extends Controller
{
    public function signup(Request $request)
    {
        $name = trim($request->name ?? '');
        $email = trim($request->email ?? '');
        $password = $request->password ?? '';

        if ($name === '' || $email === '' || $password === '') {
            return response()->json([
                'status' => 'error',
                'message' => 'name, email, password are required',
            ], 400);
        }

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            return response()->json([
                'status' => 'error',
                'message' => 'Invalid email format',
            ], 400);
        }

        if (strlen($password) < 8) {
            return response()->json([
                'status' => 'error',
                'message' => 'Password must be at least 8 characters',
            ], 400);
        }

        $exists = DB::table('users')
            ->where('email', $email)
            ->exists();

        if ($exists) {
            return response()->json([
                'status' => 'error',
                'message' => 'Email already exists',
            ], 409);
        }

        $id = DB::table('users')->insertGetId([
            'name' => $name,
            'email' => $email,
            'password' => Hash::make($password),
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'User registered successfully',
            'user' => [
                'id' => $id,
                'name' => $name,
                'email' => $email,
            ]
        ], 201);
    }
}