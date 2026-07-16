<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>グループタスク編集 | STEPRA</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        body {
            background-color: #f8f9fa;
        }

        .day.active,
        .mode.active,
        .priority.active {
            background-color: #0d6efd !important;
            border-color: #0d6efd !important;
            color: white !important;
        }

        .disabled-group {
            opacity: 0.5;
            pointer-events: none;
        }

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
        }

        .color-circle.selected {
            transform: scale(1.15);
            border: 3px solid black;
        }

        .color-circle.custom {
            display: flex;
            align-items: center;
            justify-content: center;
            background: white;
            border: 2px dashed #666;
            font-size: 20px;
            color: #666;
        }

        .color-option {
            position: relative;
            display: inline-flex;
        }

        .color-remove {
            position: absolute;
            top: -5px;
            right: -5px;
            width: 18px;
            height: 18px;
            border: none;
            border-radius: 50%;
            background: white;
            color: #444;
            box-shadow: 0 0 0 1px #ccc;
            cursor: pointer;
            font-size: 12px;
        }

        .page-title {
            font-size: clamp(1.5rem, 2vw, 2.2rem);
        }

        .form-label{
    font-size: clamp(0.95rem, 1.2vw, 1.2rem);
}

.form-control{
    font-size: clamp(1rem, 1.2vw, 1.2rem);
}

.btn{
    font-size: clamp(1rem, 1.3vw, 1.2rem);
}

.small{
    font-size: clamp(0.9rem, 1vw, 1.1rem) !important;
}
    </style>
</head>

<body>

<div class="container py-4 mb-5">

    <img src="{{ asset('image/tit.png') }}" 
         class="mb-3" 
         style="width:200px;">

    <div class="row justify-content-center">

    <!-- <div class="card shadow"> -->

                <div class="card-body p-4">

                    <h2 class="text-center fw-bold">
                        グループタスク編集
                    </h2>

                        <div class="mb-3">
                            <label class="form-label fw-bold">目標名</label>
                            <input
                                type="text"
                                id="goal-name"
                                class="form-control"
                                placeholder="タスク名を入力">
                        </div>

                        <!-- 頻度 -->

                        <div class="mb-3">

                            <label class="form-label fw-bold">頻度</label>

                            <div class="d-flex gap-2 mb-2">

                                <button
                                    type="button"
                                    id="everyday-btn"
                                    class="btn btn-outline-secondary day"
                                    onclick="toggleEveryday()">
                                    毎日
                                </button>

                                <div class="btn-group flex-grow-1" id="frequency-group">

                                    <button type="button" class="btn btn-outline-secondary day" onclick="toggleDay(this)">月</button>
                                    <button type="button" class="btn btn-outline-secondary day" onclick="toggleDay(this)">火</button>
                                    <button type="button" class="btn btn-outline-secondary day" onclick="toggleDay(this)">水</button>
                                    <button type="button" class="btn btn-outline-secondary day" onclick="toggleDay(this)">木</button>
                                    <button type="button" class="btn btn-outline-secondary day" onclick="toggleDay(this)">金</button>
                                    <button type="button" class="btn btn-outline-secondary day" onclick="toggleDay(this)">土</button>
                                    <button type="button" class="btn btn-outline-secondary day" onclick="toggleDay(this)">日</button>

                                </div>
                            </div>
                        </div>
                        <!-- 時間設定 -->
                        <div class="row g-2 mb-3">
                            <div class="col-6">
                                <label class="form-label fw-bold">開始時間</label>
                                <input
                                    type="time"
                                    id="start-timing"
                                    class="form-control">
                            </div>
                            <div class="col-6">
                                <label class="form-label fw-bold">所要時間</label>
                                <div class="d-flex gap-1">
                                    <input
                                        type="number"
                                        id="duration-hours"
                                        class="form-control"
                                        min="0">
                                    <span class="align-self-center">時間</span>
                                    <input
                                        type="number"
                                        id="duration-minutes"
                                        class="form-control"
                                        min="0"
                                        max="59">
                                    <span class="align-self-center">分</span>
                                </div>
                            </div>
                        </div>
                        <!-- モード設定 -->
                        <div class="mb-3 border rounded p-3">
                            <label class="form-label fw-bold">モード設定</label>
                            <div class="btn-group w-100 mb-2" id="mode-group">
                                <button
                                    type="button"
                                    class="btn btn-outline-secondary mode active"
                                    onclick="selectMode(this)">
                                    自由設定
                                </button>
                                <button
                                    type="button"
                                    class="btn btn-outline-secondary mode"
                                    onclick="selectMode(this)">
                                    優先順位
                                </button>
                            </div>
                            <div id="priority-box" class="disabled-group">
                                <label class="form-label fw-bold">優先度</label>
                                <div class="btn-group w-100" id="priority-group">
                                    <button type="button" class="btn btn-outline-secondary priority" onclick="selectPriority(this)">高</button>
                                    <button type="button" class="btn btn-outline-secondary priority" onclick="selectPriority(this)">中</button>
                                    <button type="button" class="btn btn-outline-secondary priority" onclick="selectPriority(this)">低</button>
                                </div>
                            </div>
                        </div>
                        <!-- 開始日・終了日 -->
                        <div class="row g-2 mb-3">
                            <div class="col-6">
                                <label class="form-label fw-bold">開始日</label>
                                <input
                                    type="date"
                                    id="start-date"
                                    class="form-control"
                                    disabled>
                            </div>
                            <div class="col-6">
                                <label class="form-label fw-bold">終了日</label>
                                <input
                                    type="date"
                                    id="end-date"
                                    class="form-control">
                            </div>
                        </div>
                        <!-- カラー設定 -->
                        <div class="mb-4">
                            <label class="form-label fw-bold">タスクカラー</label>
                            <div id="color-limit-message" class="form-text text-muted mb-2">
                                好きな色を5色まで選べます。不要な色は削除できます。
                            </div>
                            <div class="color-selection" id="color-group"></div>
                            <input
                                type="color"
                                id="custom-color-picker"
                                style="display:none;"
                                onchange="addCustomColor(this.value)">
                        </div>
                        <!-- ボタン -->
                        <div class="row g-2">
                            <div class="col-6">
                                <button
                                    type="button"
                                    class="btn btn-secondary w-100"
                                    onclick="cancelEdit()">
                                    キャンセル
                                </button>
                            </div>
                            <div class="col-6">
                                <button
                                    type="button"
                                    class="btn btn-primary w-100"
                                    onclick="saveGroupTask()">
                                    編集を保存
                                </button>
                            </div>
                        </div>
                        <div class="mt-2">

                            <button
                                type="button"
                                class="btn btn-danger w-100"
                                onclick="deleteGroupTask()">
                                削除
                            </button>
                        </div>
                    </div>
            </div>

        </div>

    </div>

