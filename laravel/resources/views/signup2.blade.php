<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>会員登録</title>
    <style>
        :root {
            --bg: #0f172a;
            --bg2: #111827;
            --card: rgba(15, 23, 42, 0.86);
            --border: rgba(148, 163, 184, 0.2);
            --text: #e2e8f0;
            --muted: #94a3b8;
            --primary: #22c55e;
            --primary-strong: #16a34a;
            --danger: #ef4444;
            --shadow: 0 24px 80px rgba(0, 0, 0, 0.35);
        }

        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            min-height: 100vh;
            display: grid;
            place-items: center;
            font-family: "Noto Sans JP", "Hiragino Kaku Gothic ProN", "Yu Gothic", sans-serif;
            color: var(--text);
            background:
                radial-gradient(circle at top left, rgba(34, 197, 94, 0.22), transparent 30%),
                radial-gradient(circle at top right, rgba(59, 130, 246, 0.18), transparent 28%),
                linear-gradient(160deg, var(--bg), var(--bg2));
            padding: 24px;
        }

        .shell {
            width: min(100%, 980px);
            display: grid;
            grid-template-columns: 1.05fr 0.95fr;
            gap: 24px;
            align-items: stretch;
        }

        .hero,
        .card {
            border: 1px solid var(--border);
            border-radius: 24px;
            background: var(--card);
            box-shadow: var(--shadow);
            -webkit-backdrop-filter: blur(18px);
            backdrop-filter: blur(18px);
        }

        .hero {
            padding: 32px;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            overflow: hidden;
            position: relative;
        }

        .hero::after {
            content: "";
            position: absolute;
            inset: auto -40px -40px auto;
            width: 180px;
            height: 180px;
            border-radius: 50%;
            background: radial-gradient(circle, rgba(34, 197, 94, 0.25), transparent 70%);
            pointer-events: none;
        }

        .eyebrow {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            width: fit-content;
            padding: 8px 12px;
            border-radius: 999px;
            background: rgba(34, 197, 94, 0.12);
            color: #86efac;
            font-size: 12px;
            letter-spacing: 0.08em;
            text-transform: uppercase;
        }

        h1 {
            margin: 18px 0 12px;
            font-size: clamp(2.2rem, 5vw, 4rem);
            line-height: 1.05;
        }

        .lead {
            margin: 0;
            max-width: 32rem;
            color: var(--muted);
            font-size: 1rem;
            line-height: 1.8;
        }

        .stats {
            display: grid;
            grid-template-columns: repeat(3, minmax(0, 1fr));
            gap: 12px;
            margin-top: 28px;
        }

        .stat {
            padding: 16px;
            border-radius: 18px;
            background: rgba(15, 23, 42, 0.55);
            border: 1px solid rgba(148, 163, 184, 0.14);
        }

        .stat strong {
            display: block;
            font-size: 1.2rem;
            margin-bottom: 6px;
        }

        .stat span {
            color: var(--muted);
            font-size: 0.88rem;
        }

        .card {
            padding: 28px;
        }

        .card h2 {
            margin: 0 0 10px;
            font-size: 1.5rem;
        }

        .card p {
            margin: 0 0 24px;
            color: var(--muted);
        }

        form {
            display: grid;
            gap: 16px;
        }

        label {
            display: grid;
            gap: 8px;
            font-size: 0.95rem;
        }

        input {
            width: 100%;
            border: 1px solid rgba(148, 163, 184, 0.18);
            border-radius: 14px;
            padding: 14px 16px;
            background: rgba(15, 23, 42, 0.8);
            color: var(--text);
            outline: none;
            transition: border-color 0.2s ease, transform 0.2s ease, box-shadow 0.2s ease;
        }

        input:focus {
            border-color: rgba(34, 197, 94, 0.8);
            box-shadow: 0 0 0 4px rgba(34, 197, 94, 0.12);
        }

        .row {
            display: grid;
            gap: 16px;
        }

        button {
            border: 0;
            border-radius: 14px;
            padding: 14px 16px;
            font: inherit;
            font-weight: 700;
            color: #052e16;
            background: linear-gradient(135deg, #86efac, var(--primary));
            cursor: pointer;
            transition: transform 0.2s ease, filter 0.2s ease;
        }

        button:hover {
            transform: translateY(-1px);
            filter: brightness(1.02);
        }

        button:disabled {
            opacity: 0.7;
            cursor: wait;
            transform: none;
        }

        .message {
            min-height: 44px;
            padding: 12px 14px;
            border-radius: 14px;
            border: 1px solid transparent;
            line-height: 1.6;
        }

        .message[data-state="idle"] {
            display: none;
        }

        .message.success {
            background: rgba(34, 197, 94, 0.12);
            border-color: rgba(34, 197, 94, 0.24);
            color: #bbf7d0;
        }

        .message.error {
            background: rgba(239, 68, 68, 0.12);
            border-color: rgba(239, 68, 68, 0.24);
            color: #fecaca;
        }

        .footer-note {
            margin-top: 18px;
            color: var(--muted);
            font-size: 0.9rem;
            line-height: 1.7;
        }

        @media (max-width: 860px) {
            .shell {
                grid-template-columns: 1fr;
            }

            .stats {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>
<body>
    <main class="shell">
        <section class="hero">
            <div>
                <div class="eyebrow">STEPRA / signup api</div>
                <h1>アカウント登録を 1 画面で実行</h1>
                <p class="lead">
                    名前、メールアドレス、パスワードを入力すると backend/login.php に送信して、
                    データベースへユーザーを登録します。
                </p>
            </div>

            <div class="stats" aria-label="feature summary">
                <div class="stat">
                    <strong>JSON送信</strong>
                    <span>fetch で API を呼び出し</span>
                </div>
                <div class="stat">
                    <strong>即時表示</strong>
                    <span>成功・失敗メッセージを表示</span>
                </div>
                <div class="stat">
                    <strong>重複対策</strong>
                    <span>既存メールは登録不可</span>
                </div>
            </div>
        </section>

        <section class="card">
            <h2>会員登録フォーム</h2>
            <p>3つの項目を入力して登録を実行してください。</p>

            <form id="signupForm" autocomplete="off">
                <label>
                    名前
                    <input type="text" name="name" id="name" placeholder="山田 太郎" required>
                </label>

                <label>
                    メールアドレス
                    <input type="email" name="email" id="email" placeholder="taro@example.com" required>
                </label>

                <label>
                    パスワード
                    <input type="password" name="password" id="password" placeholder="8文字以上" minlength="8" required>
                </label>

                <button type="submit" id="submitButton">登録する</button>
                <div class="message" id="message" data-state="idle" aria-live="polite"></div>
            </form>

            <div class="footer-note">
                API 先は <strong>backend/signup.php</strong> です。<br>
                同じサーバーで配信していれば、そのまま動作します。
            </div>
        </section>
    </main>

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
                const response = await fetch('http://localhost/backend/signup.php', {
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