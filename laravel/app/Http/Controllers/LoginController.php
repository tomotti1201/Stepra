<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class LoginController extends Controller
{
    public function login(Request $request)
    {
        $email = trim($request->email ?? '');
        $password = $request->password ?? '';

        // 入力チェック
        if ($email === '' || $password === '') {
            return response()->json([
                'status' => 'error',
                'message' => 'ユーザー名とパスワードを入力してください'
            ], 400);
        }

        // users テーブルから検索
        $user = DB::table('users')
            ->where('email', $email)
            ->first();

        // ユーザー存在確認
        if (!$user) {
            return response()->json([
                'status' => 'error',
                'message' => 'メールアドレスまたはパスワードが違います'
            ], 401);
        }

        // パスワード確認
        if (!Hash::check($password, $user->password)) {
            return response()->json([
                'status' => 'error',
                'message' => 'メールアドレスまたはパスワードが違います'
            ], 401);
        }

        // ログイン成功
        return response()->json([
            'status' => 'success',
            'message' => 'ログイン成功',
            'user' => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email
            ]
        ]);
    }
}