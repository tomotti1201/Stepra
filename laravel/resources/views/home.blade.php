<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>STEPRA ホーム画面</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="bg-light">
    <script>
        if (!localStorage.getItem("user_id")) {
            location.href = "/login";
        }
    </script>

    <div class="container py-4 mb-5">

        <!-- タイトル -->
        <img src="{{ asset('image/tit.png') }}" class="mb-3" style="width:200px;">

        <!-- スケジュール -->
        <div class="card shadow mb-4">
            <div class="card-body text-center">
                <h4 class="fw-bold mb-4">
                    本日のスケジュール
                </h4>

                <div class="d-flex justify-content-center">
                    <div id="circleChart" class="position-relative" style="width:280px; height:280px; border-radius:50%;">
                    </div>
                </div>
            </div>
        </div>

        <!-- 目標タイトル -->
        <div class="card shadow mb-3">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <h5 id="goalTitle" class="fw-bold mb-0">
                        本日の目標一覧
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

                    <button type="button" class="btn-close" data-bs-dismiss="modal">
                    </button>
                </div>

                <div class="modal-body">
                    <div class="form-check mb-2">
                        <input class="form-check-input" type="radio" name="reason" value="急な用事">
                        <label class="form-check-label">急な用事が入った</label>
                    </div>

                    <div class="form-check mb-2">
                        <input class="form-check-input" type="radio" name="reason" value="仮眠">
                        <label class="form-check-label">仮眠をしすぎた</label>
                    </div>

                    <div class="form-check mb-2">
                        <input class="form-check-input" type="radio" name="reason" value="忘れた">
                        <label class="form-check-label">他ごとをしていて忘れていた</label>
                    </div>

                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="reason" value="やる気">
                        <label class="form-check-label">やる気がなかった</label>
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

// 初期設定
document.addEventListener("DOMContentLoaded", async()=>{

    await loadUserGroups();
    await loadTodayGoals();

});

// 状態管理
let currentTaskId = null;
let isGroupMode = false;

// グループ切替用
let userGroups = [];
let currentGroupIndex = -1;

// グループ取得
async function loadUserGroups(){

    const userId = localStorage.getItem("user_id");

    const res = await fetch(
        `/api/user/groups?user_id=${userId}`
    );

    const data = await res.json();

    userGroups = data.groups || [];

    console.log(userGroups);

    updateArrow();

}

// 通常タスク取得
async function loadTodayGoals(){

    const userId = localStorage.getItem("user_id");

    const res = await fetch(`/api/home/tasks?user_id=${userId}`);

    const data = await res.json();

    const tasks = data.tasks || [];

    createGoals(tasks);

    await loadChart(tasks);

}

// チャート読み込み
async function loadChart(tasks = null){

    if(!tasks){

        const userId = localStorage.getItem("user_id");

        const res = await fetch(`/api/home/tasks?user_id=${userId}`);

        const data = await res.json();

        tasks = data.tasks || [];

    }

    renderChart(tasks);

}

