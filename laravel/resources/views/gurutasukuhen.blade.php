<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>STEPRA グループタスク編集</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="bg-light">

<div class="container py-4 mb-5">

    <!-- タイトル画像 -->
    <img src="/image/tit.png" alt="STEPRA" class="mb-3" style="width:200px;">

    <!-- タイトル -->
    <div class="card shadow mb-4">
        <div class="card-body">
            <h4 class="text-center fw-bold mb-0">
                グループタスク編集
            </h4>
        </div>
    </div>

    <!-- タスク名 -->
    <div class="card shadow mb-3">
        <div class="card-body">
            <label class="form-label fw-bold">
                {{ $group->name }}
            </label>

            <input
                type="text"
                id="goal-name"
                class="form-control"
                placeholder="タスク名を入力"
                aria-label="Task name"
                title="Task name">
        </div>
    </div>

    <!-- 頻度 -->
    <div class="card shadow mb-3">
        <div class="card-body">
            <label class="form-label fw-bold">
                頻度
            </label>

            <div class="btn-group w-100" id="frequency-group">
                <button type="button" class="btn btn-outline-secondary day" onclick="this.classList.toggle('active')">日</button>
                <button type="button" class="btn btn-outline-secondary day" onclick="this.classList.toggle('active')">月</button>
                <button type="button" class="btn btn-outline-secondary day" onclick="this.classList.toggle('active')">火</button>
                <button type="button" class="btn btn-outline-secondary day" onclick="this.classList.toggle('active')">水</button>
                <button type="button" class="btn btn-outline-secondary day" onclick="this.classList.toggle('active')">木</button>
                <button type="button" class="btn btn-outline-secondary day" onclick="this.classList.toggle('active')">金</button>
                <button type="button" class="btn btn-outline-secondary day" onclick="this.classList.toggle('active')">土</button>
            </div>
        </div>
    </div>

    <!-- 時間設定 -->
    <div class="card shadow mb-3">
        <div class="card-body">
            <div class="row">
                <div class="col-6">
                    <label class="form-label fw-bold">
                        開始時間
                    </label>

                    <input
                        type="time"
                        id="start-timing"
                        class="form-control"
                        value="10:00"
                        aria-label="Start time"
                        title="Start time">
                </div>

                <div class="col-6">
                    <label class="form-label fw-bold">
                        所要時間
                    </label>

                    <div class="input-group">
                        <input
                            type="number"
                            id="duration-hours"
                            class="form-control"
                            value="0"
                            min="0"
                            max="23"
                            aria-label="Duration hours"
                            title="Duration hours">

                        <span class="input-group-text">時</span>

                        <input
                            type="number"
                            id="duration-minutes"
                            class="form-control"
                            value="0"
                            min="0"
                            max="59"
                            aria-label="Duration minutes"
                            title="Duration minutes">

                        <span class="input-group-text">分</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- モード設定 -->
    <div class="card shadow mb-3">
        <div class="card-body">

            <label class="form-label fw-bold">
                タスクモード
            </label>

            <div class="btn-group w-100" id="mode-group">
                <button type="button" class="btn btn-primary mode active">
                    自由設定
                </button>

                <button type="button" class="btn btn-outline-primary mode">
                    優先順位
                </button>
            </div>

            <div id="priorityArea">

                <label class="form-label fw-bold mt-3">
                    優先度
                </label>

                <div class="btn-group w-100" id="priority-group">
                    <button type="button" class="btn btn-outline-secondary priority">
                        高
                    </button>

                    <button type="button" class="btn btn-outline-secondary priority active">
                        中
                    </button>

                    <button type="button" class="btn btn-outline-secondary priority">
                        低
                    </button>
                </div>

            </div>

        </div>
    </div>

    <!-- 開始日・終了日 -->
    <div class="card shadow mb-3">
        <div class="card-body">
            <div class="row">

                <div class="col-6">
                    <label class="form-label fw-bold">
                        開始日
                    </label>

                    <input
                        type="date"
                        id="start-date"
                        class="form-control"
                        aria-label="Start date"
                        title="Start date">
                </div>

                <div class="col-6">
                    <label class="form-label fw-bold">
                        終了日
                    </label>

                    <input
                        type="date"
                        id="end-date"
                        class="form-control"
                        aria-label="End date"
                        title="End date">
                </div>

            </div>
        </div>
    </div>

    <!-- カラー設定 -->
    <div class="card shadow mb-3">
        <div class="card-body">

            <label class="form-label fw-bold">
                タスクカラー
            </label>

            <input
                type="color"
                id="goal-color"
                class="form-control form-control-color"
                value="#198754"
                aria-label="Task color"
                title="Task color">

        </div>
    </div>
    <!-- ボタン -->
    <div class="card shadow">
        <div class="card-body">

            <button
                class="btn btn-success w-100 mb-2"
                onclick="updateTask()">
                編集保存
            </button>

            <button
                class="btn btn-secondary w-100"
                onclick="location.href='/gtasutkuitiran'">
                戻る
            </button>

        </div>
    </div>

</div>

<x-menubar />

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

<script>

const editId = Number(localStorage.getItem("editTaskId"));

const goals = JSON.parse(localStorage.getItem("groupGoals")) || [];

const goal = goals.find(g => g.id === editId);

if (goal) {

    document.getElementById("goal-name").value = goal.name;

    document.getElementById("start-timing").value = goal.startTime;

    document.getElementById("duration-hours").value = goal.durationHours;

    document.getElementById("duration-minutes").value = goal.durationMinutes;

    if (goal.days) {

        document.querySelectorAll(".day").forEach(btn => {

            btn.classList.remove("active");
            btn.classList.remove("btn-success");
            btn.classList.add("btn-outline-secondary");

            if (goal.days.includes(btn.textContent.trim())) {

                btn.classList.add("active");
                btn.classList.remove("btn-outline-secondary");
                btn.classList.add("btn-success");

            }

        });

    }

    document.getElementById("start-date").value = goal.startDate;

    document.getElementById("end-date").value = goal.endDate;

    document.getElementById("goal-color").value = goal.color;

    togglePriorityArea();

}

