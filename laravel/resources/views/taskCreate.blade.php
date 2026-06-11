<!DOCTYPE html>
<html lang="ja">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta name="csrf-token" content="{{ csrf_token() }}">
<title>目標新規作成 | STEPRA</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

<style>
    body { background-color: #f8f9fa; }
    .day.active { background-color: #0d6efd !important; color: white !important; }
    .disabled-group { opacity: 0.5; pointer-events: none; }

    /* カラーサークルのスタイル */
    .color-selection {
    display: flex;
    flex-wrap: wrap;
    gap: 10px;
    margin-top: 10px;
    }
    .color-circle {
    width: 40px;
    height: 40px;
    border-radius: 50%;
    cursor: pointer;
    border: 2px solid #333;
    box-sizing: border-box;
    box-shadow: 0 0 0 1px #ccc;
    }
    .color-circle.custom {
    border: 2px dashed #666;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 20px;
    color: #666;
    background-color: #fff;
    box-shadow: none;
    }
</style>
</head>
<body>

<div class="container-fluid py-4">
    <img src="{{ asset('image/tit.png') }}" class="mb-3" style="width:200px;">
<div class="row justify-content-center">

    <div class="col-12 col-md-8 col-lg-5">
    
    <!-- <div class="card shadow"> -->
        <div class="card-body p-4">
        
        <h2 class="text-center fw-bold mb-4 fs-4">目標新規作成</h2>

        <div class="mb-3">
            <label class="form-label fw-bold small">目標名</label>
            <input type="text" class="form-control" id="goal-name" placeholder="目標名を入力">
        </div>

        <div class="mb-3">
            <label class="form-label fw-bold small">頻度</label>
            <div class="btn-group w-100" id="frequency-group">
            <button type="button" class="btn btn-outline-secondary day" onclick="this.classList.toggle('active')">日</button>
            <button type="button" class="btn btn-outline-secondary day active" onclick="this.classList.toggle('active')">月</button>
            <button type="button" class="btn btn-outline-secondary day" onclick="this.classList.toggle('active')">火</button>
            <button type="button" class="btn btn-outline-secondary day active" onclick="this.classList.toggle('active')">水</button>
            <button type="button" class="btn btn-outline-secondary day" onclick="this.classList.toggle('active')">木</button>
            <button type="button" class="btn btn-outline-secondary day" onclick="this.classList.toggle('active')">金</button>
            <button type="button" class="btn btn-outline-secondary day active" onclick="this.classList.toggle('active')">土</button>
            </div>
        </div>

        <div class="row g-2 mb-3">
            <div class="col-6">
            <label class="form-label fw-bold small">開始タイミング</label>
            <input type="text" class="form-control" id="start-timing" placeholder="10:00" value="10:00">
            </div>
            <div class="col-6">
            <label class="form-label fw-bold small">所要時間</label>
            <div class="d-flex gap-1">
                <input type="number" class="form-control" id="duration-hours" placeholder="0" min="0" value="0">
                <span class="align-self-center small">時間</span>
                <input type="number" class="form-control" id="duration-minutes" placeholder="30" min="0" max="59" value="30">
                <span class="align-self-center small">分</span>
            </div>
            </div>
        </div>

        <div class="mb-3 border p-3 rounded bg-light">
            <label class="form-label fw-bold small">モード設定</label>
            <div class="btn-group w-100 mb-2" id="mode-group">
            <button type="button" class="btn btn-outline-primary day active" onclick="selectMode(this)">自由設定</button>
            <button type="button" class="btn btn-outline-primary day" onclick="selectMode(this)">優先順位</button>
            </div>
            <div id="priority-box" class="disabled-group">
            <label class="form-label small">優先度</label>
            <div class="btn-group w-100" id="priority-group">
                <button type="button" class="btn btn-outline-dark day" onclick="selectSingle(this)">高</button>
                <button type="button" class="btn btn-outline-dark day" onclick="selectSingle(this)">中</button>
                <button type="button" class="btn btn-outline-dark day" onclick="selectSingle(this)">低</button>
            </div>
            </div>
        </div>

        <div class="row g-2 mb-3">
            <div class="col-6">
            <label class="form-label fw-bold small">開始日</label>
            <input type="text" class="form-control" id="start-date" placeholder="20260101" value="20260101">
            </div>
            <div class="col-6">
            <label class="form-label fw-bold small">終了日</label>
            <input type="text" class="form-control" id="end-date" placeholder="20261231" value="20261231">
            </div>
        </div>

        <div class="mb-4">
            <label class="form-label fw-bold small">目標カラー</label>
            <button type="button" id="delete-mode-btn" class="btn btn-sm btn-outline-secondary ms-2" style="font-size: 11px; padding: 2px 6px;" onclick="toggleColorDeleteMode()">色の変更</button>
            
            <div class="color-selection" id="color-group">
            <div class="color-circle" id="current-color-circle" style="background-color: #0d6efd;" data-color="#0d6efd"></div>
            <div class="color-circle custom" id="custom-color-circle" style="display: none;" onclick="selectCustomColor()">＋</div>
            <input type="color" id="custom-color-picker" style="display: none;" onchange="updateCustomColor(this.value)">
            </div>
        </div>

        <div>
            <button type="button" class="btn btn-success w-100 mb-2" onclick="saveGoal()">登録する</button>
            <button type="button" class="btn btn-secondary w-100" onclick="location.href='/task'">とりけし</button>
        </div>
        
        </div>
    <!-- </div> -->

    </div>
</div>
</div>

<script>
// モード切り替え
function selectMode(element) {
    selectSingle(element);
    const priorityBox = document.getElementById('priority-box');
    priorityBox.className = (element.innerText === '優先順位') ? '' : 'disabled-group';
    
    if (element.innerText === '自由設定') {
    document.querySelectorAll('#priority-group .day').forEach(el => el.classList.remove('active'));
    }
}

// 単一選択
function selectSingle(element) {
    const parent = element.parentElement;
    parent.querySelectorAll('.day').forEach(el => el.classList.remove('active'));
    element.classList.add('active');
}

// 🎨 カラー選択
let isColorDeleteMode = false;
function toggleColorDeleteMode() {
    isColorDeleteMode = !isColorDeleteMode;

    const btn = document.getElementById('delete-mode-btn');
    const addBtn = document.getElementById('custom-color-circle');

    if (isColorDeleteMode) {
    btn.textContent = '色の変更完了';
    btn.classList.replace('btn-outline-secondary', 'btn-secondary');
    if (addBtn) addBtn.style.display = 'flex';
    } else {
    btn.textContent = '色の変更';
    btn.classList.replace('btn-secondary', 'btn-outline-secondary');
    if (addBtn) addBtn.style.display = 'none';
    }
}

// カスタムカラー選択の起動
function selectCustomColor() {
    document.getElementById('custom-color-picker').click();
}

// カラーサークルの書き換え
function updateCustomColor(value) {
    if (!value) return;
    const targetCircle = document.getElementById('current-color-circle');
    if (targetCircle) {
    targetCircle.style.backgroundColor = value;
    targetCircle.dataset.color = value;
    }
}

// 💾 新規追加用の保存処理
async function saveGoal() {

    const userId =
        localStorage.getItem("user_id");

    if (!userId) {
        alert("ログインしてください");
        location.href = "/login";
        return;
    }

const name = document.getElementById('goal-name').value.trim();

if (!name) {
    alert('目標名を入力してください');
    return;
}

const daysActive =
    document.querySelectorAll('#frequency-group .active');

if (daysActive.length === 0) {
    alert('頻度を選択してください');
    return;
}

const timing =
    document.getElementById('start-timing').value.trim();

const startDate =
    document.getElementById('start-date').value.trim();

const endDate =
    document.getElementById('end-date').value.trim();

const durationHours =
    document.getElementById('duration-hours').value;

const durationMinutes =
    document.getElementById('duration-minutes').value;

const color =
    document.getElementById('current-color-circle')
    .dataset.color;

let priority = null;

if (
    !document.getElementById('priority-box')
    .classList.contains('disabled-group')
) {

    priority =
        document.querySelector(
            '#priority-group .active'
        )?.innerText;
}

const weekDays =
    Array.from(daysActive)
    .map(el => el.innerText);

try {

    const response =
        await fetch('/tasks', {

            method: 'POST',

            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN':
                    document.querySelector(
                        'meta[name="csrf-token"]'
                    ).content
            },

            body: JSON.stringify({

    user_id: userId,

    title: name,

    content: "",

    week_days: weekDays,

    start_time: timing,

    duration_hours: durationHours,

    duration_minutes: durationMinutes,

    priority: priority,

    color: color,

    start_date: startDate,

    end_date: endDate
})
        });

    const data = await response.json();

    if (!response.ok) {
        alert(data.message);
        return;
    }

    alert(data.message);

    location.href = "/task";

} catch (error) {

    console.error(error);

    alert('登録に失敗しました');
}

}

</script>

</body>
</html>