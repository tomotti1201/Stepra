<?php

namespace App\Http\Controllers;

use App\Models\Group;
use App\Models\Groupmember;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

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
            'invite_code' => Str::upper(Str::random(8)),
            'description' => $request->description,
            'is_public' => $request->is_public ?? 0,
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
    //グループ削除
public function destroy($id)
{
    $group = Group::findOrFail($id);
    $group->delete();
    return response()->json(null, 204);
}
}
