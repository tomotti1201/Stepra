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
        $validated = $request->validate([
            'group_id' => ['required', 'integer'],
            'user_id' => ['required', 'integer'],
            'role' => ['nullable', 'in:admin,member'],
        ]);

        $member = Groupmember::create([
            'group_id' => $validated['group_id'],
            'user_id' => $validated['user_id'],
            'notification_enabled' => 1,
            'role' => $validated['role'] ?? 'member',
        ]);

        return response()->json($member, 201);
    }

    public function update(Request $request, $id)
    {
        $group = Group::findOrFail($id);
        $requestUserId = $request->request_user_id;

        if (!$requestUserId) {
            return response()->json([
                'status' => 'error',
                'message' => 'request_user_id is required',
            ], 400);
        }

        $requestMember = Groupmember::where('group_id', $group->id)
            ->where('user_id', $requestUserId)
            ->first();

        if (!$requestMember || $requestMember->role !== 'admin') {
            return response()->json([
                'status' => 'error',
                'message' => 'Only admins can update groups',
            ], 403);
        }

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
