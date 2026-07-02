<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;



class SettingController extends Controller
{
    public function user($id)
    {
        $user = User::find($id);

        if (!$user) {
            return response()->json([
                'status' => 'error',
                'message' => 'ユーザーが見つかりません'
            ],404);
        }

        return response()->json([
            'status' => 'success',
            'user'=>[
                'id'=>$user->id,
                'name'=>$user->name,
                'email'=>$user->email
            ]
        ]);
    }
    public function updateName(Request $request,$id)
    {

        $name = trim($request->name ?? '');

        if($name === ''){

            return response()->json([

                'status'=>'error',

                'message'=>'ユーザー名を入力してください'

            ],400);

        }

        $user = User::find($id);

        if(!$user){

            return response()->json([

                'status'=>'error',

                'message'=>'ユーザーが見つかりません'

            ],404);

        }

        $user->name = $name;

        $user->save();

        return response()->json([

            'status'=>'success',

            'message'=>'ユーザー名を変更しました'

        ]);

    }
    public function updateMail(Request $request,$id)
    {

        $email = trim($request->email ?? '');

        if($email === ''){

            return response()->json([

                'status'=>'error',

                'message'=>'メールアドレスを入力してください'

            ],400);

        }

        if(!filter_var($email,FILTER_VALIDATE_EMAIL)){

            return response()->json([

                'status'=>'error',

                'message'=>'メールアドレスの形式が正しくありません'

            ],400);

        }

        $exists = User::where('email',$email)
                        ->where('id','!=',$id)
                        ->exists();

        if($exists){

            return response()->json([

                'status'=>'error',

                'message'=>'そのメールアドレスは既に使われています'

            ],400);

        }

        $user = User::find($id);

        if(!$user){

            return response()->json([

                'status'=>'error',

                'message'=>'ユーザーが見つかりません'

            ],404);

        }

        $user->email = $email;

        $user->save();

        return response()->json([

            'status'=>'success',

            'message'=>'メールアドレスを変更しました'

        ]);

    }
    public function checkPassword(Request $request, $id)
    {
        $user = User::find($id);

        if (!$user) {
            return response()->json([
                "status" => "error",
                "message" => "ユーザーが存在しません"
            ],404);
        }

        if (!Hash::check($request->password, $user->password)) {
            return response()->json([
                "status" => "error",
                "message" => "パスワードが違います"
            ],400);
        }

        return response()->json([
            "status" => "success",
            "message" => "一致しました"
        ]);
    }
}