if (goal.mode) {

    document.querySelectorAll(".mode").forEach(btn => {

        btn.classList.remove("active");
        btn.classList.remove("btn-primary");
        btn.classList.add("btn-outline-primary");

        if (btn.textContent.trim() === goal.mode) {

            btn.classList.add("active");
            btn.classList.remove("btn-outline-primary");
            btn.classList.add("btn-primary");

        }

    });

}

if (goal.priority) {

    document.querySelectorAll(".priority").forEach(btn => {

        btn.classList.remove("active");
        btn.classList.remove("btn-secondary");
        btn.classList.add("btn-outline-secondary");

        if (btn.textContent.trim() === goal.priority) {

            btn.classList.add("active");
            btn.classList.remove("btn-outline-secondary");
            btn.classList.add("btn-secondary");

        }

    });

}


const storageKey = "groupGoals";

function updateTask() {

    const goalName = document.getElementById("goal-name").value.trim();

    const startDate = document.getElementById("start-date").value;

    const hours = Number(document.getElementById("duration-hours").value);

    const minutes = Number(document.getElementById("duration-minutes").value);

    if (goalName === "") {
        alert("タスク名を入力してください");
        return;
    }

    if (startDate === "") {
        alert("開始日を入力してください");
        return;
    }

    if (hours < 0 || minutes < 0 || hours > 23 || minutes > 59) {
        alert("正しい所要時間を入力してください");
        return;
    }

    if (hours === 0 && minutes === 0) {
        alert("所要時間を入力してください");
        return;
    }

    const goals = JSON.parse(localStorage.getItem("groupGoals")) || [];

    const editId = Number(localStorage.getItem("editTaskId"));

    const index = goals.findIndex(goal => goal.id === editId);

    const selectedDays = [];

    document.querySelectorAll(".day.active").forEach(day => {
        selectedDays.push(day.textContent.trim());
    });

    const mode = document.querySelector(".mode.active").textContent.trim();

    const priority = document.querySelector(".priority.active").textContent.trim();

    if (index === -1) {
        alert("タスクが見つかりません");
        return;
    }

    goals[index].startTime = document.getElementById("start-timing").value;
    goals[index].durationHours = hours;
    goals[index].durationMinutes = minutes;
    goals[index].startDate = startDate;
    goals[index].endDate = document.getElementById("end-date").value;
    goals[index].color = document.getElementById("goal-color").value;
    goals[index].mode = mode;
    goals[index].priority = priority;
    goals[index].days = selectedDays;

    localStorage.setItem("groupGoals", JSON.stringify(goals));

    alert("編集内容を保存しました");

    location.href = "/gtasutkuitiran";
}
document.querySelectorAll(".priority").forEach(button => {

    button.addEventListener("click", () => {

        document.querySelectorAll(".priority").forEach(btn => {
            btn.classList.remove("active");
            btn.classList.remove("btn-secondary");
            btn.classList.add("btn-outline-secondary");
        });

        button.classList.add("active");
        button.classList.remove("btn-outline-secondary");
        button.classList.add("btn-secondary");

    });

});

document.querySelectorAll(".mode").forEach(button => {

    button.addEventListener("click", () => {

        document.querySelectorAll(".mode").forEach(btn => {
            btn.classList.remove("active");
            btn.classList.remove("btn-primary");
            btn.classList.add("btn-outline-primary");
        });

        button.classList.add("active");
        button.classList.remove("btn-outline-primary");
        button.classList.add("btn-primary");

        togglePriorityArea();

    });

});

document.querySelectorAll(".priority").forEach(button => {

    button.addEventListener("click", () => {

        document.querySelectorAll(".priority").forEach(btn => {
            btn.classList.remove("active");
            btn.classList.remove("btn-secondary");
            btn.classList.add("btn-outline-secondary");
        });

        button.classList.add("active");
        button.classList.remove("btn-outline-secondary");
        button.classList.add("btn-secondary");

    });

});

function togglePriorityArea() {

    const mode = document.querySelector(".mode.active").textContent.trim();

    const priorityArea = document.getElementById("priorityArea");

    if (mode === "優先順位") {
        priorityArea.style.display = "block";
    } else {
        priorityArea.style.display = "none";
    }

}

togglePriorityArea();
    //頻度選択

document.querySelectorAll(".day").forEach(button => {

    button.addEventListener("click", () => {

        button.classList.toggle("active");

        if (button.classList.contains("active")) {
            button.classList.remove("btn-outline-secondary");
            button.classList.add("btn-success");
        } else {
            button.classList.remove("btn-success");
            button.classList.add("btn-outline-secondary");
        }

    });

});

   //下メニュー

const menuButtons = document.querySelectorAll(".navbar button");

menuButtons.forEach(button => {

    button.addEventListener("click", () => {

        menuButtons.forEach(btn => {
            btn.classList.remove("btn-success");
            btn.classList.add("btn-outline-secondary");
        });

        button.classList.remove("btn-outline-secondary");
        button.classList.add("btn-success");

    });

});

   //頻度ボタン

document.querySelectorAll(".day").forEach(button => {

    button.addEventListener("click", () => {

        button.classList.toggle("active");

        if (button.classList.contains("active")) {
            button.classList.remove("btn-outline-secondary");
            button.classList.add("btn-success");
        } else {
            button.classList.remove("btn-success");
            button.classList.add("btn-outline-secondary");
        }

    });

});

</script>

</body>
</html>