const form = document.getElementById('taskForm');
const submitButton = document.getElementById('submitButton');
const message = document.getElementById('message');

function showMessage(text, state) {
    message.textContent = text;
    message.className = `message ${state}`;
    message.dataset.state = state === 'idle' ? 'idle' : 'visible';
}

/*
|--------------------------------------------------------------------------
| 曜日選択
|--------------------------------------------------------------------------
*/

document.querySelectorAll('#frequency-group .day').forEach(day => {
    day.addEventListener('click', () => day.classList.toggle('active'));
});

/*
|--------------------------------------------------------------------------
| モード選択
|--------------------------------------------------------------------------
*/

const priorityBox = document.getElementById('priority-box');

if (
    document.querySelector('#mode-group .active')?.innerText === '自由設定'
) {
    priorityBox.classList.add('disabled-group');
}

document.querySelectorAll('#mode-group .day').forEach(button => {
    button.addEventListener('click', () => {

        document.querySelectorAll('#mode-group .day')
            .forEach(item => item.classList.remove('active'));

        button.classList.add('active');

        if (button.innerText === '自由設定') {
            priorityBox.classList.add('disabled-group');
        } else {
            priorityBox.classList.remove('disabled-group');
        }
    });
});

/*
|--------------------------------------------------------------------------
| 優先度選択
|--------------------------------------------------------------------------
*/

document.querySelectorAll('#priority-group .day').forEach(button => {
    button.addEventListener('click', () => {

        const mode =
            document.querySelector('#mode-group .active')?.innerText;

        if (mode === '自由設定') return;

        document.querySelectorAll('#priority-group .day')
            .forEach(item => item.classList.remove('active'));

        button.classList.add('active');
    });
});

/*
|--------------------------------------------------------------------------
| カラー選択
|--------------------------------------------------------------------------
*/

document.querySelectorAll('#color-group .color-circle').forEach(circle => {
    circle.addEventListener('click', () => {

        document.querySelectorAll('#color-group .color-circle')
            .forEach(item => item.classList.remove('active'));

        circle.classList.add('active');
    });
});

/*
|--------------------------------------------------------------------------
| カラー追加
|--------------------------------------------------------------------------
*/

const addColorButton = document.getElementById('add-color-btn');
const colorPicker = document.getElementById('custom-color-picker');

addColorButton.addEventListener('click', () => {
    colorPicker.click();
});

colorPicker.addEventListener('change', () => {

    const color = colorPicker.value;

    const newCircle = document.createElement('div');
    newCircle.classList.add('color-circle');
    newCircle.dataset.color = color;
    newCircle.style.background = color;

    addColorButton.before(newCircle);

    document.querySelectorAll('#color-group .color-circle')
        .forEach(item => item.classList.remove('active'));

    newCircle.classList.add('active');

    newCircle.addEventListener('click', () => {
        document.querySelectorAll('#color-group .color-circle')
            .forEach(item => item.classList.remove('active'));

        newCircle.classList.add('active');
    });
});

/*
|--------------------------------------------------------------------------
| 登録
|--------------------------------------------------------------------------
*/

form.addEventListener('submit', async event => {

    event.preventDefault();

    const payload = {
        user_id: 1,
        title: document.getElementById('goal-name').value,

        week_days: Array.from(
            document.querySelectorAll('#frequency-group .active')
        ).map(day => day.innerText),

        start_time: document.getElementById('start-timing').value,
        duration_hours: document.getElementById('duration-hours').value,
        duration_minutes: document.getElementById('duration-minutes').value,

        priority:
            document.querySelector('#mode-group .active')?.innerText === '自由設定'
                ? 'null'
                : document.querySelector('#priority-group .active')?.innerText,

        color:
            document.querySelector('#color-group .active')?.dataset.color,

        start_date: document.getElementById('start-date').value,
        end_date: document.getElementById('end-date').value
    };

    submitButton.disabled = true;
    submitButton.textContent = '登録中...';

    showMessage('目標を登録しています。', 'idle');

    try {

        const response = await fetch('/api/tasks', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify(payload)
        });

        const result = await response.json();

        if (!response.ok) {
            throw new Error(result.message || '登録に失敗しました');
        }

        showMessage(result.message, 'success');
        form.reset();

    } catch (error) {

        showMessage(error.message, 'error');

    } finally {

        submitButton.disabled = false;
        submitButton.textContent = '登録する';

    }
});