const submitButton = document.getElementById('submitButton');

const message = document.getElementById('message');

function showMessage(text, state) {
    message.textContent = text;

    if (state === 'success') {
        message.className =
            'text-success text-center mb-3';
    } else if (state === 'error') {
        message.className =
            'text-danger text-center mb-3';
    } else {
        message.className =
            'text-secondary text-center mb-3';
    }
}

async function resetPassword() {
    const payload = {
        email:
            document.getElementById('email')
                .value
                .trim(),
        birth_date:
            document.getElementById('birth_date')
                .value
                .trim(),
        password:
            document.getElementById('password')
                .value,
        password_confirmation:
            document.getElementById('password_confirmation')
                .value,
    };

    submitButton.disabled = true;

    try {
        const response = await fetch(
            '/api/passwordReset',
            {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify(payload),
            }
        );
        const result =
            await response.json();

        if (!response.ok) {
            showMessage(
                result.message ||
                'パスワード更新に失敗しました',
                'error'
            );
            return;
        }
        showMessage(
            result.message,
            'success'
        );

        document.getElementById('email').value = '';
        document.getElementById('birth_date').value = '';
        document.getElementById('password').value = '';
        document.getElementById('password_confirmation').value = '';

    } catch (error) {
        console.error(error);

        showMessage(
            '通信エラー',
            'error'
        );
    } finally {
        submitButton.disabled = false;
        submitButton.textContent = '再設定する';
    }
}