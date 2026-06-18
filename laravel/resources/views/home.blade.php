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
        document.addEventListener("DOMContentLoaded", loadTodayGoals);
        document.addEventListener("DOMContentLoaded", loadChart);

        let currentTaskId = null;

        async function loadTodayGoals() {
            const userId = localStorage.getItem("user_id");
            const res = await fetch(`/api/home/tasks?user_id=${userId}`);
            const data = await res.json();
            const tasks = data.tasks || [];

            createGoals(tasks);
        }

        function createGoals(tasks) {
            const goalList = document.getElementById("goalList");
            goalList.innerHTML = "";

            tasks.forEach(task => {
                goalList.innerHTML += `
                <div class="d-flex align-items-center justify-content-between bg-white shadow-sm rounded mb-2 overflow-hidden"
                    data-id="${task.id}">

                    <!-- 左：色 + 内容（目標一覧と同じ構造） -->
                    <div class="d-flex align-items-center flex-grow-1">

                        <!-- 左色バー（目標一覧と完全統一） -->
                        <div style="
                            width:8px;
                            align-self:stretch;
                            background:${task.color ?? '#198754'};
                        "></div>

                        <!-- タスク内容 -->
                        <div class="p-3">
                            <div class="fw-bold">${task.title}</div>

                            <div class="small text-muted">
                                ${task.start_time.slice(0,5)} / ${task.required_minutes}分 / ${task.priority ?? "未設定"}
                            </div>
                        </div>

                    </div>

                    <!-- 右：ボタン -->
                    <div class="d-flex gap-2 px-3">

                        <button class="btn btn-success btn-sm done-btn"
                            onclick="doneTask(this)">
                            ○
                        </button>

                        <button class="btn btn-danger btn-sm fail-btn"
                            onclick="openReasonModal(this)">
                            ×
                        </button>

                        <button class="btn btn-secondary btn-sm cancel-btn d-none"
                            onclick="cancelTask(this)">
                            取消
                        </button>

                    </div>

                </div>
                `;
            });
        }

        function formatTime(time) {
            if (!time) return '';
            return time.slice(0, 5);
        }

        async function doneTask(btn) {
            const card = btn.closest("[data-id]");
            const id = card.dataset.id;

            await fetch(`/api/tasks/${id}/status`, {
                method: "POST",
                headers: {
                    "Content-Type": "application/json"
                },
                body: JSON.stringify({
                    status: "completed"
                })
            });

            btn.classList.add("d-none");
            card.querySelector(".fail-btn").classList.add("d-none");
            card.querySelector(".cancel-btn").classList.remove("d-none");
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

            await fetch(`/api/tasks/${currentTaskId}/status`, {
                method: "POST",
                headers: {
                    "Content-Type": "application/json"
                },
                body: JSON.stringify({
                    status: "failed",
                    reason: selected.value
                })
            });

            const card = document.querySelector(`[data-id="${currentTaskId}"]`);

            card.querySelector(".done-btn").classList.add("d-none");
            card.querySelector(".fail-btn").classList.add("d-none");
            card.querySelector(".cancel-btn").classList.remove("d-none");

            bootstrap.Modal.getInstance(
                document.getElementById("reasonModal")
            ).hide();

            selected.checked = false;
        }

        async function cancelTask(btn) {
            const card = btn.closest("[data-id]");
            const id = card.dataset.id;

            await fetch(`/api/tasks/${id}/status`, {
                method: "POST",
                headers: {
                    "Content-Type": "application/json"
                },
                body: JSON.stringify({
                    status: "active"
                })
            });

            card.querySelector(".done-btn").classList.remove("d-none");
            card.querySelector(".fail-btn").classList.remove("d-none");
            card.querySelector(".cancel-btn").classList.add("d-none");
        }

        async function loadChart() {
            const userId = localStorage.getItem("user_id");

            const res = await fetch(`/api/home/tasks?user_id=${userId}`);
            const data = await res.json();

            const tasks = data.tasks || [];

            renderChart(tasks);
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
    </script>
</body>
</html>