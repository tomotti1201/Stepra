<!DOCTYPE html>
<html lang="ja">
<head>
<meta charset="UTF-8">
<title>新規登録</title>
<style>
    *{
        margin: 0;
        padding: 0;
        box-sizing: border-box;
        font-family: sans-serif;
    }
    body {
        background-color: #e6e6e6;
        font-family: sans-serif;
    }

    .container {
        width: 280px;
        margin: 30px auto;
        padding: 25px;
        background-color: #d8e8da;;
        border-radius: 40px;
        border: 2px solid #333;
    }

    .title {
        text-align: center;
        padding: 10px;
        background-color: #3cff00;
        border: 1px solid #333;
        border-radius: 10px;
        margin-bottom: 20px;
        font-size: 16px;
    }

    .form-group {
        margin-bottom: 15px;
    }

    input {
        width: 100%;
        padding: 10px;
        border: 1px solid #999;
        background-color: white;
        box-sizing: border-box;
    }

    .submit-btn {
        width: 100%;
        padding: 12px;
        background-color: #3cff00;
        color: #333;
        border: 1px solid #333;
        margin-top: 10px;
        cursor: pointer;
        font-size: 14px;
    }

    .back-btn {
        width: 100%;
        padding: 12px;
        background-color: #f26c6c;
        color: #333;
        border: 1px solid #333;
        margin-top: 10px;
        cursor: pointer;
        font-size: 14px;
    }
</style>
</head>

<body>

<div class="container">
    <div class="title">新規登録</div>
    <form method="POST" id="signupForm" autocomplete="on">
        <div class="form-group">
            <input type="text" id="name" name="username" placeholder="ユーザー名" required>
        </div>
        <div class="form-group">
            <input type="email" id="email"  name="email" placeholder="メールアドレス" required>
        </div>
        <div class="form-group">
            <input type="text" id="birthdate"  name="birthdate" placeholder="生年月日(例:2000/01/01)" required>
        </div>
        <div class="form-group">
            <input type="password" id="password"  name="password" placeholder="パスワード" required>
        </div>
        <div class="form-group">
            <input type="password" id="password_confirm"  name="password_confirm" placeholder="パスワード再入力" required>
        </div>
        <button type="submit" class="submit-btn" id="submitButton">登録する</button>
        <button type="button" class="back-btn" onclick="location.href='roguin.html'">取消・戻る</button>
    </form>
    <div id="message"></div>
</div>
<script>
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
    </script>
</body>
</html>