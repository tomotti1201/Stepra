<!DOCTYPE html>
<html lang="ja">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta name="csrf-token" content="{{ csrf_token() }}">
<title>目標編集 | STEPRA</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

<style>
    body {
    background-color: #f8f9fa;
}

.day,
.period {
    background-color: #f8f9fa;
    color: #6c757d;
}

.day.active,
.period.active {
    background-color: #0d6efd !important;
    border-color: #0d6efd !important;
    color: white !important;
}

.disabled-group {
    opacity: 0.5;
    pointer-events: none;
}
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
    .color-circle.selected {
    transform: scale(1.15);
    border: 3px solid black;
    }

    .color-option {
    position: relative;
    display: inline-flex;
    align-items: center;
    }

    .color-remove {
    position: absolute;
    top: -5px;
    right: -5px;
    width: 18px;
    height: 18px;
    border: none;
    border-radius: 50%;
    background: #fff;
    color: #444;
    box-shadow: 0 0 0 1px #ccc;
    font-size: 12px;
    line-height: 1;
    cursor: pointer;
    }

    .color-remove:hover {
    background: #f8d7da;
    color: #b02a37;
    }

    .page-title{
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
    <img src="{{asset('image/tit.png')}}" class="mb-3" style="width:200px;">


<div class="row justify-content-center">


    <!-- <div class="card shadow"> -->
    <div class="card-body p-4">

        <h2 class="text-center fw-bold">目標編集</h2>

        <div class="mb-3">
        <label class="form-label fw-bold">目標名</label>
        <input type="text" class="form-control" id="goal-name" placeholder="目標名を入力">
        </div>


        <div class="mb-3">
    <label class="form-label fw-bold">
        頻度
    </label>

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
        <div class="row g-2 mb-3">
        <div class="col-6">
            <label class="form-label fw-bold">開始時間</label>
            <input type="time" class="form-control" id="start-timing" placeholder="10:00">
        </div>

        <div class="col-6">
            <label class="form-label fw-bold">所要時間</label>

            <div class="d-flex gap-1">
            <input type="number" class="form-control" id="duration-hours" placeholder="0" min="0">
            <span class="align-self-center">時間</span>

            <input type="number" class="form-control" id="duration-minutes" placeholder="0" min="0" max="59">
            <span class="align-self-center">分</span>
            </div>
        </div>
        </div>

        <div class="mb-3 border p-3 rounded bg-light">
        <label class="form-label fw-bold">モード設定</label>

        <div class="btn-group w-100 mb-2" id="mode-group">
            <button type="button" class="btn btn-outline-secondary day active" onclick="selectMode(this)">自由設定</button>
            <button type="button" class="btn btn-outline-secondary day" onclick="selectMode(this)">優先順位</button>
        </div>

        <div id="priority-box" class="disabled-group">
            <label class="form-label fw-bold">優先度</label>

            <div class="btn-group w-100" id="priority-group">
            <button type="button" class="btn btn-outline-secondary day" onclick="selectSingle(this)">高</button>
            <button type="button" class="btn btn-outline-secondary day" onclick="selectSingle(this)">中</button>
            <button type="button" class="btn btn-outline-secondary day" onclick="selectSingle(this)">低</button>
            </div>
        </div>
        </div>

        <div class="row g-2 mb-3">
        <div class="col-6">
            <label class="form-label fw-bold">開始日</label>
            <input type="date" class="form-control" id="start-date" disabled>
        </div>

        <div class="col-6">
            <label class="form-label fw-bold">終了日</label>
            <input type="date" class="form-control" id="end-date">
        </div>
        </div>

        <div class="mb-4">

    <label class="form-label fw-bold">目標カラー</label>

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
                    onclick="saveGoal()">
                    編集を保存
                </button>
            </div>

        </div>

        <div class="mt-2">
            <button
                type="button"
                class="btn btn-danger w-100"
                onclick="deleteGoal()">
                削除
            </button>
        </div>

    </div>
    <!-- </div> -->

    </div>
</div>
</div>

<script>
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

        dayButtons.forEach(btn =>
            btn.classList.remove('active')
        );

    } else {

        everydayBtn.classList.add('active');

        dayButtons.forEach(btn =>
            btn.classList.add('active')
        );
    }
}
function togglePeriod(element) {

    const isActive =
        element.classList.contains('active');

    document
        .querySelectorAll('#period-group .period')
        .forEach(btn =>
            btn.classList.remove('active')
        );

    if (!isActive) {
        element.classList.add('active');
    }
}
function selectMode(element) {
    selectSingle(element);

    const priorityBox = document.getElementById('priority-box');

    if (element.innerText === '優先順位') {
        priorityBox.classList.remove('disabled-group');
    } else {
        priorityBox.classList.add('disabled-group');

        document.querySelectorAll('#priority-group .day')
            .forEach(el => el.classList.remove('active'));
    }
}

