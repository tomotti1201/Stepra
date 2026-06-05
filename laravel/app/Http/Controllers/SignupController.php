<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class SignupController extends Controller
{
    public function signup(Request $request)
    {
        $name = trim($request->name ?? '');
        $email = trim($request->email ?? '');
        $birth_date = trim($request->birth_date ?? '');
        $password = $request->password ?? '';

        if ($name === '' || $email === '' || $birth_date === '' || $password === '') {
            return response()->json([
                'status' => 'error',
                'message' => 'name, email, birth_date, password are required',
            ], 400);
        }

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            return response()->json([
                'status' => 'error',
                'message' => 'Invalid email format',
            ], 400);
        }

        if(strlen($birth_date) !== 8){
            return response() -> json([
                'status' => 'error',
                'message' => 'Birth date must be 8 characters',
            ], 400);
        }

        $birth_date = substr($birth_date,0,4) . '-' . substr($birth_date,4,2) . '-' . substr($birth_date,6,2);

        if (strlen($password) < 8) {
            return response()->json([
                'status' => 'error',
                'message' => 'Password must be at least 8 characters',
            ], 400);
        }

        $exists = User::where('email', $email)->exists();

        if ($exists) {
            return response()->json([
                'status' => 'error',
                'message' => 'Email already exists',
            ], 409);
        }

        $user = User::create([
            'name' => $name,
            'email' => $email,
            'birth_date' => $birth_date,
            'password' => Hash::make($password),
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'User registered successfully',
            'user' => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'birth_date' => $user->birth_date,
            ]
        ], 201);
    }
}