</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

<script>

let availableColors = [
    "#0d6efd",
    "#198754",
    "#dc3545",
    "#ffc107",
    "#6f42c1"
];

let selectedColor = availableColors[0];
function renderColorPalette() {

    const colorGroup =
        document.getElementById("color-group");

    if (!colorGroup) {
        return;
    }

    colorGroup.innerHTML = "";

    availableColors.forEach(color => {

        const option = document.createElement("div");
        option.className = "color-option";

        const circle = document.createElement("div");
        circle.className = "color-circle";

        if (selectedColor === color) {
            circle.classList.add("selected");
        }

        circle.style.backgroundColor = color;

        circle.onclick = function () {
            selectColor(color);
        };

        const remove = document.createElement("button");
        remove.type = "button";
        remove.className = "color-remove";
        remove.textContent = "×";

        remove.onclick = function (e) {
            e.stopPropagation();
            removeColor(color);
        };

        option.appendChild(circle);
        option.appendChild(remove);
        colorGroup.appendChild(option);

    });

    const addButton = document.createElement("div");
    addButton.className = "color-circle custom";
    addButton.textContent = "＋";
    addButton.onclick = selectCustomColor;

    colorGroup.appendChild(addButton);

}

function selectColor(color) {

    selectedColor = color;
    renderColorPalette();

}

function selectCustomColor() {

    document
        .getElementById("custom-color-picker")
        .click();

}

function addCustomColor(value) {

    if (!value) {
        return;
    }

    if (!availableColors.includes(value)) {

        if (availableColors.length >= 5) {

            alert("色は5色までです");
            return;

        }

        availableColors.push(value);

    }

    selectedColor = value;
    renderColorPalette();

}

function removeColor(color) {

    if (availableColors.length <= 1) {
        return;
    }

    availableColors = availableColors.filter(c => c !== color);

    if (selectedColor === color) {
        selectedColor = availableColors[0];
    }

    renderColorPalette();

}

document.addEventListener(
    "DOMContentLoaded",
    loadGroupTask
);
async function loadGroupTask() {

    const id = new URLSearchParams(location.search).get('task_id');

    if (!id) {
        alert('タスクIDがありません');
        return;
    }

    const response = await fetch(`/api/grouptasks/${id}`);
    const result = await response.json();

    if (!response.ok) {
        alert(result.message);
        return;
    }

    const task = result.task;

    // 目標名
    document.getElementById('goal-name').value = task.title;

    // 開始時間
    document.getElementById('start-timing').value = task.start_time;

    // 所要時間
    const hours = Math.floor(task.required_minutes / 60);
    const minutes = task.required_minutes % 60;

    document.getElementById('duration-hours').value = hours;
    document.getElementById('duration-minutes').value = minutes;

    // 日付
    const startDate = document.getElementById('start-date');
    startDate.value = task.start_date ?? "";
    startDate.disabled = true;

    document.getElementById('end-date').value = task.end_date ?? "";

    // 曜日
    const days = JSON.parse(task.week_days);

    document.querySelectorAll('.day').forEach(btn => {

        if (days.includes(convertDay(btn.innerText))) {
            btn.classList.add('active');
        }

    });

    // 優先度
    if (task.priority) {

        const priorityMap = {
            high: "高",
            middle: "中",
            low: "低"
        };

        // 優先順位モードに変更
        const priorityModeBtn = [...document.querySelectorAll('#mode-group .mode')]
            .find(btn => btn.innerText === '優先順位');

        if (priorityModeBtn) {
            selectMode(priorityModeBtn);
        }

        // 優先度ボタン選択
        document.querySelectorAll('#priority-group .priority')
            .forEach(btn => {

                btn.classList.remove('active');

                if (btn.innerText === priorityMap[task.priority]) {
                    btn.classList.add('active');
                }

            });

    }

    // 色
    if (task.color) {

        selectedColor = task.color;

        if (!availableColors.includes(task.color)) {
            availableColors.push(task.color);
        }

        renderColorPalette();

    }

}
function convertDay(day) {

    const map = {
        "月": 1,
        "火": 2,
        "水": 3,
        "木": 4,
        "金": 5,
        "土": 6,
        "日": 7
    };

    return map[day];

}

