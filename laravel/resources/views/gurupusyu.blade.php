<!DOCTYPE html>
<html lang="ja">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>グループ詳細 | STEPRA</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

@php
  $calendarTasks = $tasklist->map(function ($task) {
    return [
      'id' => $task->id,
      'title' => $task->title,
      'weekdays' => $task->week_days
        ? array_map('trim', explode(',', $task->week_days))
        : [],
      'startDate' => $task->start_date,
      'endDate' => $task->end_date,
      'color' => $task->color ?: '#198754',
    ];
  })->values();
@endphp

<div class="container-fluid py-4 mb-5">
  <img src="/image/tit.png" alt="STEPRA" class="mb-3" style="width:200px;">

  <div class="card shadow border-0 mb-4">
    <div class="card-body p-3 p-md-4">
      <h2 class="text-center fw-bold mb-4 display-6">
        {{ $group->name }}
      </h2>

      <div class="row g-2 mb-4">
        <div class="col-7 d-flex flex-column gap-2">
          <button class="btn btn-outline-dark w-100 py-2 fw-bold text-start btn-sm" onclick="openTaskList({{ $group->id }})">
            グループタスクを表示
          </button>

          <button class="btn btn-outline-dark w-100 py-2 fw-bold text-start btn-sm" onclick="openTaskContinue({{ $group->id }})">
            グループタスクの継続
          </button>
        </div>

        <div class="col-5">
          <button class="btn btn-primary w-100 h-100 fw-bold d-flex align-items-center justify-content-center" onclick="editGroup()">
            グループ<br>編集
          </button>
        </div>
      </div>

      <div class="border rounded p-3 bg-light">
        <div class="d-flex justify-content-between align-items-center mb-3">
          <button class="btn btn-secondary px-3 py-1 fw-bold" onclick="changeMonth(-1)" aria-label="previous month" title="previous month">&lt;</button>
          <div class="fs-4 fw-bold text-dark px-3 py-1 bg-white border rounded shadow-sm" style="cursor:pointer;" onclick="openCalendarModal()" id="calendarTitle"></div>
          <button class="btn btn-secondary px-3 py-1 fw-bold" onclick="changeMonth(1)" aria-label="next month" title="next month">&gt;</button>
        </div>

        <div class="d-flex text-center fw-bold mb-2 small">
          <div class="text-danger" style="width:14.285%;">日</div>
          <div class="text-dark" style="width:14.285%;">月</div>
          <div class="text-dark" style="width:14.285%;">火</div>
          <div class="text-dark" style="width:14.285%;">水</div>
          <div class="text-dark" style="width:14.285%;">木</div>
          <div class="text-dark" style="width:14.285%;">金</div>
          <div class="text-primary" style="width:14.285%;">土</div>
        </div>

        <div id="calendarGrid" class="d-flex flex-wrap"></div>
      </div>

      <div class="mt-4 border rounded p-3 bg-white">
        <p class="fw-bold mb-3">グループタスク一覧</p>

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

<div class="modal fade" id="calendarModal" tabindex="-1" aria-labelledby="calendarModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered px-3">
    <div class="modal-content shadow border-0">
      <div class="modal-header border-0 pb-0">
        <h5 class="modal-title fw-bold" id="calendarModalLabel">移動する年月</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" title="Close"></button>
      </div>

      <div class="modal-body p-4 text-center">
        <div class="d-flex gap-2 mb-4">
          <select id="yearSelect" class="form-control py-2 fw-bold text-center" aria-label="year" title="year"></select>
          <select id="monthSelect" class="form-control py-2 fw-bold text-center" aria-label="month" title="month"></select>
        </div>

        <button type="button" class="btn btn-success w-100 py-2 fw-bold" onclick="changeCalendar()">
          移動する
        </button>
      </div>
    </div>
  </div>
</div>

<x-menubar />

<div class="py-5"></div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

