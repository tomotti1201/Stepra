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
        $birth_date = trim($request->birth_date ?? '');
        $password = $request->password ?? '';

        if ($name === '' || $email === '' || $birth_date === '' || $password === '') {
    return response()->json([
        'status' => 'error',
        'message' => '必須項目を入力してください',
    ], 400);
}

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    return response()->json([
        'status' => 'error',
        'message' => 'メールアドレスの形式が正しくありません',
    ], 400);
}

if (strlen($birth_date) !== 8) {
    return response()->json([
        'status' => 'error',
        'message' => '生年月日は8桁で入力してください',
    ], 400);
}

if (strlen($password) < 8) {
    return response()->json([
        'status' => 'error',
        'message' => 'パスワードは8文字以上で入力してください',
    ], 400);
}

        $exists = DB::table('users')
            ->where('email', $email)
            ->exists();

        if ($exists) {
            return response()->json([
                'status' => 'error',
                'message' => 'このメールアドレスは既に登録されています',
            ], 409);
        }

        $id = DB::table('users')->insertGetId([
            'name' => $name,
            'email' => $email,
            'birth_date' => $birth_date,
            'password' => Hash::make($password),
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'User registered successfully',
            'user' => [
                'id' => $id,
                'name' => $name,
                'email' => $email,
                'birth_date' => $birth_date,
            ]
        ], 201);
    }
}