function toggleDay(element) {

    element.classList.toggle('active');

    const dayButtons =
        document.querySelectorAll('#frequency-group .day');

    const everydayBtn =
        document.getElementById('everyday-btn');

    const allSelected =
        [...dayButtons].every(btn =>
            btn.classList.contains('active')
        );

    if (allSelected) {
        everydayBtn.classList.add('active');
    } else {
        everydayBtn.classList.remove('active');
    }

}

function toggleEveryday() {

    const everydayBtn =
        document.getElementById('everyday-btn');

    const dayButtons =
        document.querySelectorAll('#frequency-group .day');

    const isActive =
        everydayBtn.classList.contains('active');

    if (isActive) {

        everydayBtn.classList.remove('active');

        dayButtons.forEach(btn => {
            btn.classList.remove('active');
        });

    } else {

        everydayBtn.classList.add('active');

        dayButtons.forEach(btn => {
            btn.classList.add('active');
        });

    }

}

function selectMode(element) {

    document
        .querySelectorAll('#mode-group .mode')
        .forEach(btn => {
            btn.classList.remove('active');
        });

    element.classList.add('active');

    const priorityBox =
        document.getElementById('priority-box');

    if (element.innerText === '優先順位') {

        priorityBox.classList.remove('disabled-group');

    } else {

        priorityBox.classList.add('disabled-group');

        document
            .querySelectorAll('#priority-group .priority')
            .forEach(btn => {
                btn.classList.remove('active');
            });

    }

}

function selectPriority(element) {

    document
        .querySelectorAll('#priority-group .priority')
        .forEach(btn => {
            btn.classList.remove('active');
        });

    element.classList.add('active');

}

function selectSingle(element) {

    const parent =
        element.parentElement;

    parent
        .querySelectorAll('.day')
        .forEach(el => {
            el.classList.remove('active');
        });

    element.classList.add('active');

}
async function saveGroupTask() {

    const id = new URLSearchParams(location.search).get('task_id');

    if (!id) {
        alert('タスクIDがありません');
        return;
    }

    const data = {
        title: document.getElementById('goal-name').value,

        week_days: [...document.querySelectorAll('.day.active')]
            .map(el => el.innerText),

        start_time: document.getElementById('start-timing').value,

        duration_hours: document.getElementById('duration-hours').value,

        duration_minutes: document.getElementById('duration-minutes').value,

        priority: document.querySelector('.priority.active')
            ?.innerText ?? null,

        color: selectedColor,

        start_date: document.getElementById('start-date').value,

        end_date: document.getElementById('end-date').value
    };

    const response = await fetch(`/api/grouptasks/${id}`, {
        method: 'PUT',

        headers: {
            'Content-Type': 'application/json',

            'X-CSRF-TOKEN': document.querySelector(
                'meta[name="csrf-token"]'
            ).content
        },

        body: JSON.stringify(data)
    });

    const result = await response.json();

    if (!response.ok) {
        alert(result.message);
        return;
    }

    alert('更新しました');

    location.href =
    `/group/${result.task.group_id}/tasks`;
}

function cancelEdit() {

    const result =
        confirm('編集内容を破棄しますか？');

    if (!result) {
        return;
    }

    const id =
        new URLSearchParams(location.search)
            .get('task_id');

    if (id) {

        fetch(`/api/grouptasks/${id}`)
            .then(res => res.json())
            .then(data => {

                location.href =
                    `/group/${data.task.group_id}/tasks`;

            });

    } else {

        history.back();

    }

}

async function deleteGroupTask() {

    const id =
        new URLSearchParams(location.search)
            .get('task_id');

    if (!id) {
        alert('タスクIDがありません');
        return;
    }

    if (!confirm('このグループタスクを削除しますか？')) {
        return;
    }

    const response = await fetch(`/api/grouptasks/${id}`, {
        method: 'DELETE',

        headers: {
            'X-CSRF-TOKEN': document.querySelector(
                'meta[name="csrf-token"]'
            ).content
        }
    });

    const result = await response.json();

    if (!response.ok) {
        alert(result.message);
        return;
    }

    alert('削除しました');

    location.href =
    `/group/${result.group_id}/tasks`;
}
</script>
</body>
</html>