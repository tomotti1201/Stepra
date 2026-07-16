<!DOCTYPE html>
<html lang="ja">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>グループ詳細 | STEPRA</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
  <div class="container py-4 mb-5">

    <div class="card-body">

    <script>
        if (!localStorage.getItem("user_id")) {
            location.href = "/login";
        }
    </script>

@php
  $calendarTasks = $tasklist->map(function ($task) {
    $weekdays = [];
    if (!empty($task->week_days)) {
      $decoded = json_decode($task->week_days, true);
      $weekdays = is_array($decoded)
        ? $decoded
        : array_map('trim', explode(',', $task->week_days));
    }

    return [
      'id' => $task->id,
      'title' => $task->title,
      'weekdays' => $weekdays,
      'startDate' => $task->start_date,
      'endDate' => $task->end_date,
      'color' => $task->color ?: '#198754',
    ];
  })->values();
@endphp

  <img src="/image/tit.png" alt="STEPRA" class="mb-3" style="width:200px;">

      <h2 class="text-center fw-bold mb-4 display-6">
        {{ $group->name }}
      </h2>

      <div class="row g-2 mb-4">
          <div class="col-6 d-grid">
              <button
                  class="btn btn-outline-dark fw-bold py-4 h-100 d-flex align-items-center justify-content-center"
                  onclick="openTaskList({{ $group->id }})">
                  グループタスクを表示
              </button>
          </div>

          <div class="col-6 d-grid">
              <button
                  class="btn btn-primary fw-bold py-4 h-100 d-flex align-items-center justify-content-center"
                  onclick="editGroup({{ $group->id }})">
                  グループ編集
              </button>
          </div>
      </div>

        <div class="d-flex justify-content-between align-items-center mb-3">
          <button class="btn btn-secondary px-3 py-1 fw-bold" onclick="changeMonth(-1)" aria-label="previous month" title="previous month">&lt;</button>
    <div id="calendarArea">
    <!-- 通常表示 -->
    <button
        id="calendarTitle"
        type="button"
        class="btn btn-light border shadow-sm fw-bold fs-3 px-4 py-2"
        onclick="showSelect()">
    </button>
    <!-- 編集用 -->
    <div id="calendarEditor" class="d-none">
      <div class="d-flex align-items-center gap-3">
      <select
          id="yearSelect"
          class="form-select"
          style="width:140px;"
          onchange="changeYear()">
      </select>

      <select
          id="monthSelect"
          class="form-select"
          style="width:140px;"
          onchange="changeMonthSelect()">
      </select>

      </div>

    </div>

</div>
          <button class="btn btn-secondary px-3 py-1 fw-bold" onclick="changeMonth(1)" aria-label="next month" title="next month">&gt;</button>
        </div>

        <div
            class="d-grid text-center fw-bold border-bottom pb-3 mb-3 fs-5"
            style="grid-template-columns:repeat(7,1fr);">
            <div class="text-danger">日</div>
            <div>月</div>
            <div>火</div>
            <div>水</div>
            <div>木</div>
            <div>金</div>
            <div class="text-primary">土</div>
        </div>

       <div
          id="calendarGrid"
          class="d-grid gap-1 text-center fs-6"
          style="
              grid-template-columns: repeat(7,1fr);
              grid-auto-rows:95px;
              align-items:stretch;
          ">
      </div>

      <div class="mt-4 border rounded p-3 bg-white" id="groupTaskArea">
        <p class="fw-bold mb-3">グループタスク一覧</p>

        <div id="groupTaskList">
          @forelse ($tasklist as $task)
            <div class="border rounded p-3 mb-2">
              <div class="fw-bold">{{ $task->title }}</div>

              @if (!empty($task->content))
                <div class="small text-muted mt-1">{{ $task->content }}</div>
              @endif
            </div>
          @empty
            <p class="text-muted small mb-0">まだグループタスクがありません</p>
          @endforelse
        </div>
      </div>
    </div>
  </div>
</div>

<x-menubar />

<div class="py-5"></div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

