<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class LoginController extends Controller
{
    public function login(Request $request)
    {
        $email = trim($request->email ?? '');
        $password = $request->password ?? '';

        if ($email === '' || $password === '') {
            return response()->json([
                'status' => 'error',
                'message' => 'メールアドレスとパスワードを入力してください',
            ], 400);
        }

        $user = User::where('email', $email)->first();

        if (!$user || !Hash::check($password, $user->password)) {
            return response()->json([
                'status' => 'error',
                'message' => 'メールアドレスまたはパスワードが違います',
            ], 401);
        }

        $request->session()->put('user_id', $user->id);

        return response()->json([
            'status' => 'success',
            'message' => 'ログイン成功',
            'user' => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
            ],
        ]);
    }

    public function currentUser(Request $request)
    {
        $userId = $request->session()->get('user_id');

        if (!$userId) {
            return response()->json([
                'status' => 'error',
                'message' => 'Unauthenticated',
            ], 401);
        }

        $user = User::find($userId);

        if (!$user) {
            return response()->json([
                'status' => 'error',
                'message' => 'User not found',
            ], 404);
        }

        return response()->json([
            'status' => 'success',
            'user' => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
            ],
        ]);
    }
}