<script>
(() => {
  let taskData = [];
  async function loadTasks() {
    try {
      const resp = await fetch(`/api/grouptasks?group_id={{ $group->id }}`, { headers: { Accept: 'application/json' } });
      if (!resp.ok) return;
      const data = await resp.json();
      const tasks = Array.isArray(data) ? data : data.tasks || [];
      taskData = tasks.map(task => ({
        id: task.id,
        title: task.title,
        weekdays: task.week_days ? task.week_days.split(',').map(s => s.trim()) : [],
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

  const weekMap = {
    "日": 0,
    "月": 1,
    "火": 2,
    "水": 3,
    "木": 4,
    "金": 5,
    "土": 6
  };

  function parseDate(value) {
    if (!value) {
      return null;
    }

    const normalized = String(value).replaceAll("-", "");

    return new Date(
      Number(normalized.slice(0, 4)),
      Number(normalized.slice(4, 6)) - 1,
      Number(normalized.slice(6, 8))
    );
  }

  function updateCalendarTitle() {
    document.getElementById("calendarTitle").textContent =
      `${currentYear}年${currentMonth}月`;
  }

  function taskMatchesDate(task, date) {
    const start = parseDate(task.startDate);
    const end = task.endDate ? parseDate(task.endDate) : new Date(9999, 11, 31);

    if (!start || date < start || date > end) {
      return false;
    }

    const week = date.getDay();

    const days = task.weekdays || [];
    if (days.length === 0) {
      // No weekday restriction — show for all dates in the start/end range
      return true;
    }

    return days.some((dayText) => {
      const key = String(dayText).trim();
      return weekMap[key] === week || Number(key) === week;
    });
  }

  function getTasksForDate(date) {
    return taskData.filter((task) => taskMatchesDate(task, date));
  }

  function createCalendar() {
    const calendar = document.getElementById("calendarGrid");
    calendar.innerHTML = "";

    const firstDay = new Date(currentYear, currentMonth - 1, 1).getDay();
    const lastDate = new Date(currentYear, currentMonth, 0).getDate();

    for (let i = 0; i < firstDay; i++) {
      const empty = document.createElement("div");
      empty.style.width = "14.285%";
      calendar.appendChild(empty);
    }

    for (let day = 1; day <= lastDate; day++) {
      const dayBox = document.createElement("div");
      const currentDate = new Date(currentYear, currentMonth - 1, day);
      const currentWeek = currentDate.getDay();
      const matchedTasks = getTasksForDate(currentDate);

      let colorClasses = "bg-white text-dark";

      if (
        currentYear === today.getFullYear() &&
        currentMonth === today.getMonth() + 1 &&
        day === today.getDate()
      ) {
        colorClasses = "bg-success bg-opacity-25 border border-success text-success fw-bold";
      } else if (currentWeek === 0) {
        colorClasses = "bg-danger bg-opacity-10 text-danger";
      } else if (currentWeek === 6) {
        colorClasses = "bg-primary bg-opacity-10 text-primary";
      }

      dayBox.className =
        `border rounded p-1 d-flex flex-column ${colorClasses}`;
      dayBox.style.width = "14.285%";
      dayBox.style.minHeight = "108px";
      dayBox.style.cursor = "pointer";
      dayBox.style.overflow = "hidden";

      const dayNum = document.createElement("span");
      dayNum.className = "small fw-bold text-center mb-1";
      dayNum.textContent = day;
      dayBox.appendChild(dayNum);

      const taskContainer = document.createElement("div");
      taskContainer.className = "d-flex flex-column gap-1 w-100";

      matchedTasks.slice(0, 3).forEach((task) => {
        const taskBar = document.createElement("div");
        taskBar.className = "fw-bold text-center px-1 rounded";
        const bg = task.color || "#0d6efd";
        taskBar.style.backgroundColor = bg;
        // choose readable text color based on background luminance
        function hexToRgb(hex) {
          const h = hex.replace('#', '');
          const bigint = parseInt(h.length === 3 ? h.split('').map(c=>c+c).join('') : h, 16);
          return [(bigint >> 16) & 255, (bigint >> 8) & 255, bigint & 255];
        }
        function luminance(r,g,b){
          const a=[r,g,b].map(v=>{
            v=v/255; return v<=0.03928? v/12.92 : Math.pow((v+0.055)/1.055,2.4);
          });
          return 0.2126*a[0]+0.7152*a[1]+0.0722*a[2];
        }
        try{
          const [r,g,b] = hexToRgb(bg);
          const lum = luminance(r,g,b);
          taskBar.style.color = lum > 0.5 ? '#111' : '#fff';
        }catch(e){
          taskBar.style.color = '#fff';
        }
        taskBar.style.fontSize = "12px";
        taskBar.style.lineHeight = "1.45";
        taskBar.style.whiteSpace = "nowrap";
        taskBar.style.overflow = "hidden";
        taskBar.style.textOverflow = "ellipsis";
        taskBar.title = task.title;
        taskBar.textContent = task.title;
        taskContainer.appendChild(taskBar);
      });

      if (matchedTasks.length > 3) {
        const more = document.createElement("div");
        more.className = "small text-muted text-center";
        more.textContent = `+${matchedTasks.length - 3}`;
        taskContainer.appendChild(more);
      }

      dayBox.onclick = () => {
        const y = currentYear;
        const m = String(currentMonth).padStart(2, '0');
        const d = String(day).padStart(2, '0');
        // navigate to schedule detail page for the clicked date (include group_id)
        window.location.href = `/scheduleDetail?date=${y}-${m}-${d}&group_id={{ $group->id }}`;
      };

      dayBox.appendChild(taskContainer);
      calendar.appendChild(dayBox);
    }
  }

  function createSelectOptions() {
    const yearSelect = document.getElementById("yearSelect");
    const monthSelect = document.getElementById("monthSelect");

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
  }

  window.changeMonth = (move) => {
    currentMonth += move;

    if (currentMonth < 1) {
      currentMonth = 12;
      currentYear--;
    }

    if (currentMonth > 12) {
      currentMonth = 1;
      currentYear++;
    }

    updateCalendarTitle();
    createCalendar();
  };

  window.openCalendarModal = () => {
    createSelectOptions();
    const modal = new bootstrap.Modal(document.getElementById("calendarModal"));
    modal.show();
  };

  window.changeCalendar = () => {
    currentYear = Number(document.getElementById("yearSelect").value);
    currentMonth = Number(document.getElementById("monthSelect").value);

    updateCalendarTitle();
    createCalendar();

    const modalElement = document.getElementById("calendarModal");
    const modalInstance = bootstrap.Modal.getInstance(modalElement);

    if (modalInstance) {
      modalInstance.hide();
    }
  };

  window.openTaskList = (id) => {
    window.location.href = `/gtasutkuitiran/${id}`;
  };

  window.openTaskContinue = (id) => {
    window.location.href = `/gurutaskukuhen/${id}`;
  };

  window.editGroup = () => {
    alert("グループ編集は未実装です");
  };

  (async () => {
    await loadTasks();
    updateCalendarTitle();
    createCalendar();
  })();
})();
</script>

</body>
</html>
