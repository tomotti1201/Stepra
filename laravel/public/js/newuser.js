const form = document.getElementById('signupForm');
const submitButton = document.getElementById('submitButton');
const message = document.getElementById('message');

function showMessage(text, state) {
    message.textContent = text;
    message.className = `message ${state}`;
    message.dataset.state = state === 'idle' ? 'idle' : 'visible';
}

form.addEventListener('submit', async (event) => {
    event.preventDefault();

    const payload = {
        name: document.getElementById('name').value.trim(),
        email: document.getElementById('email').value.trim(),
        birth_date: document.getElementById('birth_date').value.trim(),
        password: document.getElementById('password').value,
    };

    submitButton.disabled = true;
    submitButton.textContent = '送信中...';
    showMessage('登録を送信しています。', 'idle');

    try {
        const response = await fetch('/api/signup', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify(payload),
        });

        const result = await response.json();

        if (!response.ok) {
            throw new Error(result.message || '登録に失敗しました');
        }

        showMessage(`${result.message} (${result.user.email})`, 'success');
        form.reset();
    } catch (error) {
        showMessage(error.message, 'error');
    } finally {
        submitButton.disabled = false;
        submitButton.textContent = '登録する';
    }
});