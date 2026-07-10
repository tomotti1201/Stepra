<?php

namespace App\Http\Controllers;

use App\Models\Group;
use App\Models\Groupmember;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class GroupController extends Controller
{
    public function index()
    {
        $groups = Group::all();

        return response()->json($groups);
    }

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

    public function update(Request $request, $id)
    {
        $group = Group::findOrFail($id);

        $group->update($request->only([
            'name',
            'icon',
            'description',
            'is_public',
        ]));

        return response()->json($group);
    }

    public function destroy($id)
    {
        $group = Group::findOrFail($id);
        $group->delete();

        return response()->json(null, 204);
    }
}
