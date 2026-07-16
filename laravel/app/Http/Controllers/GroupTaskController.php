<?php

namespace App\Http\Controllers;

use App\Models\Grouptask;
use App\Models\GroupSchedule;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class GroupTaskController extends Controller
{

public function store(Request $request)
{
    $title = trim($request->title ?? '');

    if ($title === '') {
        return response()->json([
            'status'=>'error',
            'message'=>'目標名を入力してください'
        ],400);
    }

    $groupId = $request->group_id;

    if(!$groupId){
        return response()->json([
            'status'=>'error',
            'message'=>'グループIDがありません'
        ],400);
    }

    $weekDays = $request->week_days ?? [];

    if(count($weekDays) === 0){
        return response()->json([
            'status'=>'error',
            'message'=>'曜日を選択してください'
        ],400);
    }

    $start_time = trim($request->start_time ?? '');

    if($start_time === ''){
        return response()->json([
            'status'=>'error',
            'message'=>'開始時間を入力してください'
        ],400);
    }

    $requiredMinutes = (int)($request->required_minutes ?? 0);

    if($requiredMinutes <= 0){
        return response()->json([
            'status'=>'error',
            'message'=>'所要時間を入力してください'
        ],400);
    }

    $startDate = trim($request->start_date ?? '');
    $endDate = trim($request->end_date ?? '');

    if($startDate === ''){
        return response()->json([
            'status'=>'error',
            'message'=>'開始日を入力してください'
        ],400);
    }

    $today = date('Y-m-d');

    if($startDate < $today){
        return response()->json([
            'status'=>'error',
            'message'=>'開始日は今日以降を指定してください'
        ],400);
    }

    if($endDate !== '' && $endDate < $startDate){
        return response()->json([
            'status'=>'error',
            'message'=>'終了日は開始日以降を指定してください'
        ],400);
    }

    $endDate = $endDate === '' ? null : $endDate;

    // 優先度変換
    $priority = match($request->priority){
        '高'=>'high',
        '中'=>'middle',
        '低'=>'low',
        default=>null
    };

    // 曜日を数値化
    $weekDayMap = [
        '月'=>1,
        '火'=>2,
        '水'=>3,
        '木'=>4,
        '金'=>5,
        '土'=>6,
        '日'=>7
    ];

    $weekDays = array_map(
        fn($day)=>$weekDayMap[$day],
        $weekDays
    );

    /*
    |--------------------------------------------------------------------------
    | グループタスク登録
    |--------------------------------------------------------------------------
    */

    $task = Grouptask::create([
        'group_id'=>$groupId,
        'user_id'=>$request->user_id ?? Auth::id(),
        'title'=>$title,
        'content'=>$request->content ?? '',
        'week_days'=>json_encode($weekDays),
        'start_time'=>$start_time,
        'required_minutes'=>$requiredMinutes,
        'priority'=>$priority,
        'color'=>$request->color ?? '#0d6efd',
        'period'=>$request->period ?? 'weekly',
        'notification_enabled'=>1,
        'start_date'=>$startDate,
        'end_date'=>$endDate,
        'status'=>'active',
    ]);

    /*
    |--------------------------------------------------------------------------
    | グループスケジュール生成
    |--------------------------------------------------------------------------
    */

    $start = Carbon::parse($startDate);

    if($endDate){
        $endLimit = Carbon::parse($endDate);
    }else{
        // 1か月分
        $endLimit = $start->copy()->addMonth();
    }

    $date = $start->copy();

    while($date->lte($endLimit)){

        // 月=1 火=2 ... 日=7
        $dow = $date->dayOfWeekIso;

        if(in_array($dow,$weekDays)){

            GroupSchedule::create([
                'group_task_id'=>$task->id,
                'group_id'=>$groupId,
                'user_id'=>$task->user_id,
                'scheduled_date'=>$date->format('Y-m-d'),
                'title'=>$task->title,
                'content'=>$task->content,
                'week_days'=>$task->week_days,
                'start_time'=>$task->start_time,
                'required_minutes'=>$task->required_minutes,
                'priority'=>$task->priority,
                'color'=>$task->color,
                'period'=>$task->period,
                'notification_enabled'=>$task->notification_enabled,
                'start_date'=>$task->start_date,
                'end_date'=>$task->end_date,
                'status'=>$task->status,
            ]);
        }

        $date->addDay();
    }

    return response()->json([
        'status'=>'success',
        'message'=>'グループ目標を登録しました',
        'task'=>$task
    ],201);
}

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

        if($request->filled('user_id')){

            $query->where(
                'user_id',
                $request->user_id
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

        'task'=>[
            'id'=>$task->id,

            'group_id'=>$task->group_id,

            'title'=>$task->title,

            'content'=>$task->content,

            'week_days'=>$task->week_days,

            'start_time'=>$task->start_time,

            'required_minutes'=>$task->required_minutes,

            'priority'=>$task->priority,

            'color'=>$task->color,

            'period'=>$task->period,


            // ★通常タスクと同じ形式
            'start_date'=>$task->start_date
                ? $task->start_date->format('Y-m-d')
                : null,


            'end_date'=>$task->end_date
                ? $task->end_date->format('Y-m-d')
                : null,

            'status'=>$task->status
        ]
    ]);
}

