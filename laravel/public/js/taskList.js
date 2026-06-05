async function loadTasks() {

const response = await fetch('/api/tasks');
const result = await response.json();
const taskList = document.getElementById('taskList');

taskList.innerHTML = '';

result.tasks.forEach(task => {

    const hours = Math.floor(task.required_minutes / 60);
    const minutes = task.required_minutes % 60;

    taskList.innerHTML += `
    <div class="item">

        <div class="content">

        <strong>${task.title}</strong><br>

        頻度：${task.week_days}<br>

        開始：${task.start_time}<br>

        時間：${hours}時間${minutes}分

        </div>

        <div class="buttons">
        <a href="#" class="edit">編集</a>
        <a href="#" class="delete">削除</a>
        </div>

    </div>
    `;
});
}

loadTasks();