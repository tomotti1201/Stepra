<?php

namespace App\Http\Controllers;

use App\Models\Group;
use App\Models\Groupmember;
use Illuminate\Http\Request;

class GroupController extends Controller
{
     //グループ一覧取得
    public function index()
    {
        $groups = Group::all();

        return response()->json($groups);
    }

     //グループ作成
    public function store(Request $request)
    {
        $group = Group::create([
            'name' => $request->name,
            'icon' => $request->icon,
            'invite_code' => $request->invite_code,
            'description' => $request->description,
            'ispublic' => $request->ispublic,
        ]);

        return response()->json($group, 201);
    }
     //グループ参加
    public function join(Request $request)
    {
        $member = Groupmember::create([
            'group_id' => $request->group_id,
            'user_id' => $request->user_id,
            'notification_enabled' => 1,
            'role' => 'member',
        ]);

        return response()->json($member, 201);
    }
}