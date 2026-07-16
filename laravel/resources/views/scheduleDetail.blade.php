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


                <div>

                    <button id="prevBtn"
                    class="btn btn-success"
                    onclick="prevGroup()">
                        ←
                    </button>


                    <button id="nextBtn"
                    class="btn btn-success"
                    onclick="nextGroup()">
                        →
                    </button>

                </div>

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
    // グループ切替
let userGroups = [];
let currentGroupIndex = -1;
function moveDate(diff){

    const date = new Date(dateStr);

    date.setDate(date.getDate() + diff);

    const y = date.getFullYear();
    const m = String(date.getMonth() + 1).padStart(2,"0");
    const d = String(date.getDate()).padStart(2,"0");

    const params = new URLSearchParams();
    params.set('date', `${y}-${m}-${d}`);
    if (typeof groupId !== 'undefined' && groupId) {
        params.set('group_id', groupId);
    }

    location.href = `/scheduleDetail?${params.toString()}`;
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

// 一昨日・昨日・今日だけ操作可能
const canEdit = [2, 1, 0].includes(diffDays);

// Detect group context
let groupId = urlParams.get("group_id");

// 初期ロード
document.addEventListener("DOMContentLoaded", async()=>{

    await loadUserGroups();

    if(groupId){
        loadGroupDayData();
    }else{
        loadDayData();
    }

});
async function loadUserGroups(){

    const userId = localStorage.getItem("user_id");


    const res = await fetch(
        `/api/user/groups?user_id=${userId}`
    );


    const data = await res.json();


    userGroups = data.groups || [];


    updateArrow();


}
function updateArrow(){

    const prev =
        document.getElementById("prevBtn");

    const next =
        document.getElementById("nextBtn");


    if(currentGroupIndex === -1){

        prev.style.visibility = "hidden";

    }
    else{

        prev.style.visibility = "visible";

    }



    if(currentGroupIndex >= userGroups.length - 1){

        next.style.visibility = "hidden";

    }
    else{

        next.style.visibility = "visible";

    }

}
async function nextGroup(){

    if(userGroups.length === 0){
        return;
    }


    if(currentGroupIndex < userGroups.length - 1){

        currentGroupIndex++;

        await showCurrentGroup();

    }


    updateArrow();

}
async function prevGroup(){

    if(currentGroupIndex === -1){
        return;
    }

    currentGroupIndex--;

    if(currentGroupIndex === -1){

    groupId = null;

    document.getElementById("goalTitle")
    .textContent =
    "個人目標一覧";

    await loadDayData();

    }else{

        await showCurrentGroup();

    }


    updateArrow();

}
async function showCurrentGroup(){

    const group =
        userGroups[currentGroupIndex];


    document.getElementById("goalTitle")
    .textContent =
    group.name + " の目標一覧";


    await loadGroupDayData(group.id);

}
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

async function loadGroupDayData(selectedGroupId=null) {

    if(selectedGroupId){

        groupId = selectedGroupId;

    }
    const date = dateStr;
    try {
        const resp = await fetch(`/api/grouptasks?group_id=${groupId}`, { headers: { Accept: 'application/json' } });
        if (!resp.ok) {
            createChart([]);
            createGoals([]);
            return;
        }

        const data = await resp.json();
        const tasks = Array.isArray(data) ? data : data.tasks || [];

        // filter tasks by date range and weekdays
        const filtered = tasks.filter(t => {
            const start = t.start_date ? new Date(t.start_date + 'T00:00:00') : null;
            const end = t.end_date ? new Date(t.end_date + 'T00:00:00') : null;
            const target = new Date(date + 'T00:00:00');

            if (start && target < start) return false;
            if (end && target > end) return false;

            // weekdays check
            const days = normalizeWeekDays(t.week_days);
            if (days.length > 0) {
                const week = target.getDay();
                const isoWeek = week === 0 ? 7 : week;
                return days.some(d => {
                    const map = { '日':0,'月':1,'火':2,'水':3,'木':4,'金':5,'土':6 };
                    return map[d] === week || Number(d) === isoWeek;
                });
            }

            return true;
        });

        // transform into schedule-like objects for createGoals
        const scheduleLike = filtered.map(t => ({
            id: t.id,
            status: t.status || 'active',
            start_time: t.start_time || '00:00',
            required_minutes: t.required_minutes || 0,
            priority: t.priority || null,
            content: t.content || null,
            color: t.color || '#198754',
            title: t.title
        }));

        // hide change button for group view
        const btn = document.querySelector('#goalTitle').nextElementSibling;
        if (btn && btn.tagName === 'BUTTON') btn.style.display = 'none';

        createChart(scheduleLike);
        createGoals(scheduleLike);

    } catch (e) {
        console.error(e);
        createChart([]);
        createGoals([]);
    }
}

// 時間変換
function normalizeWeekDays(value) {
    if (!value) {
        return [];
    }

    if (Array.isArray(value)) {
        return value.map(day => String(day));
    }

    try {
        const parsed = JSON.parse(value);
        if (Array.isArray(parsed)) {
            return parsed.map(day => String(day));
        }
    } catch (e) {
        // comma separated values are handled below.
    }

    return String(value)
        .split(",")
        .map(day => day.trim())
        .filter(Boolean);
}

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
    
    // 操作不可の日付でも、完了済み/未達成は段階として表示する
    let active, completed, failed;
    if (canEdit) {
        active = tasks.filter(t => t.status === "active");
        completed = tasks.filter(t => t.status === "completed");
        failed = tasks.filter(t => t.status === "failed");
    } else {
        active = tasks.filter(t => t.status === "active");
        completed = tasks.filter(t => t.status === "completed");
        failed = tasks.filter(t => t.status === "failed");
    }
    
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

    if (completed.length > 0 || failed.length > 0) {
        goalList.innerHTML += `<hr><h5>達成済み</h5>`;

        completed.forEach(task=>{
            goalList.innerHTML += createCard(task);
        });

        goalList.innerHTML += `<hr><h5>未達成</h5>`;

        failed.forEach(task=>{
            goalList.innerHTML += createCard(task);
        });
    }
}


