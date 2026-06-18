<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>新規登録 | STEPRA</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="bg-light">

    <div class="container">
        <div class="row justify-content-center min-vh-100 align-items-center">
            <div class="col-12 col-md-8 col-lg-5">

                <div class="text-center mb-4">
                    <img src="{{ asset('image/t.png') }}" alt="ロゴ" class="mb-3" style="width:500px; height:auto;">
                </div>

                <div id="error-message" class="text-danger text-center mb-3"></div>

                <div class="mb-3">
                    <label class="form-label">ユーザー名</label>
                    <input type="text" id="name" class="form-control" placeholder="ユーザー名">
                </div>

                <div class="mb-3">
                    <label class="form-label">メールアドレス</label>
                    <input type="email" id="email" class="form-control" placeholder="example@email.com">
                </div>

                <div class="mb-3">
                    <label class="form-label">生年月日</label>
                    <input type="text" id="birth_date" class="form-control" placeholder="20000101">
                </div>

                <div class="mb-3">
                    <label class="form-label">パスワード</label>
                    <input type="password" id="password" class="form-control" placeholder="パスワード">
                </div>

                <div class="mb-4">
                    <label class="form-label">パスワード再入力</label>
                    <input type="password" id="password_confirm" class="form-control" placeholder="パスワード再入力">
                </div>

                <button type="button" class="btn btn-success w-100 mb-2" onclick="signup()">登録する</button>

                <button type="button" class="btn btn-secondary w-100" onclick="location.href='/login'">ログイン</button>

            </div>
        </div>
    </div>

    <script>
        async function signup() {
            document.getElementById("error-message").textContent = "";

            const name = document.getElementById("name").value;
            const email = document.getElementById("email").value;
            const birth_date = document.getElementById("birth_date").value;
            const password = document.getElementById("password").value;
            const password_confirm = document.getElementById("password_confirm").value;

            if (password !== password_confirm) {
                document.getElementById("error-message").textContent = "パスワードが一致しません";
                return;
            }

            try {
                const response = await fetch(
                    "http://localhost:8000/api/signup",
                    {
                        method: "POST",
                        headers: {
                            "Content-Type": "application/json"
                        },
                        body: JSON.stringify({
                            name: name,
                            email: email,
                            birth_date: birth_date,
                            password: password
                        })
                    }
                );

                const data = await response.json();

                console.log(data);

                if (response.ok) {
                    location.href = "/login";
                } else {
                    document.getElementById("error-message").textContent = data.message;
                }

            } catch (error) {
                console.error(error);
                alert("通信エラー");
            }
        }
    </script>

</body>
</html>