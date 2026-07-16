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

                    <button class="btn btn-success" onclick="changeGoal()">
                        切替
                    </button>
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
        const homeParams = new URLSearchParams(window.location.search);
        const selectedHomeDate = homeParams.get("date") || formatLocalDate(new Date());
        const selectedGroupId = homeParams.get("group_id");

        if (selectedGroupId) {
            localStorage.setItem("group_id", selectedGroupId);
        }

        document.addEventListener("DOMContentLoaded", initHome);
        document.addEventListener("DOMContentLoaded", loadChart);

        // 切替ボタンでの切替で使用
        let currentTaskId = null;
        let isGroupMode = false;

        async function initHome() {
            if (selectedGroupId) {
                isGroupMode = true;
                const title = document.getElementById("goalTitle");

                if (title) {
                    title.textContent = "本日のスケジュール";
                }

                await loadGroupGoals();
                return;
            }

            await loadTodayGoals();
        }

        async function loadTodayGoals() {
            const userId = localStorage.getItem("user_id");
            const params = new URLSearchParams({
                user_id: userId,
                date: selectedHomeDate
            });
            const res = await fetch(`/api/home/tasks?${params.toString()}`);
            const data = await res.json();
            const tasks = data.tasks || [];

            createGoals(tasks);
            await loadChart(tasks);
        }

        async function loadChart(tasks = null) {
            if (!tasks) {
                const userId = localStorage.getItem("user_id");
                const res = await fetch(`/api/home/tasks?user_id=${userId}`);
                const data = await res.json();
                tasks = data.tasks || [];
            }

            renderChart(tasks);
        }

        function createGoals(tasks, options = {}) {
            const heading = options.heading || "今日のタスク";
            const readonly = options.readonly || false;

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
                    <div class="fw-bold">${task.title}</div>

                    <div class="small text-muted">
                        ${formatTime(task.start_time)}
                        /
                        ${task.required_minutes ?? 0}分
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
                    task.status === "active" && !readonly
                    ? `
                        <button class="btn btn-success btn-sm"
                            onclick="doneTask(this)">○</button>

                        <button class="btn btn-danger btn-sm"
                            onclick="openReasonModal(this)">×</button>
                      `
                    : readonly
                    ? ""
                    : `
                        <button class="btn btn-secondary btn-sm"
                            onclick="cancelTask(this)">取消</button>
                      `
                }
            </div>

        </div>
        `;
    }

    goalList.innerHTML += `<h5 class="mt-3">${heading}</h5>`;

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

    if(tasks.length === 0){
        goalList.innerHTML += `
            <div class="text-center text-muted small py-4">
                表示できる目標がありません
            </div>
        `;
    }

}

        async function loadGroupGoals() {
            const groupId = selectedGroupId || localStorage.getItem("group_id");
            const userId = localStorage.getItem("user_id");
            const goalList = document.getElementById("goalList");

            goalList.innerHTML = `
                <div class="text-center text-muted small py-4">
                    読み込み中...
                </div>
            `;

            if(!groupId || !userId){
                createGoals([], {
                    heading: "今日のグループ目標",
                    readonly: true
                });
                return;
            }

            const params = new URLSearchParams({
                group_id: groupId,
                user_id: userId
            });
            const response = await fetch(`/api/grouptasks?${params.toString()}`);
            const data = await response.json();

            const todayTasks = (data.tasks || [])
                .filter(isTodayGroupTask)
                .sort((a, b) => (a.start_time || "").localeCompare(b.start_time || ""));

            createGoals(todayTasks, {
                heading: "今日のグループ目標",
                readonly: true
            });
        }

        function isTodayGroupTask(task) {
            const targetDate = parseLocalDate(selectedHomeDate);
            const todayDate = formatLocalDate(targetDate);
            const dayNames = ["日", "月", "火", "水", "木", "金", "土"];
            const todayDayName = dayNames[targetDate.getDay()];
            const todayDayNumber = String(targetDate.getDay() === 0 ? 7 : targetDate.getDay());

            if(task.start_date && task.start_date.slice(0, 10) > todayDate){
                return false;
            }

            if(task.end_date && task.end_date.slice(0, 10) < todayDate){
                return false;
            }

            const weekDays = normalizeWeekDays(task.week_days);

            return weekDays.length === 0
                || weekDays.includes(todayDayName)
                || weekDays.includes(todayDayNumber);
        }

        function formatLocalDate(date) {
            const year = date.getFullYear();
            const month = String(date.getMonth() + 1).padStart(2, "0");
            const day = String(date.getDate()).padStart(2, "0");

            return `${year}-${month}-${day}`;
        }

        function parseLocalDate(value) {
            const [year, month, day] = value.split("-").map(Number);
            return new Date(year, month - 1, day);
        }

        function normalizeWeekDays(value) {
            if(!value){
                return [];
            }

            if(Array.isArray(value)){
                return value.map(day => String(day));
            }

            try{
                const parsed = JSON.parse(value);
                if(Array.isArray(parsed)){
                    return parsed.map(day => String(day));
                }
            }catch(e){
                // カンマ区切りで保存されている古いデータにも対応する
            }

            return String(value)
                .split(",")
                .map(day => day.trim())
                .filter(Boolean);
        }

        function formatTime(time) {
            if (!time) return '';
            return time.slice(0, 5);
        }

        async function doneTask(btn) {
            const card = btn.closest("[data-id]");
            const id = card.dataset.id;

            if (isGroupMode) {
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

            if (isGroupMode) {
                await loadGroupGoals();
                await loadGroupChart();
            } else {
                await loadTodayGoals();
                await loadChart();
            }
        }

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
                alert("理由を選択してください");
                return;
            }

            if (isGroupMode) {
                await fetch(`/api/grouptasks/${currentTaskId}`, {
                    method: "PUT",
                    headers: {
                        "Content-Type": "application/json"
                    },
                    body: JSON.stringify({
                        status: "failed",
                        content: selected.value
                    })
                });
            } else {
                await fetch(`/api/tasks/${currentTaskId}/status`, {
                    method: "POST",
                    headers: {
                        "Content-Type": "application/json"
                    },
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

            if (isGroupMode) {
                await loadGroupGoals();
                await loadGroupChart();
            } else {
                await loadTodayGoals();
                await loadChart();
            }
        }

        async function cancelTask(btn) {
            const card = btn.closest("[data-id]");
            const id = card.dataset.id;

            if (isGroupMode) {
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

            if (isGroupMode) {
                await loadGroupGoals();
                await loadGroupChart();
            } else {
                await loadTodayGoals();
                await loadChart();
            }
        }

        async function loadGroupChart() {
            try {
                // ユーザーが属するグループを取得
                const res = await fetch('/api/groups');
                const groupData = await res.json();
                const groups = Array.isArray(groupData) ? groupData : (groupData.groups || []);

                if (!groups.length) {
                    renderChart([]);
                    return;
                }

                // 全グループのタスクを取得
                let allTasks = [];
                for (const group of groups) {
                    const taskRes = await fetch(`/api/grouptasks?group_id=${group.id}`);
                    const taskData = await taskRes.json();
                    const tasks = Array.isArray(taskData) ? taskData : (taskData.tasks || []);

                    // 本日のタスクでフィルタリング
                    const today = new Date();
                    const todayStr = today.toISOString().split('T')[0];
                    const todayDow = today.getDay();
                    const dayMap = ['日', '月', '火', '水', '木', '金', '土'];
                    const todayDay = dayMap[todayDow];

                    const filteredTasks = tasks.filter(t => {
                        // 開始日のチェック
                        if (t.start_date && t.start_date > todayStr) return false;
                        // 終了日のチェック
                        if (t.end_date && t.end_date < todayStr) return false;

                        // 曜日のチェック
                        if (t.week_days && t.week_days.trim() !== '') {
                            const days = t.week_days.split(',').map(s => s.trim());
                            return days.includes(todayDay) || days.some(d => Number(d) === todayDow);
                        }

                        return true;
                    });

                    allTasks = allTasks.concat(filteredTasks);
                }

                renderChart(allTasks);
            } catch (e) {
                console.error('Error loading group chart:', e);
                renderChart([]);
            }
        }


        function timeToMin(t) {
            if (!t) return 0;

            const [h, m] = t.split(":").map(Number);
            return h * 60 + m;
        }

        function renderChart(tasks) {
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

                const length = Math.sqrt(
                    (x2 - x1) ** 2 +
                    (y2 - y1) ** 2
                );

                const angleDeg =
                    Math.atan2(y2 - y1, x2 - x1) * 180 / Math.PI;

                line.style.position = "absolute";
                line.style.left = `${x1}px`;
                line.style.top = `${y1}px`;

                line.style.width = `${length}px`;
                line.style.height = isMain ? "1.5px" : "1px";

                line.style.background =
                    isMain ? "#495057" : "#ced4da";

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

                const color = task?.color ?? "#198754";

                gradients.push(`${color} ${startP}% ${endP}%`);
                current = endP;
            });

            if (current < 100) {
                gradients.push(`#e9ecef ${current}% 100%`);
            }

            chart.style.background =
                `conic-gradient(${gradients.join(",")})`;

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
        async function changeGoal() {   // 切替ボタンでの切替で使用
            isGroupMode = !isGroupMode;

            const title = document.getElementById("goalTitle");

            if (isGroupMode) {
                title.textContent = "本日のグループ目標一覧";
                await loadGroupGoals();
                await loadGroupChart();
            } else {
                title.textContent = "本日の目標一覧";
                await loadTodayGoals();
                await loadChart();
            }
        }

        async function loadGroupGoals() {
            try {
                // ユーザーが属するグループを取得
                const res = await fetch('/api/groups');
                const groupData = await res.json();
                const groups = Array.isArray(groupData) ? groupData : (groupData.groups || []);

                if (!groups.length) {
                    createGoals([]);
                    return;
                }

                // 全グループのタスクを取得
                let allTasks = [];
                for (const group of groups) {
                    const taskRes = await fetch(`/api/grouptasks?group_id=${group.id}`);
                    const taskData = await taskRes.json();
                    const tasks = Array.isArray(taskData) ? taskData : (taskData.tasks || []);

                    // 本日のタスクでフィルタリング
                    const today = new Date();
                    const todayStr = today.toISOString().split('T')[0];
                    const todayDow = today.getDay();
                    const dayMap = ['日', '月', '火', '水', '木', '金', '土'];
                    const todayDay = dayMap[todayDow];

                    const filteredTasks = tasks.filter(t => {
                        // 開始日のチェック
                        if (t.start_date && t.start_date > todayStr) return false;
                        // 終了日のチェック
                        if (t.end_date && t.end_date < todayStr) return false;

                        // 曜日のチェック
                        if (t.week_days && t.week_days.trim() !== '') {
                            const days = t.week_days.split(',').map(s => s.trim());
                            return days.includes(todayDay) || days.some(d => Number(d) === todayDow);
                        }

                        return true;
                    });

                    allTasks = allTasks.concat(filteredTasks);
                }

                createGoals(allTasks);
            } catch (e) {
                console.error('Error loading group goals:', e);
                createGoals([]);
            }
        }

    </script>
</body>
</html>
