<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class PasswordResetController extends Controller
{
    public function resetPassword(Request $request)
    {
        $email = trim($request->email ?? '');
        $birth_date = trim($request->birth_date ?? '');
        $new_password = $request->new_password ?? '';

        // 必須チェック
        if ($email === '' || $birth_date === '' || $new_password === '') {

            return response()->json([
                'status' => 'error',
                'message' => '入力してください'
            ], 400);
        }

        // 生年月日8桁チェック
        if (strlen($birth_date) !== 8) {

            return response()->json([
                'status' => 'error',
                'message' => '生年月日は8桁で入力してください'
            ], 400);
        }

        // パスワード8文字以上
        if (strlen($new_password) < 8) {

            return response()->json([
                'status' => 'error',
                'message' => 'パスワードは8文字以上です'
            ], 400);
        }

        // 20051201 → 2005-12-01
        $birth_date =
            substr($birth_date, 0, 4) . '-' .
            substr($birth_date, 4, 2) . '-' .
            substr($birth_date, 6, 2);

        // ユーザー検索
        $user = DB::table('users')
            ->where('email', $email)
            ->where('birth_date', $birth_date)
            ->first();

        // 一致しない
        if (!$user) {

            return response()->json([
                'status' => 'error',
                'message' => 'ユーザー情報が一致しません'
            ], 404);
        }

        // パスワード更新
        DB::table('users')
            ->where('id', $user->id)
            ->update([
                'password' => Hash::make($new_password)
            ]);

        return response()->json([
            'status' => 'success',
            'message' => 'パスワードを変更しました'
        ]);
    }
}