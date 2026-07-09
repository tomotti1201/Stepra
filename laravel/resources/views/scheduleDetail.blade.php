<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>STEPRA 日別詳細</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="bg-light">

<div class="container py-4 mb-5">

    <!-- タイトル画像 -->
        <img src="{{ asset('image/tit.png') }}" class="mb-3" style="width:200px;">

    <!-- スケジュール -->
    <div class="card shadow mb-4">
        <div class="card-body text-center">

            <div class="d-flex justify-content-between align-items-center mb-4">

                <button
                    class="btn btn-secondary px-4 py-2"
                    onclick="moveDate(-1)"
                >
                    &lt;
                </button>

                <h4 id="scheduleTitle" class="fw-bold mb-0">
                    スケジュール
                </h4>

                <button
                    class="btn btn-secondary px-4 py-2"
                    onclick="moveDate(1)"
                >
                    &gt;
                </button>

            </div>

            <div class="d-flex justify-content-center">
                <div
                    id="circleChart"
                    class="position-relative"
                    style="width:280px;height:280px;border-radius:50%;">
                </div>
            </div>

        </div>
    </div>

    <!-- 目標タイトル -->
    <div class="card shadow mb-3">
        <div class="card-body">

            <div class="d-flex justify-content-between align-items-center">

                <h5 id="goalTitle" class="fw-bold mb-0">
                    個人目標一覧
                </h5>

                <!--<button class="btn btn-success" onclick="changeGoal()">
                    切替
                </button>-->

            </div>
        </div>
    </div>

    <!-- 目標一覧 -->
    <div id="goalList"></div>

</div>

<!-- 未達成理由モーダル -->
<div class="modal fade" id="reasonModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">

            <div class="modal-header">

                <h5 class="modal-title">
                    未達成理由
                </h5>

                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>

            </div>

            <div class="modal-body">

                <div class="form-check mb-2">
                    <input class="form-check-input" type="radio" name="reason" value="急な用事">
                    <label class="form-check-label">
                        急な用事が入った
                    </label>
                </div>

                <div class="form-check mb-2">
                    <input class="form-check-input" type="radio" name="reason" value="仮眠">
                    <label class="form-check-label">
                        仮眠をしすぎた
                    </label>
                </div>

                <div class="form-check mb-2">
                    <input class="form-check-input" type="radio" name="reason" value="忘れた">
                    <label class="form-check-label">
                        他ごとをしていて忘れていた
                    </label>
                </div>

                <div class="form-check">
                    <input class="form-check-input" type="radio" name="reason" value="やる気">
                    <label class="form-check-label">
                        やる気がなかった
                    </label>
                </div>

            </div>

            <div class="modal-footer">

                <button class="btn btn-success" onclick="registerReason()">
                    登録
                </button>

            </div>

        </div>
    </div>
</div>

    <x-menubar />

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

<script>
function moveDate(diff){

    const date = new Date(dateStr);

    date.setDate(date.getDate() + diff);

    const y = date.getFullYear();
    const m = String(date.getMonth() + 1).padStart(2,"0");
    const d = String(date.getDate()).padStart(2,"0");

    location.href = `/scheduleDetail?date=${y}-${m}-${d}`;
}

// ==========================
// 日付取得（localStorage）
// ==========================
const urlParams = new URLSearchParams(window.location.search);
const dateStr = urlParams.get("date");

if (!dateStr) {
    alert("日付が指定されていません");
}

// ==========================
// 日付表示
// ==========================
document.getElementById("scheduleTitle").textContent =
`${dateStr} `;
const clickedDate = new Date(dateStr);
const today = new Date();

clickedDate.setHours(0,0,0,0);
today.setHours(0,0,0,0);

// 操作可能判定（今日・昨日・一昨日のみ）
const diffDays = (today - clickedDate) / (1000 * 60 * 60 * 24);

// 今日～2日前までだけ操作可能
const canEdit = diffDays >= 0 && diffDays <= 2;

// 初期ロード
document.addEventListener("DOMContentLoaded", loadDayData);