// 完了処理
async function doneTask(btn) {

    const card = btn.closest("[data-id]");
    const id = card.dataset.id;

    if (groupId) {
        await fetch(`/api/grouptasks/${id}`, {
            method: "PUT",
            headers: { "Content-Type": "application/json" },
            body: JSON.stringify({ status: "completed" })
        });
    } else {
        await fetch(`/api/tasks/${id}/status`, {
            method: "POST",
            headers: { "Content-Type": "application/json" },
            body: JSON.stringify({ status: "completed" })
        });
    }
    
    if (groupId) {
        await loadGroupDayData();
    } else {
        await loadDayData();
    }
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

    if (groupId) {
        await fetch(`/api/grouptasks/${currentTaskId}`, {
            method: "PUT",
            headers: { "Content-Type": "application/json" },
            body: JSON.stringify({
                status: "failed",
                content: selected.value
            })
        });
    } else {
        await fetch(`/api/tasks/${currentTaskId}/status`, {
            method: "POST",
            headers: { "Content-Type": "application/json" },
            body: JSON.stringify({
                status: "failed",
                content: selected.value
            })
        });
    }

    bootstrap.Modal.getInstance(
    document.getElementById("reasonModal")
    ).hide();

    selected.checked = false;

    if (groupId) {
        await loadGroupDayData();
    } else {
        await loadDayData();
    }
}

// 取消
async function cancelTask(btn) {

    const card = btn.closest("[data-id]");
    const id = card.dataset.id;

    if (groupId) {
        await fetch(`/api/grouptasks/${id}`, {
            method: "PUT",
            headers: { "Content-Type": "application/json" },
            body: JSON.stringify({ status: "active" })
        });
    } else {
        await fetch(`/api/tasks/${id}/status`, {
            method: "POST",
            headers: { "Content-Type": "application/json" },
            body: JSON.stringify({ status: "active" })
        });
    }

    if (groupId) {
        await loadGroupDayData();
    } else {
        await loadDayData();
    }
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
