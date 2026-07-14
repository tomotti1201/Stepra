<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>月間カレンダー | STEPRA</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="bg-light">
    <div class="container py-4 mb-5">

        <!--<div class="row justify-content-center">-->

            <!--<div class="col-12 col-md-8 col-lg-5"> -->

                <div class="card-body">

    <script>
        if (!localStorage.getItem("user_id")) {
            location.href = "/login";
        }
    </script>

        <img src="{{ asset('image/tit.png') }}" class="mb-3" style="width:200px;">

        <div class="row justify-content-center">
            <div class="col-12 px-3 px-md-4">

                <div class="card-body">

                    <div class="text-center fw-bold mb-4 fs-4">月間カレンダー</div>

                    <div class="d-flex justify-content-between align-items-center mb-4">

    <button class="btn btn-secondary px-4 py-2"
            onclick="changeMonth(-1)">
        &lt;
    </button>

    <div>

        <!-- 通常表示 -->
        <button
            id="current-month-display"
            type="button"
            class="btn btn-light border shadow-sm fw-bold fs-3 px-4 py-2"
            onclick="showSelect()">
        </button>

        <!-- 編集表示 -->
        <div
            id="calendarEditor"
            class="d-none">

            <div class="d-flex align-items-center gap-2">

                <select
                    id="yearSelect"
                    class="form-select form-select-lg"
                    style="width:140px;"
                    onchange="changeYear()">
                </select>

                <select
                    id="monthSelect"
                    class="form-select form-select-lg"
                    style="width:120px;"
                    onchange="changeCalendar()">
                </select>
            </div>

        </div>

    </div>

    <button class="btn btn-secondary px-4 py-2"
            onclick="changeMonth(1)">
        &gt;
    </button>

</div>

                    <div class="week d-grid text-center fw-bold border-bottom pb-3 mb-3 fs-5" style="grid-template-columns: repeat(7, 1fr);">
                        <div class="text-danger">日</div>
                        <div>月</div>
                        <div>火</div>
                        <div>水</div>
                        <div>木</div>
                        <div>金</div>
                        <div class="text-primary">土</div>
                    </div>

                    <div id="calendar-days"
                        class="d-grid gap-1 text-center fs-6"
                        style="
                            grid-template-columns: repeat(7, 1fr);
                            grid-auto-rows: 95px;
                            align-items: stretch;
                        ">
                    </div>
                </div>

            </div>
        </div>

    </div>