// データ取得（API）
async function loadDayData() {

    const userId = localStorage.getItem("user_id");

    const date = dateStr;

    const res = await fetch(`/api/schedules/daily?user_id=${userId}&date=${date}`);

    const data = await res.json();

    const tasks = data.schedules || [];

    createChart(tasks);
    createGoals(tasks);
}

// 時間変換
function timeToMinutes(time) {
    const [h, m] = time.split(":").map(Number);
    return h * 60 + m;
}

function minutesToTime(minutes) {
    let h = Math.floor(minutes / 60);
    let m = minutes % 60;

    if (h >= 24) h -= 24;

    return `${String(h).padStart(2, "0")}:${String(m).padStart(2, "0")}`;
}

// 円グラフ
function timeToMin(t) {
    if (!t) return 0;

    const [h, m] = t.split(":").map(Number);
    return h * 60 + m;
}
function createChart(tasks) {
    const chart = document.getElementById("circleChart");
    chart.innerHTML = "";

    for (let h = 0; h < 24; h++) {
        const angle = (h / 24) * 360 - 90;
        const radius = 150;

        const x = 140 + radius * Math.cos(angle * Math.PI / 180);
        const y = 140 + radius * Math.sin(angle * Math.PI / 180);

        const label = document.createElement("div");
        label.style.position = "absolute";
        label.style.left = `${x}px`;
        label.style.top = `${y}px`;
        label.style.transform = "translate(-50%, -50%)";
        label.style.fontSize = "12px";
        label.style.fontWeight = "600";
        label.style.color = "#495057";
        label.textContent = h;

        chart.appendChild(label);
    }

    for (let h = 0; h < 24; h++) {
        const angle = (h / 24) * 360 - 90 - 0.35;
        const isMain = h % 6 === 0;

        const innerRadius = 85;
        const outerRadius = 140;

        const x1 = 140 + innerRadius * Math.cos(angle * Math.PI / 180);
        const y1 = 140 + innerRadius * Math.sin(angle * Math.PI / 180);

        const x2 = 140 + outerRadius * Math.cos(angle * Math.PI / 180);
        const y2 = 140 + outerRadius * Math.sin(angle * Math.PI / 180);

        const line = document.createElement("div");

        const length = Math.sqrt((x2 - x1) ** 2 + (y2 - y1) ** 2);
        const angleDeg = Math.atan2(y2 - y1, x2 - x1) * 180 / Math.PI;

        line.style.position = "absolute";
        line.style.left = `${x1}px`;
        line.style.top = `${y1}px`;
        line.style.width = `${length}px`;
        line.style.height = isMain ? "1.5px" : "1px";
        line.style.background = isMain ? "#495057" : "#ced4da";
        line.style.transformOrigin = "0 0";
        line.style.transform = `rotate(${angleDeg}deg)`;

        chart.appendChild(line);
    }

    let gradients = [];
    let current = 0;

    const DAY = 1440;
    let totalMinutes = 0;

    tasks.forEach(task => {
        const start = timeToMin(task.start_time);
        const end = start + (task.required_minutes || 0);

        totalMinutes += task.required_minutes || 0;

        const startP = (start / DAY) * 100;
        const endP = (end / DAY) * 100;

        if (startP > current) {
            gradients.push(`#e9ecef ${current}% ${startP}%`);
        }

        const color = task.color ?? "#198754";

        gradients.push(`${color} ${startP}% ${endP}%`);
        current = endP;
    });

    if (current < 100) {
        gradients.push(`#e9ecef ${current}% 100%`);
    }

    chart.style.background = `conic-gradient(${gradients.join(",")})`;

    const hours = Math.floor(totalMinutes / 60);
    const minutes = totalMinutes % 60;

    const center = document.createElement("div");
    center.style.width = "170px";
    center.style.height = "170px";
    center.style.borderRadius = "50%";
    center.style.background = "#f8f9fa";
    center.style.position = "absolute";
    center.style.top = "50%";
    center.style.left = "50%";
    center.style.transform = "translate(-50%, -50%)";
    center.style.display = "flex";
    center.style.alignItems = "center";
    center.style.justifyContent = "center";
    center.style.flexDirection = "column";
    center.style.fontWeight = "bold";

    center.innerHTML = `
        <div style="font-size:18px;">合計</div>
        <div style="font-size:16px;">${hours}時間 ${minutes}分</div>
    `;

    chart.appendChild(center);
}

