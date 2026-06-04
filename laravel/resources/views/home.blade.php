<!DOCTYPE html>
<html lang="ja">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<link rel="stylesheet" href="{{asset('css/home.css')}}">

<title>STEPRA ホーム画面</title>

</head>

<body>
<div class="phone">

    <div class="top-bar">
    <div class="schedule-title">
        本日のスケジュール
    </div>
    </div>

    <div class="chart-area">
    <div class="circle-chart" id="circleChart"></div>
    </div>

    <div class="goal-area">
    <div class="goal-title" id="goalTitle">
        本日の目標一覧
    </div>

    <button class="change-btn" onclick="changeGoal()">
        ▶
    </button>
    </div>

    <div class="goal-list" id="goalList"></div>

    <div class="bottom-menu">
    <button class="menu-btn active-btn">⌂</button>
    <button class="menu-btn">◷</button>
    <button class="menu-btn">▤</button>
    <button class="menu-btn">◉</button>
    <button class="menu-btn">☷</button>
    </div>

    <div class="overlay" id="overlay">
    <div class="reason-modal">

        <button class="close-modal-btn" onclick="closeReasonModal()">
        ×
        </button>

        <div class="modal-title">
        出来なかった理由
        </div>

        <div class="reason-list">

        <label class="reason-item">
            <input type="radio" name="reason">
            <span>急な用事が入った</span>
        </label>

        <label class="reason-item">
            <input type="radio" name="reason">
            <span>仮眠をしすぎた</span>
        </label>

        <label class="reason-item">
            <input type="radio" name="reason">
            <span>他ごとをしていて忘れていた</span>
        </label>

        <label class="reason-item">
            <input type="radio" name="reason">
            <span>やる気がなかった</span>
        </label>

        </div>

        <button class="register-btn" onclick="registerReason()">
        登録
        </button>

    </div>
    </div>

</div>

    <script src="{{asset('/js/home.js')}}"></script>

</body>
</html>