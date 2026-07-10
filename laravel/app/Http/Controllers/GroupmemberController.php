<?php

namespace App\Http\Controllers;

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

        public function destroy($id){
            $member = Groupmember::find($id);

            if (!$member) {
                return response()->json('Group member not found', 404);
            }

            $member->delete();

            return response()->json(null, 204);
        }
}

