<?php

namespace App\Http\Controllers;

use App\Models\Grouptask;
use Illuminate\Http\Request;

class GroupTaskController extends Controller
{

    /**
     * グループタスク一覧
     */
    public function index(Request $request)
    {
        $query = Grouptask::query();


        if($request->filled('group_id')){

            $query->where(
                'group_id',
                $request->group_id
            );

        }


        $tasks = $query
            ->orderBy('id','desc')
            ->get();


        return response()->json([

            'status'=>'success',

            'tasks'=>$tasks

        ]);
    }



    /**
     * 詳細取得
     */
    public function show($id)
    {

        $task = Grouptask::find($id);


        if(!$task){

            return response()->json([

                'status'=>'error',

                'message'=>'タスクが存在しません'

            ],404);

        }


        return response()->json([

            'status'=>'success',

            'task'=>$task

        ]);

    }



    /**
     * 登録
     */
    public function store(Request $request)
    {


        $title = trim(
            $request->title ?? ''
        );


        if($title === ''){

            return response()->json([

                'status'=>'error',

                'message'=>'グループタスク名を入力してください'

            ],400);

        }



        $weekDays = $request->week_days ?? [];


        if(count($weekDays)==0){

            return response()->json([

                'status'=>'error',

                'message'=>'曜日を選択してください'

            ],400);

        }



        $startTime =
            trim($request->start_time ?? '');



        if($startTime === ''){

            return response()->json([

                'status'=>'error',

                'message'=>'開始時間を入力してください'

            ],400);

        }




        $requiredMinutes =
            (int)$request->required_minutes;



        if($requiredMinutes <=0){

            return response()->json([

                'status'=>'error',

                'message'=>'所要時間を入力してください'

            ],400);

        }




        $startDate =
            $request->start_date;



        $endDate =
            $request->end_date;



        if($startDate){

            if($startDate < date('Y-m-d')){

                return response()->json([

                    'status'=>'error',

                    'message'=>'開始日は今日以降にしてください'

                ],400);

            }

        }




        if(
            $endDate &&
            $startDate &&
            $endDate < $startDate
        ){

            return response()->json([

                'status'=>'error',

                'message'=>'終了日は開始日以降にしてください'

            ],400);

        }





        /*
        優先度変換
        */

        $priority = match(
            $request->priority
        ){

            '高'=>'high',

            '中'=>'middle',

            '低'=>'low',

            default=>null

        };






        $task = Grouptask::create([


            'group_id'=>
                $request->group_id,


            'user_id'=>
                $request->user_id,



            'title'=>
                $title,



            'content'=>
                $request->content ?? '',



            // 月,火,水形式
            'week_days'=>
                implode(',',$weekDays),



            'start_time'=>
                $startTime,



            'required_minutes'=>
                $requiredMinutes,



            'priority'=>
                $priority,



            'color'=>
                $request->color ?? '#0d6efd',



            'period'=>
                $request->period ?? 'weekly',



            'notification_enabled'=>
                $request->notification_enabled ?? 1,



            'start_date'=>
                $startDate,



            'end_date'=>
                $endDate,



            'status'=>
                'active'


        ]);




        return response()->json([

            'status'=>'success',

            'message'=>'グループタスクを登録しました',

            'task'=>$task

        ],201);

    }





    /**
     * 削除
     */
    public function destroy($id)
    {

        $task =
            Grouptask::find($id);



        if(!$task){

            return response()->json([

                'status'=>'error',

                'message'=>'タスクが存在しません'

            ],404);

        }



        $task->delete();



        return response()->json([

            'status'=>'success',

            'message'=>'削除しました'

        ]);

    }


}