public function update(Request $request, $id)
{
    $task = Grouptask::find($id);

    if (!$task) {
        return response()->json([
            'status'=>'error',
            'message'=>'グループタスクが見つかりません'
        ],404);
    }


    $title = trim($request->title ?? '');

    if ($title === '') {
        return response()->json([
            'status'=>'error',
            'message'=>'タスク名を入力してください'
        ],400);
    }


    $weekDays = $request->week_days ?? [];

    if(count($weekDays) === 0){
        return response()->json([
            'status'=>'error',
            'message'=>'曜日を選択してください'
        ],400);
    }


    $start_time = trim($request->start_time ?? '');

    if($start_time === ''){
        return response()->json([
            'status'=>'error',
            'message'=>'開始時間を入力してください'
        ],400);
    }


    $duration_hours = trim($request->duration_hours ?? '');

    $duration_minutes = trim($request->duration_minutes ?? '');


    if(
        $duration_hours === '' ||
        $duration_minutes === ''
    ){
        return response()->json([
            'status'=>'error',
            'message'=>'所要時間を入力してください'
        ],400);
    }


    $requiredMinutes =
        ((int)$duration_hours * 60)
        +
        (int)$duration_minutes;


    if($requiredMinutes <= 0){

        return response()->json([
            'status'=>'error',
            'message'=>'所要時間を入力してください'
        ],400);

    }


    // 優先度変換
    $priority = match($request->priority){

        '高'=>'high',

        '中'=>'middle',

        '低'=>'low',

        default=>null

    };


    // 曜日変換
    $weekDayMap = [

        '月'=>1,

        '火'=>2,

        '水'=>3,

        '木'=>4,

        '金'=>5,

        '土'=>6,

        '日'=>7

    ];


    $weekDays = array_map(
        fn($d)=>$weekDayMap[$d],
        $weekDays
    );


    $startDate = trim($request->start_date ?? '');

    $endDate = trim($request->end_date ?? '');


    if($startDate === ''){

        return response()->json([
            'status'=>'error',
            'message'=>'開始日を入力してください'
        ],400);

    }


    $endDate =
        $endDate === ''
        ? null
        : $endDate;


    if(
        !empty($endDate)
        &&
        strtotime($endDate) < strtotime($startDate)
    ){

        return response()->json([
            'status'=>'error',
            'message'=>'終了日は開始日以降を指定してください'
        ],400);

    }



    /*
    |--------------------------------------------------------------------------
    | グループタスク更新
    |--------------------------------------------------------------------------
    */

    $task->update([

    'title'=>$title,

    'week_days'=>json_encode($weekDays),

    'start_time'=>$start_time,

    'required_minutes'=>$requiredMinutes,

    'priority'=>$priority,

    'color'=>$request->color ?? $task->color,

    'period'=>$task->period,

    'start_date'=>$startDate,

    'end_date'=>$endDate,

]);



    /*
    |--------------------------------------------------------------------------
    | 未来スケジュール削除
    |--------------------------------------------------------------------------
    */

    $today = now()->toDateString();


    GroupSchedule::where(
            'group_task_id',
            $task->id
        )
        ->where(
            'scheduled_date',
            '>=',
            $today
        )
        ->delete();



    /*
    |--------------------------------------------------------------------------
    | スケジュール再生成
    |--------------------------------------------------------------------------
    */


    // 修正：開始日から生成
    $start = Carbon::parse($startDate);


    if($endDate){

        $endLimit = Carbon::parse($endDate);

    }else{

        $endLimit = (clone $start)->addMonth();

    }


    $date = $start->copy();



    while($date->lte($endLimit)){


        $dow = $date->dayOfWeekIso;


        if(in_array($dow,$weekDays)){


            GroupSchedule::create([

                'group_task_id'=>$task->id,

                'group_id'=>$task->group_id,

                'user_id'=>$task->user_id,

                'scheduled_date'=>$date->format('Y-m-d'),

                'title'=>$title,

                'content'=>$request->content ?? '',

                'week_days'=>json_encode($weekDays),

                'start_time'=>$start_time,

                'required_minutes'=>$requiredMinutes,

                'priority'=>$priority,

                'color'=>$request->color ?? $task->color,

                'period'=>$task->period,

                'notification_enabled'=>1,

                'end_date'=>$endDate,

                'status'=>'active'

            ]);

        }


        $date->addDay();

    }


    $task->refresh();


    return response()->json([

        'status'=>'success',

        'message'=>'更新しました',

        'task'=>$task

    ]);

}
    /**
     * 削除
     */
    public function destroy($id)
{
    $task = Grouptask::find($id);


    if(!$task){

        return response()->json([
            'status'=>'error',
            'message'=>'タスクが存在しません'
        ],404);

    }


    GroupSchedule::where(
        'group_task_id',
        $task->id
    )->delete();


    $task->delete();


    return response()->json([
    'status'=>'success',
    'message'=>'削除しました',
    'group_id'=>$task->group_id
]);
}

}