<script>
() => {
  let taskData = @json($calendarTasks);
  async function loadTasks() {
    try {
      const resp = await fetch(`/api/grouptasks?group_id={{ $group->id }}`, { headers: { Accept: 'application/json' } });
      if (!resp.ok) return;
      const data = await resp.json();
      const tasks = Array.isArray(data) ? data : data.tasks || [];
      taskData = tasks.map(task => ({
        id: task.id,
        title: task.title,
        weekdays: normalizeWeekDays(task.week_days),
        startDate: task.start_date,
        endDate: task.end_date,
        color: task.color || '#198754'
      }));
    } catch (e) {
      console.error('Failed loading group tasks', e);
    }
  }
  const today = new Date();
  let currentYear = today.getFullYear();
  let currentMonth = today.getMonth() + 1;

let scheduleCache = {};
let scheduleData = [];

/**
 * 月のスケジュール取得
 */
async function fetchSchedules() {

    const key = `${currentYear}-${currentMonth}`;

    if (scheduleCache[key]) {
        scheduleData = scheduleCache[key];
        return;
    }

    const res = await fetch(
        `/api/groupSchedules/monthly?year=${currentYear}&month=${currentMonth}&group_id={{ $group->id }}`
    );

    const data = await res.json();

    scheduleCache[key] = data.schedules || [];
    scheduleData = scheduleCache[key];
}

/**
 * タイトル更新
 */
function updateCalendarTitle() {

    document.getElementById("calendarTitle").textContent =
        `${currentYear}年${currentMonth}月`;

}

/**
 * カレンダー描画
 */
// async function createCalendar() {

//     const week = date.getDay();
//     const isoWeek = week === 0 ? 7 : week;

//     const days = task.weekdays || [];
//     if (days.length === 0) {
//       // No weekday restriction — show for all dates in the start/end range
//       return true;
//     }

//     return days.some((dayText) => {
//       const key = String(dayText).trim();
//       return weekMap[key] === week || Number(key) === isoWeek;
//     });
//   }

  function getTasksForDate(date) {
    return taskData.filter((task) => taskMatchesDate(task, date));
  }

  function createCalendar() {
    const calendar = document.getElementById("calendarGrid");
    calendar.innerHTML = "";

    updateCalendarTitle();

    const firstDay = new Date(currentYear, currentMonth - 1, 1).getDay();
    const lastDate = new Date(currentYear, currentMonth, 0).getDate();

    // 空白
    for (let i = 0; i < firstDay; i++) {

        const empty = document.createElement("div");
        calendar.appendChild(empty);

    }

    // 日付生成
    for (let day = 1; day <= lastDate; day++) {

        const dayBox = document.createElement("button");

        const fullDate =
            `${currentYear}-${String(currentMonth).padStart(2,"0")}-${String(day).padStart(2,"0")}`;

        const matchedTasks =
            scheduleData.filter(schedule =>
                schedule.scheduled_date === fullDate
            );

        const dayOfWeek =
            new Date(currentYear, currentMonth - 1, day).getDay();

        let colorClasses = "bg-white text-dark";

        if (
            currentYear === today.getFullYear() &&
            currentMonth === today.getMonth() + 1 &&
            day === today.getDate()
        ) {

            colorClasses =
                "bg-success bg-opacity-25 border border-success text-success fw-bold";

        } else if (dayOfWeek === 0) {

            colorClasses =
                "bg-danger bg-opacity-10 text-danger";

        } else if (dayOfWeek === 6) {

            colorClasses =
                "bg-primary bg-opacity-10 text-primary";

        }

        dayBox.className =
            `btn w-100 border rounded-3 p-1 shadow-sm ${colorClasses}`;

        dayBox.style.height = "95px";
        dayBox.style.display = "flex";
        dayBox.style.flexDirection = "column";
        dayBox.style.overflow = "hidden";

        // 日付
        const dayNum = document.createElement("span");

        dayNum.className =
            "small fw-bold text-center mb-1";

        dayNum.textContent = day;

        dayBox.appendChild(dayNum);

        // タスク表示
        const taskContainer = document.createElement("div");

        taskContainer.className =
            "d-flex flex-column gap-1 w-100";

        matchedTasks.slice(0,3).forEach(task => {

            const taskBar = document.createElement("div");

            taskBar.className =
                "fw-bold text-center px-1 rounded";

            taskBar.style.backgroundColor =
                task.color || "#198754";

            taskBar.style.color = "#fff";

            taskBar.style.fontSize = "12px";
            taskBar.style.whiteSpace = "nowrap";
            taskBar.style.overflow = "hidden";
            taskBar.style.textOverflow = "ellipsis";

            taskBar.textContent = task.title;

            taskContainer.appendChild(taskBar);

        });
                // 4件以上ある場合
        if (matchedTasks.length > 3) {

            const more = document.createElement("div");

            more.className =
                "small text-muted text-center";

            more.textContent =
                `+${matchedTasks.length - 3}`;

            taskContainer.appendChild(more);
        }

        dayBox.appendChild(taskContainer);

      dayBox.onclick = () => {
        if (matchedTasks.length === 0) {
          alert(`${currentMonth}月${day}日のタスクはありません`);
          return;
        }

        const selectedDate =
          `${currentYear}-${String(currentMonth).padStart(2, "0")}-${String(day).padStart(2, "0")}`;

        localStorage.setItem("group_id", "{{ $group->id }}")

            window.location.href =
                `/group/{{ $group->id }}/scheduleDetail?date=${fullDate}`;

        };

        calendar.appendChild(dayBox);
    }

    // 最後の空白セル
    const totalCells = firstDay + lastDate;
    const remainCells = 42 - totalCells;

    for (let i = 0; i < remainCells; i++) {

        const empty = document.createElement("div");

        empty.style.height = "95px";

        calendar.appendChild(empty);
    }

}

/**
 * 年月セレクト作成
 */
window.createSelectOptions = function () {

    const yearSelect =
        document.getElementById("yearSelect");

    const monthSelect =
        document.getElementById("monthSelect");

    yearSelect.innerHTML = "";
    monthSelect.innerHTML = "";

    for (let year = 2020; year <= 2035; year++) {

        const option = document.createElement("option");

        option.value = year;
        option.textContent = `${year}年`;

        yearSelect.appendChild(option);
    }

    for (let month = 1; month <= 12; month++) {

        const option = document.createElement("option");

        option.value = month;
        option.textContent = `${month}月`;

        monthSelect.appendChild(option);
    }

    yearSelect.value = currentYear;
    monthSelect.value = currentMonth;

};

/**
 * 前月・翌月
 */
window.changeMonth = async (move) => {

    currentMonth += move;

    if (currentMonth < 1) {

        currentMonth = 12;
        currentYear--;

    }

    if (currentMonth > 12) {

        currentMonth = 1;
        currentYear++;

    }

    await createCalendar();

};

/**
 * 年変更
 */
window.changeYear = async () => {

    currentYear =
        Number(document.getElementById("yearSelect").value);

    currentMonth =
        Number(document.getElementById("monthSelect").value);

    await createCalendar();

};
/**
 * 月変更（セレクト）
 */
window.changeMonthSelect = async () => {

    currentYear =
        Number(document.getElementById("yearSelect").value);

    currentMonth =
        Number(document.getElementById("monthSelect").value);


    await createCalendar();


    document
        .getElementById("calendarEditor")
        .classList.add("d-none");

    document
        .getElementById("calendarTitle")
        .classList.remove("d-none");

};
  window.loadGroupTasks = async (id) => {
    const list = document.getElementById("groupTaskList");
    const area = document.getElementById("groupTaskArea");

    list.innerHTML = '<p class="text-muted small mb-0">読み込み中...</p>';

    try {
      const response = await fetch(`/api/grouptasks?group_id=${encodeURIComponent(id)}`);
      const data = await response.json();

      if (!response.ok || data.status === "error") {
        throw new Error(data.message || "グループタスクの取得に失敗しました");
      }

      const tasks = data.tasks || [];
      renderGroupTaskList(tasks);

      taskData = tasks.map((task) => ({
        id: task.id,
        title: task.title,
        weekdays: normalizeWeekDays(task.week_days),
        startDate: task.start_date,
        endDate: task.end_date,
        color: task.color || "#198754",
      }));
      createCalendar();
      area.scrollIntoView({ behavior: "smooth", block: "start" });
    } catch (error) {
      console.error(error);
      list.innerHTML = '<p class="text-danger small mb-0">グループタスクの読み込みに失敗しました</p>';
    }
  };

  function renderGroupTaskList(tasks) {
    const list = document.getElementById("groupTaskList");
    list.innerHTML = "";

    if (tasks.length === 0) {
      list.innerHTML = '<p class="text-muted small mb-0">まだグループタスクがありません</p>';
      return;
    }

    tasks.forEach((task) => {
      const item = document.createElement("div");
      item.className = "border rounded p-3 mb-2";

      const title = document.createElement("div");
      title.className = "fw-bold";
      title.textContent = task.title || "無題のタスク";
      item.appendChild(title);

      if (task.content) {
        const content = document.createElement("div");
        content.className = "small text-muted mt-1";
        content.textContent = task.content;
        item.appendChild(content);
      }

      list.appendChild(item);
    });
  }

  function normalizeWeekDays(value) {
    if (!value) {
      return [];
    }

    if (Array.isArray(value)) {
      return value;
    }

    try {
      const parsed = JSON.parse(value);
      if (Array.isArray(parsed)) {
        return parsed;
      }
    } catch (error) {
      // comma separated values are handled below.
    }

    return String(value)
      .split(",")
      .map((day) => day.trim())
      .filter(Boolean);
  }

  window.openGroupTasks = (id) => {
    window.location.href = `/group/${id}/tasks`;
  };

  window.openTaskContinue = (id) => {
    window.location.href = `/group/${id}/tasks`;
  };

    window.location.href = `/group/${id}/tasks`;

};

/**
 * グループ編集
 */
window.editGroup = (id) => {

    window.location.href = `/group/${id}/edit`;

};

/**
 * 初期表示
 */
(async () => {

    await createCalendar();

})();

/**
 * 年月選択表示
 */
function showSelect() {

    createSelectOptions();

    document
        .getElementById("calendarTitle")
        .classList.add("d-none");

    document
        .getElementById("calendarEditor")
        .classList.remove("d-none");

}
</script>

</body>
</html>