</div>

    <x-menubar />

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // 現在表示している月
        const now = new Date();
        let currentViewDate = new Date(now.getFullYear(), now.getMonth(), 1);

        // キャッシュ
        let scheduleCache = {};
        let scheduleData = [];

        /**
         * スケジュール取得（キャッシュ付き）
         */
        async function fetchSchedules() {
            const year = currentViewDate.getFullYear();
            const month = currentViewDate.getMonth() + 1;

            const key = `${year}-${month}`;

            if (scheduleCache[key]) {
                scheduleData = scheduleCache[key];
                return;
            }

            const userId = localStorage.getItem("user_id");

            const res = await fetch(
                `/api/schedules/monthly?year=${year}&month=${month}&user_id=${userId}`
            );

            const data = await res.json();

            scheduleCache[key] = data.schedules || [];
            scheduleData = scheduleCache[key];
        }

        /**
         * カレンダー描画
         */
        async function renderCalendar() {
            await fetchSchedules();

            const year = currentViewDate.getFullYear();
            const month = currentViewDate.getMonth();

            const daysContainer = document.getElementById("calendar-days");
            const display = document.getElementById("current-month-display");

            daysContainer.innerHTML = "";

            if (display) {
                display.innerText = `${year}年 ${month + 1}月`;
            }

            const lastDate = new Date(year, month + 1, 0).getDate();
            const firstDayOfWeek = new Date(year, month, 1).getDay();

            // 空白
            for (let i = 0; i < firstDayOfWeek; i++) {
                const empty = document.createElement("div");

                daysContainer.appendChild(empty);
            }

            // 日付生成
            for (let date = 1; date <= lastDate; date++) {

                const fullDate =
                    `${year}-${String(month + 1).padStart(2, "0")}-${String(date).padStart(2, "0")}`;

                const schedulesForDay = scheduleData.filter(s => {
                    return String(s.scheduled_date).slice(0, 10) === fullDate;
                });

                const dayEl = document.createElement("button");

                dayEl.className =
                    "btn w-100 border rounded-3 p-1 shadow-sm";

                dayEl.style.height = "95px";
                dayEl.style.overflow = "hidden";
                dayEl.style.display = "flex";
                dayEl.style.flexDirection = "column";

                const dayOfWeek = new Date(year, month, date).getDay();

                if (dayOfWeek === 0) {
                    dayEl.className = "btn btn-danger-subtle border border-danger-subtle text-danger fw-bold rounded-3 py-1 shadow-sm w-100 h-100";
                } else if (dayOfWeek === 6) {
                    dayEl.className = "btn btn-primary-subtle border border-primary-subtle text-primary fw-bold rounded-3 py-1 shadow-sm w-100 h-100";
                }

                // 日付
                const header = `
                    <div style="font-size:13px; font-weight:bold;">
                        ${date}
                    </div>
                `;

                // タスク一覧（横バー）
                const tasksHtml = schedulesForDay.map(s => {
                    const color = s.color || "#198754";
                    const title = s.title || "task";

                    return `
                        <div
                            style="
                                background:${color};
                                color:#fff;
                                font-size:12px;
                                padding:1px 4px;
                                margin-top:2px;
                                border-radius:4px;
                                white-space:nowrap;
                                overflow:hidden;
                                text-overflow:ellipsis;
                                width:100%;
                            "
                            title="${title}"
                        >
                            ${title}
                        </div>
                    `;
                }).join("");

                dayEl.innerHTML = header + `
                    <div style="display:flex; flex-direction:column; gap:2px; margin-top:2px;">
                        ${tasksHtml}
                    </div>
                `;

                dayEl.onclick = () => clickDate(date);

                daysContainer.appendChild(dayEl);
            }
            const totalCells = firstDayOfWeek + lastDate;
            const remainCells = 42 - totalCells;

            for (let i = 0; i < remainCells; i++) {
                const empty = document.createElement("div");
                empty.style.height = "95px";
                daysContainer.appendChild(empty);
            }
        }

        /**
         * 月移動
         */
        function changeMonth(offset) {
            currentViewDate.setMonth(currentViewDate.getMonth() + offset);
            renderCalendar();
        }

        /**
         * 日付クリック
         */
        function clickDate(date) {
            const year = currentViewDate.getFullYear();
            const month = currentViewDate.getMonth() + 1;

            const fullDate =
                `${year}-${String(month).padStart(2,'0')}-${String(date).padStart(2,'0')}`;

            window.location.href = `/scheduleDetail?date=${fullDate}`;
        }
function goTask(taskId) {
    window.location.href = `/tasks/show?id=${taskId}`;
}

        /**
         * 初期描画
         */
        document.addEventListener("DOMContentLoaded", renderCalendar);
    
        function createSelectOptions() {

            const yearSelect = document.getElementById("yearSelect");
            const monthSelect = document.getElementById("monthSelect");

            yearSelect.innerHTML = "";
            monthSelect.innerHTML = "";

            const currentYear = currentViewDate.getFullYear();

            for (let y = currentYear - 5; y <= currentYear + 5; y++) {

                const option = document.createElement("option");
                option.value = y;
                option.textContent = `${y}年`;

                if (y === currentYear) {
                    option.selected = true;
                }

                yearSelect.appendChild(option);
            }

            const currentMonth = currentViewDate.getMonth() + 1;

            for (let m = 1; m <= 12; m++) {

                const option = document.createElement("option");
                option.value = m;
                option.textContent = `${m}月`;

                if (m === currentMonth) {
                    option.selected = true;
                }

                monthSelect.appendChild(option);
            }
        }

        function showSelect() {

            createSelectOptions();

            document
                .getElementById("current-month-display")
                .classList.add("d-none");

            document
                .getElementById("calendarEditor")
                .classList.remove("d-none");

        }

        function changeYear() {

            const year = Number(document.getElementById("yearSelect").value);
            const month = Number(document.getElementById("monthSelect").value);

            currentViewDate = new Date(year, month - 1, 1);

            renderCalendar();

            // 編集画面は閉じない
        }

        function changeCalendar() {
            const year = Number(document.getElementById("yearSelect").value);
            const month = Number(document.getElementById("monthSelect").value);

            currentViewDate = new Date(year, month - 1, 1);

            renderCalendar();

            // 月を選んだら編集終了
            document.getElementById("calendarEditor").classList.add("d-none");
            document.getElementById("current-month-display").classList.remove("d-none");     
        }
    
    </script>

    <x-menubar />

    <div style="height:100px;"></div>

</body>
</html>