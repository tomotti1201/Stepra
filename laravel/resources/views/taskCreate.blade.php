<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <title>目標編集</title>

    <link rel="stylesheet" href="{{ asset('css/taskCreate.css') }}">
</head>

<body>

    <div class="container">

        <div class="title">目標新規作成</div>

        <div class="scroll-area">

            <form id="taskForm">

                <input
                    type="text"
                    class="box"
                    id="goal-name"
                    name="goal_name"
                    placeholder="目標名を入力"
                >

                <div class="box">
                    頻度

                    <div class="days" id="frequency-group">
                        <div class="day">日</div>
                        <div class="day">月</div>
                        <div class="day">火</div>
                        <div class="day">水</div>
                        <div class="day">木</div>
                        <div class="day">金</div>
                        <div class="day">土</div>
                    </div>
                </div>

                <div class="box">
                    開始タイミング

                    <input
                        type="text"
                        class="date-box"
                        id="start-timing"
                        name="start_timing"
                        placeholder="10:00"                    >
                </div>

                <div class="box">
                    所要時間

                    <div class="time-inputs">
                        <input
                            type="text"
                            class="date-box"
                            id="duration-hours"
                            name="duration_hours"
                            placeholder="0"
                        >
                        <span>時間</span>

                        <input
                            type="text"
                            class="date-box"
                            id="duration-minutes"
                            name="duration_minutes"
                            placeholder="30"
                        >
                        <span>分</span>
                    </div>
                </div>

                <div class="box">
                    モード

                    <div class="days" id="mode-group">
                        <div class="day active">自由設定</div>
                        <div class="day">優先順位</div>
                    </div>
                </div>

                <div class="box" id="priority-box">
                    優先度

                    <div class="days" id="priority-group">
                        <div class="day">高</div>
                        <div class="day active">中</div>
                        <div class="day">低</div>
                    </div>
                </div>

                <div class="row">

                    <div class="box">
                        開始日

                        <input
                            type="text"
                            class="date-box"
                            id="start-date"
                            name="start_date"
                            placeholder="20241107"
                        >
                    </div>

                    <div class="box">
                        終了日

                        <input
                            type="text"
                            class="date-box"
                            id="end-date"
                            name="end_date"
                            placeholder="20241231"
                        >
                    </div>

                </div>

                <div class="box">

                    目標カラー

                    <div class="color-selection" id="color-group">

                        <div class="color-circle" data-color="#ffadad" style="background:#ffadad;"></div>
                        <div class="color-circle" data-color="#ffd6a5" style="background:#ffd6a5;"></div>
                        <div class="color-circle" data-color="#fdffb6" style="background:#fdffb6;"></div>
                        <div class="color-circle active" data-color="#caffbf" style="background:#caffbf;"></div>
                        <div class="color-circle" data-color="#9bf6ff" style="background:#9bf6ff;"></div>
                        <div class="color-circle" data-color="#a0c4ff" style="background:#a0c4ff;"></div>
                        <div class="color-circle" data-color="#bdb2ff" style="background:#bdb2ff;"></div>
                        <div class="color-circle" data-color="#ffc6ff" style="background:#ffc6ff;"></div>
                        <div class="color-circle" data-color="#fffffc" style="background:#fffffc;"></div>
                        <div class="color-circle" data-color="#e2e2e2" style="background:#e2e2e2;"></div>

                        <div class="color-circle add-color" id="add-color-btn">
                            ＋
                        </div>

                    </div>

                    <input
                        type="color"
                        id="custom-color-picker"
                        hidden
                    >

                </div>

            </form>

        </div>

        <button
            type="submit"
            class="btn edit"
            id="submitButton"
            form="taskForm"
        >
            登録する
        </button>

        <button
            type="button"
            class="btn delete"
            onclick="if(confirm('本当にとりけししますか？')){ alert('とりけししました'); }"
        >
            とりけし
        </button>

    </div>

    <div id="message" class="message"></div>

    <script src="{{ asset('/js/taskCreate.js') }}"></script>

</body>

</html>