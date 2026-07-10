<?php

namespace App\Http\Controllers;

use App\Models\Group;
use App\Models\Groupmember;
use Illuminate\Http\Request;

class GroupmemberController extends Controller{
        public function index(Request $request){
            $query = Groupmember::query()
                ->leftJoin('users', 'group_members.user_id', '=', 'users.id')
                ->select(
                    'group_members.*',
                    'users.name',
                    'users.icon',
                    'users.profile_text'
                );

         if ($request->filled('group_id')) {
            $query->where('group_members.group_id', $request->group_id);
        }

        $memberlist = $query
            ->orderby('group_members.id','desc')
            ->get();

        return response()->json($memberlist);
        }
        public function show($id){
           $member = Groupmember::find($id);

             if (!$member) {
            return response()->json('Group member not found', 404);
        }

        return response()->json($member);
            
}

        public function joinByInviteCode(Request $request){
            $validated = $request->validate([
                'invite_code' => ['required', 'string'],
                'user_id' => ['required', 'integer'],
            ]);

            $group = Group::where('invite_code', strtoupper(trim($validated['invite_code'])))->first();

            if (!$group) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Invite code not found',
                ], 404);
            }

            $member = Groupmember::firstOrCreate(
                [
                    'group_id' => $group->id,
                    'user_id' => $validated['user_id'],
                ],
                [
                    'notification_enabled' => 1,
                    'role' => 'member',
                ]
            );

            return response()->json([
                'status' => 'success',
                'group' => $group,
                'member' => $member,
                'already_joined' => !$member->wasRecentlyCreated,
            ], $member->wasRecentlyCreated ? 201 : 200);
        }

        public function destroy($id){
            $member = Groupmember::find($id);

            if (!$member) {
                return response()->json('Group member not found', 404);
            }

            $requestUserId = request('request_user_id');

            if (!$requestUserId) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'request_user_id is required',
                ], 400);
            }

            $requestMember = Groupmember::where('group_id', $member->group_id)
                ->where('user_id', $requestUserId)
                ->first();

            if (!$requestMember || $requestMember->role !== 'admin') {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Only admins can delete group members',
                ], 403);
            }

            $member->delete();

            return response()->json(null, 204);
        }
}

