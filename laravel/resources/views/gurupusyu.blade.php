<!DOCTYPE html>
<html lang="ja">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>&#12464;&#12523;&#12540;&#12503;&#35443;&#32048; | STEPRA</title>
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
            &#12464;&#12523;&#12540;&#12503;&#12479;&#12473;&#12463;&#12434;&#34920;&#31034;
          </button>

          <button class="btn btn-outline-dark w-100 py-2 fw-bold text-start btn-sm" onclick="openTaskContinue({{ $group->id }})">
            &#12464;&#12523;&#12540;&#12503;&#12479;&#12473;&#12463;&#12398;&#32153;&#32154;
          </button>
        </div>

        <div class="col-5">
          <button class="btn btn-primary w-100 h-100 fw-bold d-flex align-items-center justify-content-center" onclick="editGroup()">
            &#12464;&#12523;&#12540;&#12503;<br>&#32232;&#38598;
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
          <div class="text-danger" style="width:14.285%;">&#26085;</div>
          <div class="text-dark" style="width:14.285%;">&#26376;</div>
          <div class="text-dark" style="width:14.285%;">&#28779;</div>
          <div class="text-dark" style="width:14.285%;">&#27700;</div>
          <div class="text-dark" style="width:14.285%;">&#26408;</div>
          <div class="text-dark" style="width:14.285%;">&#37329;</div>
          <div class="text-primary" style="width:14.285%;">&#22303;</div>
        </div>

        <div id="calendarGrid" class="d-flex flex-wrap"></div>
      </div>

      <div class="mt-4 border rounded p-3 bg-white">
        <p class="fw-bold mb-3">&#12464;&#12523;&#12540;&#12503;&#12479;&#12473;&#12463;&#19968;&#35239;</p>

        @forelse ($tasklist as $task)
          <div class="border rounded p-3 mb-2">
            <div class="fw-bold">{{ $task->title }}</div>

            @if (!empty($task->content))
              <div class="small text-muted mt-1">{{ $task->content }}</div>
            @endif
          </div>
        @empty
          <p class="text-muted small mb-0">&#12414;&#12384;&#12464;&#12523;&#12540;&#12503;&#12479;&#12473;&#12463;&#12364;&#12354;&#12426;&#12414;&#12379;&#12435;</p>
        @endforelse
      </div>
    </div>
  </div>
</div>

<div class="modal fade" id="calendarModal" tabindex="-1" aria-labelledby="calendarModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered px-3">
    <div class="modal-content shadow border-0">
      <div class="modal-header border-0 pb-0">
        <h5 class="modal-title fw-bold" id="calendarModalLabel">&#31227;&#21205;&#12377;&#12427;&#24180;&#26376;</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" title="Close"></button>
      </div>

      <div class="modal-body p-4 text-center">
        <div class="d-flex gap-2 mb-4">
          <select id="yearSelect" class="form-control py-2 fw-bold text-center" aria-label="year" title="year"></select>
          <select id="monthSelect" class="form-control py-2 fw-bold text-center" aria-label="month" title="month"></select>
        </div>

        <button type="button" class="btn btn-success w-100 py-2 fw-bold" onclick="changeCalendar()">
          &#31227;&#21205;&#12377;&#12427;
        </button>
      </div>
    </div>
  </div>
</div>

<nav class="navbar bg-white border-top fixed-bottom">
  <div class="container d-flex justify-content-around">
    <button class="btn btn-outline-secondary" onclick="location.href='/home'">&#12507;&#12540;&#12512;</button>
    <button class="btn btn-outline-secondary" onclick="location.href='/mokuhyouitiran'">&#30446;&#27161;</button>
    <button class="btn btn-outline-secondary" onclick="location.href='/gekkankarenda'">&#12459;&#12524;&#12531;&#12480;&#12540;</button>
    <button class="btn btn-success" onclick="location.href='/gurupu'">&#12464;&#12523;&#12540;&#12503;</button>
    <button class="btn btn-outline-secondary" onclick="location.href='/setting'">&#35373;&#23450;</button>
  </div>
</nav>

<div class="py-5"></div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

<script>
(() => {
  const taskData = @json($calendarTasks);
  const today = new Date();
  let currentYear = today.getFullYear();
  let currentMonth = today.getMonth() + 1;

  const weekMap = {
    "\u65e5": 0,
    "\u6708": 1,
    "\u706b": 2,
    "\u6c34": 3,
    "\u6728": 4,
    "\u91d1": 5,
    "\u571f": 6
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
      `${currentYear}\u5e74${currentMonth}\u6708`;
  }

  function taskMatchesDate(task, date) {
    const start = parseDate(task.startDate);
    const end = task.endDate ? parseDate(task.endDate) : new Date(9999, 11, 31);

    if (!start || date < start || date > end) {
      return false;
    }

    const week = date.getDay();

    return (task.weekdays || []).some((dayText) => {
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
        taskBar.className = "text-white fw-bold text-center px-1 rounded";
        taskBar.style.backgroundColor = task.color || "#0d6efd";
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
        if (matchedTasks.length === 0) {
          alert(`${currentMonth}\u6708${day}\u65e5\u306e\u30bf\u30b9\u30af\u306f\u3042\u308a\u307e\u305b\u3093`);
          return;
        }

        alert(matchedTasks.map((task) => task.title).join("\n"));
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
      option.textContent = `${year}\u5e74`;
      yearSelect.appendChild(option);
    }

    for (let month = 1; month <= 12; month++) {
      const option = document.createElement("option");
      option.value = month;
      option.textContent = `${month}\u6708`;
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
    alert("\u30b0\u30eb\u30fc\u30d7\u7de8\u96c6\u306f\u672a\u5b9f\u88c5\u3067\u3059");
  };

  updateCalendarTitle();
  createCalendar();
})();
</script>

</body>
</html>