function selectSingle(element) {
    const parent = element.parentElement;

    parent.querySelectorAll('.day')
        .forEach(el => el.classList.remove('active'));

    element.classList.add('active');
}

let availableColors = ["#0d6efd", "#198754", "#dc3545", "#ffc107", "#6f42c1"];
let selectedColor = availableColors[0];

function renderColorPalette() {
    const colorGroup = document.getElementById("color-group");
    const limitMessage = document.getElementById("color-limit-message");

    if (!colorGroup) return;

    colorGroup.innerHTML = "";

    availableColors.forEach(color => {
        const option = document.createElement("div");
        option.className = "color-option";

        const swatch = document.createElement("div");
        swatch.className = "color-circle" + (selectedColor === color ? " selected" : "");
        swatch.style.backgroundColor = color;
        swatch.dataset.color = color;
        swatch.onclick = function () {
            selectColor(this);
        };

        const removeButton = document.createElement("button");
        removeButton.type = "button";
        removeButton.className = "color-remove";
        removeButton.textContent = "×";
        removeButton.setAttribute("aria-label", "色を削除");
        removeButton.onclick = function (event) {
            event.stopPropagation();
            removeColor(color);
        };

        option.appendChild(swatch);
        option.appendChild(removeButton);
        colorGroup.appendChild(option);
    });

    const addButton = document.createElement("div");
    addButton.className = "color-circle custom";
    addButton.id = "add-color-btn";
    addButton.textContent = "＋";
    addButton.onclick = function () {
        selectCustomColor();
    };
    colorGroup.appendChild(addButton);

    if (limitMessage) {
        limitMessage.textContent =
            availableColors.length >= 5
                ? "彩りは5色まで。大切な色を残して、気分に合わせて入れ替えてみてください。"
                : "好きな色を5色まで選べます。不要な色は削除できます。";
        limitMessage.className = "form-text " + (availableColors.length >= 5 ? "text-warning" : "text-muted");
    }
}

function selectColor(element) {
    selectedColor = element.dataset.color;
    renderColorPalette();
}

function addCustomColor(value) {
    if (!value) return;

    if (availableColors.includes(value)) {
        selectedColor = value;
        renderColorPalette();
        return;
    }

    if (availableColors.length >= 5) {
        const limitMessage = document.getElementById("color-limit-message");
        if (limitMessage) {
            limitMessage.textContent = "彩りは5色まで。大切な色を残して、気分に合わせて入れ替えてみてください。";
            limitMessage.className = "form-text text-warning";
        }
        return;
    }

    availableColors.push(value);
    selectedColor = value;
    renderColorPalette();
}

function removeColor(colorValue) {
    if (availableColors.length <= 1) {
        const limitMessage = document.getElementById("color-limit-message");
        if (limitMessage) {
            limitMessage.textContent = "最後の1色は残しておきましょう。";
            limitMessage.className = "form-text text-secondary";
        }
        return;
    }

    availableColors = availableColors.filter(color => color !== colorValue);

    if (selectedColor === colorValue) {
        selectedColor = availableColors[0];
    }

    renderColorPalette();
}

function selectCustomColor() {
    document.getElementById('custom-color-picker').click();
}