// 通常タスク表示
function createGoals(tasks){

    const goalList = document.getElementById("goalList");

    goalList.innerHTML = "";

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

                    <div class="fw-bold">
                        ${task.title}
                    </div>

                    <div class="small text-muted">
                        ${task.start_time.slice(0,5)}
                        /
                        ${task.required_minutes}分
                        /
                        ${task.priority ?? "未設定"}
                    </div>

                    ${
                        task.status === "failed"
                        ?
                        `
                        <div class="text-danger small mt-1">
                            理由：
                            ${task.content ?? ""}
                        </div>
                        `
                        :
                        ""
                    }

                </div>

            </div>

            <div class="d-flex gap-2 px-3">

                ${
                    task.status === "active"
                    ?
                    `
                    <button class="btn btn-success btn-sm"
                    onclick="doneTask(this)">
                    ○
                    </button>

                    <button class="btn btn-danger btn-sm"
                    onclick="openReasonModal(this)">
                    ×
                    </button>
                    `
                    :
                    `
                    <button class="btn btn-secondary btn-sm"
                    onclick="cancelTask(this)">
                    取消
                    </button>
                    `
                }

            </div>

        </div>

        `;

    }


    goalList.innerHTML += `<h5 class="mt-3">今日のタスク</h5>`;

    if(tasks.length === 0){

        goalList.innerHTML += `
            <p class="text-muted">
                本日のタスクはありません
            </p>
        `;

    }else{

        active.forEach(task=>{
            goalList.innerHTML += createCard(task);
        });

    }

    if(completed.length > 0){

        goalList.innerHTML += `
            <hr>
            <h5>
                達成済み
            </h5>
        `;

        completed.forEach(task=>{

            goalList.innerHTML += createCard(task);

        });

    }


    if(failed.length > 0){

        goalList.innerHTML += `
            <hr>
            <h5>
                未達成
            </h5>
        `;

        failed.forEach(task=>{

            goalList.innerHTML += createCard(task);

        });

    }

}
// 補助関数
function formatTime(time){

    if(!time) return '';

    return time.slice(0,5);

}

// タスク完了処理
async function doneTask(btn){

    const card = btn.closest("[data-id]");

    const id = card.dataset.id;

    if(isGroupMode){

        await fetch(`/api/grouptasks/${id}`,{

            method:"PUT",

            headers:{
                "Content-Type":"application/json"
            },

            body:JSON.stringify({
                status:"completed"
            })

        });

    }else{

        await fetch(`/api/tasks/${id}/status`,{

            method:"POST",

            headers:{
                "Content-Type":"application/json"
            },

            body:JSON.stringify({
                status:"completed"
            })

        });

    }

    if(isGroupMode){

        await loadGroupGoals();
        await loadGroupChart();

    }else{

        await loadTodayGoals();
        await loadChart();

    }

}

// 未達成理由モーダル
function openReasonModal(btn){

    const card = btn.closest("[data-id]");

    currentTaskId = card.dataset.id;

    new bootstrap.Modal(
        document.getElementById("reasonModal")
    ).show();

}

// 未達成登録
async function registerReason(){

    const selected =
        document.querySelector(
            'input[name="reason"]:checked'
        );

    if(!selected){

        alert("理由を選択してください");

        return;

    }

    if(isGroupMode){

        await fetch(`/api/grouptasks/${currentTaskId}`,{

            method:"PUT",

            headers:{
                "Content-Type":"application/json"
            },

            body:JSON.stringify({
                status:"failed",
                content:selected.value
            })

        });

    }else{

        await fetch(`/api/tasks/${currentTaskId}/status`,{

            method:"POST",

            headers:{
                "Content-Type":"application/json"
            },

            body:JSON.stringify({
                status:"failed",
                content:selected.value
            })

        });

    }

    bootstrap.Modal.getInstance(
        document.getElementById("reasonModal")
    ).hide();

    selected.checked = false;

    if(isGroupMode){

        await loadGroupGoals();
        await loadGroupChart();

    }else{

        await loadTodayGoals();
        await loadChart();

    }

}

// タスク取消
async function cancelTask(btn){

    const card = btn.closest("[data-id]");

    const id = card.dataset.id;

    if(isGroupMode){

        await fetch(`/api/grouptasks/${id}`,{

            method:"PUT",

            headers:{
                "Content-Type":"application/json"
            },

            body:JSON.stringify({
                status:"active"
            })

        });

    }else{

        await fetch(`/api/tasks/${id}/status`,{

            method:"POST",

            headers:{
                "Content-Type":"application/json"
            },

            body:JSON.stringify({
                status:"active"
            })

        });

    }

    if(isGroupMode){

        await loadGroupGoals();
        await loadGroupChart();

    }else{

        await loadTodayGoals();
        await loadChart();

    }

}

// グループチャート取得
async function loadGroupChart(){

    try{

        const res = await fetch('/api/user/groups');

        const groupData = await res.json();

        const groups =
            Array.isArray(groupData)
            ? groupData
            : (groupData.groups || []);

        if(!groups.length){

            renderChart([]);

            return;

        }

        let allTasks = [];

        for(const group of groups){

            const taskRes =
                await fetch(`/api/grouptasks?group_id=${group.id}`);

            const taskData = await taskRes.json();

            const tasks =
                Array.isArray(taskData)
                ? taskData
                : (taskData.tasks || []);

            const today = new Date();

            const todayStr =
                today.toISOString().split('T')[0];

            const todayDow =
                today.getDay();

            const dayMap = [
                '日',
                '月',
                '火',
                '水',
                '木',
                '金',
                '土'
            ];

            const todayDay =
                dayMap[todayDow];

            const filteredTasks =
                tasks.filter(t=>{

                    if(
                        t.start_date &&
                        t.start_date > todayStr
                    ){

                        return false;

                    }

                    if(
                        t.end_date &&
                        t.end_date < todayStr
                    ){

                        return false;

                    }

                    if(
                        t.week_days &&
                        t.week_days.trim() !== ''
                    ){

                        const days =
                            t.week_days
                            .split(',')
                            .map(s=>s.trim());

                        return (
                            days.includes(todayDay)
                            ||
                            days.some(
                                d=>Number(d)===todayDow
                            )
                        );

                    }

                    return true;

                });

            allTasks =
                allTasks.concat(filteredTasks);

        }

        renderChart(allTasks);

    }
    catch(e){

        console.error(
            'Error loading group chart:',
            e
        );

        renderChart([]);

    }

}
// 時間変換
function timeToMin(t){

    if(!t){
        return 0;
    }

    const [h,m] = t.split(":").map(Number);

    return h * 60 + m;

}

// 円形チャート描画
function renderChart(tasks){

    const chart = document.getElementById("circleChart");

    chart.innerHTML = "";

    // 時計数字
    for(let h = 0; h < 24; h++){

        const angle = (h / 24) * 360 - 90;

        const radius = 150;

        const x =
            140 +
            radius *
            Math.cos(angle * Math.PI / 180);

        const y =
            140 +
            radius *
            Math.sin(angle * Math.PI / 180);

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

    // 目盛線
    for(let h = 0; h < 24; h++){

        const angle =
            (h / 24) * 360 - 90 - 0.35;

        const isMain = h % 6 === 0;

        const innerRadius = 85;
        const outerRadius = 140;

        const x1 =
            140 +
            innerRadius *
            Math.cos(angle * Math.PI / 180);

        const y1 =
            140 +
            innerRadius *
            Math.sin(angle * Math.PI / 180);

        const x2 =
            140 +
            outerRadius *
            Math.cos(angle * Math.PI / 180);

        const y2 =
            140 +
            outerRadius *
            Math.sin(angle * Math.PI / 180);

        const line = document.createElement("div");

        const length =
            Math.sqrt(
                (x2-x1)**2 +
                (y2-y1)**2
            );

        const angleDeg =
            Math.atan2(
                y2-y1,
                x2-x1
            )
            *
            180
            /
            Math.PI;

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

    tasks.forEach(task=>{

        const start = timeToMin(task.start_time);

        const end =
            start +
            (task.required_minutes || 0);

        totalMinutes += task.required_minutes || 0;

        const startP = (start / DAY) * 100;

        const endP = (end / DAY) * 100;

        if(startP > current){

            gradients.push(
                `#e9ecef ${current}% ${startP}%`
            );

        }

        const color =
            task?.color ??
            "#198754";

        gradients.push(
            `${color} ${startP}% ${endP}%`
        );

        current = endP;

    });

    if(current < 100){

        gradients.push(
            `#e9ecef ${current}% 100%`
        );

    }

    chart.style.background =
        `conic-gradient(${gradients.join(",")})`;

    const hours =
        Math.floor(totalMinutes / 60);

    const minutes =
        totalMinutes % 60;

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
        <div style="font-size:18px;">
            合計
        </div>
        <div style="font-size:16px;">
            ${hours}時間 ${minutes}分
        </div>
    `;

    chart.appendChild(center);

}

// グループ切替
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

        await loadTodayGoals();

        document.getElementById("goalTitle")
            .textContent =
            "本日の目標一覧";

    }
    else{

        await showCurrentGroup();

    }

    updateArrow();

}

async function showCurrentGroup(){

    const group = userGroups[currentGroupIndex];

    document.getElementById("goalTitle")
        .textContent =
        group.name + " の目標一覧";

    await loadGroupGoals(group.id);

}

// 矢印表示制御
function updateArrow(){

    const prev =
        document.getElementById("prevBtn");

    const next =
        document.getElementById("nextBtn");

    // 左矢印
    if(currentGroupIndex === -1){

        prev.style.visibility = "hidden";

    }
    else{

        prev.style.visibility = "visible";

    }


    // 右矢印
    if(currentGroupIndex >= userGroups.length - 1){

        next.style.visibility = "hidden";

    }
    else{

        next.style.visibility = "visible";

    }
}
// グループタスク表示

function createGroupGoals(tasks){

    const goalList = document.getElementById("goalList");

    goalList.innerHTML = "";

    if(tasks.length === 0){

        goalList.innerHTML = `
            <h5 class="mt-3">
                今日のグループタスク
            </h5>

            <p class="text-muted">
                本日のグループタスクはありません
            </p>
        `;

        return;

    }

    const active =
        tasks.filter(t => t.status === "active");

    const completed =
        tasks.filter(t => t.status === "completed");

    const failed =
        tasks.filter(t => t.status === "failed");

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

                    <div class="fw-bold">
                        ${task.title}
                    </div>

                    <div class="small text-muted">
                        ${formatTime(task.start_time)}
                        /
                        ${task.required_minutes ?? 0}分
                        /
                        ${task.priority ?? "未設定"}
                    </div>

                    ${
                        task.status === "failed"
                        ?
                        `
                        <div class="text-danger small mt-1">
                            理由：
                            ${task.content ?? ""}
                        </div>
                        `
                        :
                        ""
                    }

                </div>

            </div>

            <div class="d-flex gap-2 px-3">

                ${
                    task.status === "active"
                    ? `
                        <button class="btn btn-success btn-sm"
                            onclick="doneTask(this)">○</button>

                        <button class="btn btn-danger btn-sm"
                            onclick="openReasonModal(this)">×</button>
                      `
                    : 
                      `
                        <button class="btn btn-secondary btn-sm"
                            onclick="cancelTask(this)">取消</button>
                      `
                }

            </div>

        </div>
        `;

    }

    goalList.innerHTML += `
        <h5 class="mt-3">
            今日のグループタスク
        </h5>
    `;

    active.forEach(task=>{

        goalList.innerHTML += createCard(task);

    });

    if(completed.length > 0){

        goalList.innerHTML += `
            <hr>

            <h5>
                達成済み
            </h5>
        `;

        completed.forEach(task=>{

            goalList.innerHTML += createCard(task);

        });

    }

    if(failed.length > 0){

        goalList.innerHTML += `
            <hr>

            <h5>
                未達成
            </h5>
        `;

        failed.forEach(task=>{

            goalList.innerHTML += createCard(task);

        });

    }

}


// グループタスク取得

async function loadGroupGoals(groupId){

    try{

        const today = new Date();

        const todayStr =
            today.toISOString().split('T')[0];

        const res =
            await fetch(
                `/api/groups/grouptasks/daily?group_id=${groupId}&date=${todayStr}`
            );

        const data =
            await res.json();

        const tasks =
            Array.isArray(data)
            ?
            data
            :
            data.tasks || [];

        createGroupGoals(tasks);

        renderChart(tasks);

    }
    catch(e){

        console.error(e);

        createGroupGoals([]);

        renderChart([]);

    }

}
</script>
</body>
</html>