// 目標表示（ホームUI流用）
function createGoals(tasks) {

    const goalList = document.getElementById("goalList");
    goalList.innerHTML = "";

    if (!tasks.length) {
        goalList.innerHTML = `
            <div class="text-center text-muted">
                タスクなし
            </div>
        `;
        return;
    }
    const active = tasks.filter(t => t.status === "active");
    const completed = tasks.filter(t => t.status === "completed");
    const failed = tasks.filter(t => t.status === "failed");
    function createCard(task){

        return `
        <div class="d-flex align-items-center justify-content-between bg-white shadow-sm rounded mb-2 overflow-hidden"
            data-id="${task.id}">

            <div class="d-flex align-items-center flex-grow-1">

                <div style="
                    width:8px;
                    align-self:stretch;
                    background:${task.color ?? '#198754'};
                "></div>

                <div class="p-3">
                    <div class="fw-bold">${task.title}</div>

                    <div class="small text-muted">
                        ${task.start_time.slice(0,5)}
                        /
                        ${task.required_minutes}分
                        /
                        ${task.priority ?? "未設定"}
                    </div>

                    ${
                        task.status === "failed"
                        ? `<div class="text-danger small mt-1">
                            理由：${task.content ?? ""}
                        </div>`
                        : ""
                    }

                </div>

            </div>

            <div class="d-flex gap-2 px-3">
            ${
                canEdit
                ? (
                    task.status === "active"
                    ? `
                        <button class="btn btn-success btn-sm"
                            onclick="doneTask(this)">○</button>

                        <button class="btn btn-danger btn-sm"
                            onclick="openReasonModal(this)">×</button>
                    `
                    : `
                        <button class="btn btn-secondary btn-sm"
                            onclick="cancelTask(this)">取消</button>
                    `
                )
                : `
                    <button class="btn btn-secondary btn-sm disabled">
                        操作不可
                    </button>
                `
            }
        </div>

        </div>
        `;
        
    }
    goalList.innerHTML += `<h5 class="mt-3">タスク</h5>`;

    active.forEach(task=>{
        goalList.innerHTML += createCard(task);
    });

    goalList.innerHTML += `<hr><h5>達成済み</h5>`;

    completed.forEach(task=>{
        goalList.innerHTML += createCard(task);
    });

    goalList.innerHTML += `<hr><h5>未達成</h5>`;

    failed.forEach(task=>{
        goalList.innerHTML += createCard(task);
    });
}


// 完了処理
async function doneTask(btn) {

    const card = btn.closest("[data-id]");
    const id = card.dataset.id;

    await fetch(`/api/tasks/${id}/status`, {
        method: "POST",
        headers: { "Content-Type": "application/json" },
        body: JSON.stringify({ status: "completed" })
    });
    await loadDayData();
}

// 未達成理由
let currentTaskId = null;

function openReasonModal(btn) {
    const card = btn.closest("[data-id]");
    currentTaskId = card.dataset.id;

    new bootstrap.Modal(
        document.getElementById("reasonModal")
    ).show();
}

async function registerReason() {

    const selected = document.querySelector('input[name="reason"]:checked');

    if (!selected) {
        alert("未達成理由を選択してください");
        return;
    }

    await fetch(`/api/tasks/${currentTaskId}/status`, {
        method: "POST",
        headers: { "Content-Type": "application/json" },
        body: JSON.stringify({
            status: "failed",
            content: selected.value
        })
    });

    bootstrap.Modal.getInstance(
    document.getElementById("reasonModal")
    ).hide();

    selected.checked = false;

    await loadDayData();
}

// 取消
async function cancelTask(btn) {

    const card = btn.closest("[data-id]");
    const id = card.dataset.id;

    await fetch(`/api/tasks/${id}/status`, {
        method: "POST",
        headers: { "Content-Type": "application/json" },
        body: JSON.stringify({ status: "active" })
    });

    await loadDayData();
}

// モーダルリセット
document.getElementById("reasonModal")
.addEventListener("hidden.bs.modal", () => {

    const checked = document.querySelector('input[name="reason"]:checked');
    if (checked) checked.checked = false;
});

</script>

</body>
</html>