<?php

namespace App\Http\Controllers;

use App\Models\ChangePassword;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class ChangePasswordController extends Controller
{
    
    //パスワード変更一覧取得
     
    public function index()
    {
        $passwordResets = ChangePassword::all();

        return response()->json($passwordResets);
    }

    //ログインユーザーのパスワード変更情報取得
    
    public function userChangePassword()
    {
        $userId = Auth::id();

        if (!$userId) {
            return response()->json([
                'message' => 'ログインしてください',
            ], 401);
        }

        $passwordResets = ChangePassword::where('user_id', $userId)
            ->get();

        return response()->json($passwordResets);
    }

    
     //パスワードリセット作成
     
    public function store(Request $request)
    {
        $changePassword = ChangePassword::create([
            'user_id' => $request->user_id,
            'token' => Str::random(64),
            'expires_at' => now()->addHour(),
        ]);

        return response()->json($changePassword, 201);
    }

    
     //create

    public function create()
    {
        return redirect('/change_password');
    }


     //編集画面
    public function edit($id)
    {
        $changePassword = ChangePassword::findOrFail($id);

        return response()->json($changePassword);
    }

    
     //更新
    
    public function update(Request $request, $id)
    {
        $changePassword = ChangePassword::findOrFail($id);

        $changePassword->update($request->all());

        return response()->json($changePassword);
    }

    
    //削除
    
    public function destroy($id)
    {
        $changePassword = ChangePassword::findOrFail($id);

        $changePassword->delete();

        return response()->json([
            'message' => '削除しました',
        ]);
    }
}
