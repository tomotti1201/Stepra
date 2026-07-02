<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>目標新規作成 | STEPRA</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
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


    <div class="container py-4 mb-5">

        <img src="{{ asset('image/tit.png') }}" class="mb-3" style="width:200px;">

        <!--<div class="row justify-content-center">-->

            <!--<div class="col-12 col-md-8 col-lg-5"> -->

                <div class="card-body">

                    <h2 class="text-center fw-bold ">目標新規作成</h2>

                    <div class="mb-3">
                        <label class="form-label fw-bold ">目標名</label>
                        <input type="text" class="form-control" id="goal-name" placeholder="目標名を入力">
                    </div>

                    <div class="mb-3">

                        <label class="form-label fw-bold ">頻度</label>

                        <div class="d-flex gap-2 mb-2">

                            <button type="button" id="everyday-btn" class="btn btn-outline-secondary day" onclick="selectEveryday()">毎日</button>

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
                            <label class="form-label fw-bold ">開始時間</label>
                            <input type="time" class="form-control" id="start-timing" placeholder="10:00" value="10:00">
                        </div>

                        <div class="col-6">

                            <label class="form-label fw-bold ">所要時間</label>

                            <div class="d-flex gap-1">
                                <input type="number" class="form-control" id="duration-hours" placeholder="0" min="0" value="0">
                                <span class="align-self-center small">時間</span>
                                <input type="number" class="form-control" id="duration-minutes" placeholder="30" min="0" max="59" value="0">
                                <span class="align-self-center small">分</span>
                            </div>

                        </div>

                    </div>

                    <div class="mb-3 border p-3 rounded bg-light">

                        <label class="form-label fw-bold ">モード設定</label>

                        <div class="btn-group w-100 mb-2" id="mode-group">
                            <button type="button" class="btn btn-outline-secondary day active" onclick="selectMode(this)">自由設定</button>
                            <button type="button" class="btn btn-outline-secondary day" onclick="selectMode(this)">優先順位</button>
                        </div>

                        <div id="priority-box" class="disabled-group">

                            <label class="form-label ">優先度</label>

                            <div class="btn-group w-100" id="priority-group">
                                <button type="button" class="btn btn-outline-secondary day" onclick="selectSingle(this)">高</button>
                                <button type="button" class="btn btn-outline-secondary day" onclick="selectSingle(this)">中</button>
                                <button type="button" class="btn btn-outline-secondary day" onclick="selectSingle(this)">低</button>
                            </div>

                        </div>

                    </div>

                    <div class="row g-2 mb-3">

                        <div class="col-6">
                            <label class="form-label fw-bold ">開始日</label>
                            <input type="date" class="form-control" id="start-date">
                        </div>

                        <div class="col-6">
                            <label class="form-label fw-bold ">終了日</label>
                            <input type="date" class="form-control" id="end-date">
                        </div>

                    </div>

                    <div class="mb-4">

                        <label class="form-label fw-bold small">目標カラー</label>

                        <div class="color-selection" id="color-group">

                            <div class="color-circle active-color"
                                data-color="#0d6efd"
                                style="background:#0d6efd"
                                onclick="selectColor(this)">
                            </div>

                            <div class="color-circle"
                                data-color="#198754"
                                style="background:#198754"
                                onclick="selectColor(this)">
                            </div>

                            <div class="color-circle"
                                data-color="#dc3545"
                                style="background:#dc3545"
                                onclick="selectColor(this)">
                            </div>

                            <div class="color-circle"
                                data-color="#ffc107"
                                style="background:#ffc107"
                                onclick="selectColor(this)">
                            </div>

                            <div class="color-circle"
                                data-color="#6f42c1"
                                style="background:#6f42c1"
                                onclick="selectColor(this)">
                            </div>

                            <div class="color-circle custom"
                                id="add-color-btn"
                                onclick="selectCustomColor()">
                                ＋
                            </div>

                            <input type="color"
                                id="custom-color-picker"
                                style="display:none;"
                                onchange="addCustomColor(this.value)">

                        </div>
                    </div>
                    <div>
                        <button type="button" class="btn btn-success w-100 mb-2" onclick="saveGoal()">登録する</button>
                        <button type="button" class="btn btn-secondary w-100" onclick="location.href='/task'">キャンセル</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <x-menubar />

    <script>
        // JavaScript functions for handling UI interactions
        function selectMode(element) {
            selectSingle(element);

            const priorityBox = document.getElementById("priority-box");

            priorityBox.className =
                element.innerText === "優先順位"
                    ? ""
                    : "disabled-group";

            if (element.innerText === "自由設定") {
                document.querySelectorAll("#priority-group .day")
                    .forEach(el => el.classList.remove("active"));
            }
        }

        function selectSingle(element) {
            const parent = element.parentElement;

            parent.querySelectorAll(".day")
                .forEach(el => el.classList.remove("active"));

            element.classList.add("active");
        }

        let selectedColor = "#0d6efd";

        function selectColor(element) {

            document
                .querySelectorAll(".color-circle")
                .forEach(circle =>
                    circle.classList.remove("selected")
                );

            element.classList.add("selected");

            selectedColor = element.dataset.color;
        }

        function addCustomColor(value) {

            if (!value) return;

            const colorGroup =
                document.getElementById("color-group");

            const addButton =
                document.getElementById("add-color-btn");

            const newColor =
                document.createElement("div");

            newColor.className =
                "color-circle selected";

            newColor.style.backgroundColor =
                value;

            newColor.dataset.color =
                value;

            newColor.onclick = function () {
                selectColor(this);
            };

            document
                .querySelectorAll(".color-circle")
                .forEach(circle =>
                    circle.classList.remove("selected")
                );

            colorGroup.insertBefore(
                newColor,
                addButton
            );

            selectedColor = value;
        }


        function selectCustomColor() {
            document.getElementById("custom-color-picker").click();
        }

        function updateCustomColor(value) {
            if (!value) return;

            const targetCircle = document.getElementById("current-color-circle");

            if (targetCircle) {
                targetCircle.style.backgroundColor = value;
                targetCircle.dataset.color = value;
            }
        }

        function toggleDay(element) {
            element.classList.toggle("active");

            const dayButtons = document.querySelectorAll("#frequency-group .day");
            const everydayBtn = document.getElementById("everyday-btn");

            const allSelected =
                [...dayButtons].every(btn =>
                    btn.classList.contains("active")
                );

            if (allSelected) {
                everydayBtn.classList.add("active");
            } else {
                everydayBtn.classList.remove("active");
            }
        }

        function selectEveryday() {
            const everydayBtn = document.getElementById("everyday-btn");
            const dayButtons = document.querySelectorAll("#frequency-group .day");
            const isActive = everydayBtn.classList.contains("active");

            if (isActive) {
                everydayBtn.classList.remove("active");

                dayButtons.forEach(btn => {
                    btn.classList.remove("active");
                });
            } else {
                everydayBtn.classList.add("active");

                dayButtons.forEach(btn => {
                    btn.classList.add("active");
                });
            }
        }

        function selectPeriod(element) {
            const isActive = element.classList.contains("active");

            document.querySelectorAll("#period-group .period")
                .forEach(btn => btn.classList.remove("active"));

            if (!isActive) {
                element.classList.add("active");
            }
        }

        document.addEventListener("DOMContentLoaded", () => {
            const today = new Date().toISOString().split("T")[0];
            const startDate = document.getElementById("start-date");
            const endDate = document.getElementById("end-date");

            startDate.min = today;
            startDate.value = today;
            endDate.min = today;
        });

        document.getElementById("start-date")
            .addEventListener("change", function () {
                const endDate = document.getElementById("end-date");

                endDate.min = this.value;

                if (
                    endDate.value &&
                    endDate.value < this.value
                ) {
                    endDate.value = "";
                }
            });

        async function saveGoal() {
            const userId = localStorage.getItem("user_id");

            if (!userId) {
                alert("ログインしてください");
                location.href = "/login";
                return;
            }

            const name = document.getElementById("goal-name").value.trim();

            if (!name) {
                alert("目標名を入力してください");
                return;
            }

            const daysActive = document.querySelectorAll("#frequency-group .active");

            if (daysActive.length === 0) {
                alert("頻度を選択してください");
                return;
            }

            const period =
                document.querySelector("#period-group .active")
                    ?.dataset.period ?? null;

            const timing = document.getElementById("start-timing").value.trim();
            const startDate = document.getElementById("start-date").value;
            const endDate = document.getElementById("end-date").value;

            if (endDate && endDate < startDate) {
                alert("終了日は開始日以降の日付を選択してください");
                return;
            }

            const durationHours = document.getElementById("duration-hours").value;
            const durationMinutes = document.getElementById("duration-minutes").value;
            const color = selectedColor;
            let priority = null;

            if (
                !document.getElementById("priority-box")
                    .classList.contains("disabled-group")
            ) {
                priority =
                    document.querySelector("#priority-group .active")
                        ?.innerText;
            }

            const weekDays =
                Array.from(daysActive)
                    .map(el => el.innerText);

            try {
                const response = await fetch("/tasks", {
                    method: "POST",
                    headers: {
                        "Content-Type": "application/json",
                        "X-CSRF-TOKEN":
                            document.querySelector(
                                'meta[name="csrf-token"]'
                            ).content
                    },
                    body: JSON.stringify({
                        user_id: userId,
                        title: name,
                        content: "",
                        week_days: weekDays,
                        period: period,
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
                alert("登録に失敗しました");
            }
        }
    </script>

</body>
</html>