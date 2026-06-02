const form = document.getElementById('resetForm');
const submitButton = document.getElementById('submitButton');
const message = document.getElementById('message')

function showMessage(text,state){
    message.textContent = text;
    message.className = `message ${state}`;
    message.dataset.state = state === 'idle' ? 'idle' : 'visible';
}

form.addEventListener('submit',async(event) => {
    event.preventDefault();

    const payload = {
        email:document.getElementById('email').value.trim(),
        birth_date:document.getElementById('birth_date').value.trim(),
        password:document.getElementById('password').value,
        password_confirmation:document.getElementById('password_confirmation').value,
    };
    submitButton.disabled = true;
    submitButton.textContent = '送信中...';
    showMessage('パスワードを更新しています。', 'idle');

    try{
        const response = await fetch('/api/passwordReset',{
            method:'POST',
            headers:{
                'Content-Type': 'application/json',
            },
            body: JSON.stringify(payload),
        });
        const result = await response.json();

        if (!response.ok) {
            throw new Error(result.message || 'パスワード更新に失敗しました');
        }

        showMessage(result.message, 'success');
        form.reset();
    } catch (error) {
        showMessage(error.message, 'error');
    } finally {
        submitButton.disabled = false;
        submitButton.textContent = '再設定する';
    }
})