async function loadTaskFromDB() {
    const params = new URLSearchParams(location.search);
    const id = params.get('id');

    if (!id) {
        alert('IDがありません');
        return;
    }

    const res = await fetch(`/tasks/${id}`);
    const json = await res.json();

    if (!res.ok) {
        alert(json.message || '取得失敗');
        return;
    }

    const task = json.task;

    document.getElementById('goal-name').value = task.title ?? '';
    document.getElementById('start-date').value = task.start_date ?? '';
    document.getElementById('end-date').value = task.end_date ?? '';
    document.getElementById('start-timing').value =
        (task.start_time ?? '').slice(0, 5);

    const total = task.required_minutes ?? 0;
    document.getElementById('duration-hours').value = Math.floor(total / 60);
    document.getElementById('duration-minutes').value = total % 60;

    if (task.color) {
        if (!availableColors.includes(task.color)) {
            if (availableColors.length < 5) {
                availableColors.push(task.color);
            } else {
                availableColors = [...availableColors.slice(0, 4), task.color];
            }
        }

        selectedColor = task.color;
        renderColorPalette();
    }

    const dayMap = {
        1: '月',
        2: '火',
        3: '水',
        4: '木',
        5: '金',
        6: '土',
        7: '日'
    };

    const weekDays = JSON.parse(task.week_days || '[]');

    document.querySelectorAll('#frequency-group .day')
        .forEach(btn => {
            if (weekDays.some(d => dayMap[d] === btn.innerText)) {
                btn.classList.add('active');
            }
        });

    const allSelected =
        document.querySelectorAll(
            '#frequency-group .day.active'
        ).length === 7;

    if (allSelected) {
        document
            .getElementById('everyday-btn')
            .classList.add('active');
    }

    const periodBtn = [...document.querySelectorAll('#period-group .period')]
        .find(btn => btn.dataset.period === task.period);

    if (periodBtn) {
        periodBtn.classList.add('active');
    }

    const priorityMap = {
        high: '高',
        middle: '中',
        low: '低'
    };

    const priorityBtn = [...document.querySelectorAll('#priority-group .day')]
        .find(btn => btn.innerText === priorityMap[task.priority]);

    if (priorityBtn) {
        const modeBtn = [...document.querySelectorAll('#mode-group .day')]
            .find(btn => btn.innerText === '優先順位');

        if (modeBtn) {
            selectMode(modeBtn);
        }

        priorityBtn.classList.add('active');
    }
}

document.addEventListener('DOMContentLoaded', () => {
    renderColorPalette();
    loadTaskFromDB();
});

async function saveGoal() {
    const id = new URLSearchParams(location.search).get('id');

    const name = document.getElementById('goal-name').value;

    const days = [...document.querySelectorAll('#frequency-group .active')]
        .map(el => el.innerText);

    const period =
        document.querySelector('#period-group .active')
            ?.dataset.period ?? null;

    const startDate = document.getElementById('start-date').value;
    const endDate = document.getElementById('end-date').value;
    const timing = document.getElementById('start-timing').value;
    const hours = document.getElementById('duration-hours').value;
    const minutes = document.getElementById('duration-minutes').value;

    const color = selectedColor;
    
    if (days.length === 0) {
        alert('曜日を選択してください');
        return;
    }

    const priority =
        document.querySelector('#priority-group .active')
            ?.innerText ?? null;

    const res = await fetch(`/tasks/${id}`, {
        method: 'PUT',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN':
                document.querySelector('meta[name="csrf-token"]').content
        },
        body: JSON.stringify({
            title: name,
            week_days: days,
            period: period,
            start_time: timing,
            priority: priority,
            start_date: startDate,
            end_date: endDate,
            duration_hours: hours,
            duration_minutes: minutes,
            color: color
        })
    });

    const json = await res.json();

    if (!res.ok) {
        alert(json.message);
        return;
    }

    alert('更新しました');
    location.href = '/task';
}
async function deleteGoal() {

    const id =
        new URLSearchParams(location.search)
            .get('id');

    if (!confirm('この目標を削除しますか？')) {
        return;
    }

    const response =
        await fetch(`/tasks/${id}`, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN':
                    document.querySelector(
                        'meta[name="csrf-token"]'
                    ).content
            }
        });

    const data = await response.json();

    if (!response.ok) {
        alert(data.message);
        return;
    }

    alert('削除しました');
    location.href = '/task';
}
function cancelEdit() {

    const result =
        confirm('編集内容を破棄しますか？');

    if (!result) {
        return;
    }

    location.href = '/task';
}
</script>

</body>
</html>