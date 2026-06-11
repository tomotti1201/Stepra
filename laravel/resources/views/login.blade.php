<!DOCTYPE html>
<html lang="ja">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<title>STEPRA ログイン</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

</head>

<body class="bg-light">

<div class="container">

    <div class="row justify-content-center min-vh-100 align-items-center">

        <div class="col-12 col-md-8 col-lg-5">

            <!-- タイトル -->
            <div class="text-center mb-4">
                    <img src="{{ asset('image/t.png') }}"
                    alt="ロゴ"
                    class="mb-3"
                    style="width: 500px; height: auto;">
            </div>

            <div id="error-message"
     class="text-danger text-center mb-3">
            </div>

                    <!--ユーザー名-->
                    <div class="mb-3">

                        <label class="form-label">
                            メールアドレス
                        </label>

                        <input
                            type="email"
                            id="email"
                            class="form-control"
                            placeholder="example@email.com">

                    </div>

                    <!-- メールアドレス -->

                    <!-- <div class="mb-3">

                        <label class="form-label">
                            メールアドレス
                        </label>

                        <input
                            type="email"
                            id="email"
                            class="form-control"
                            placeholder="example@email.com">

                    </div> -->

                    <!-- パスワード -->

                    <div class="mb-4">

                        <label class="form-label">
                            パスワード
                        </label>

                        <input
                            type="password"
                            id="password"
                            class="form-control"
                            placeholder="パスワード">

                    </div>

                    <!-- ログイン -->

                    <button
                        type="button"
                        class="btn btn-success w-100 mb-2"
                        onclick="login()">

                        ログイン

                    </button>

                    <!-- 新規登録 -->

                    <button
                      type="button"
                        class="btn btn-secondary w-100"
                        onclick="location.href='/newuser'">

                        新規登録

                    </button>

                    <!--パスワードを忘れた場合-->

                    <button
                        class="btn btn-link w-100"
                        onclick="forgotPassword()">

                        パスワードを忘れた場合

                    </button>

                </div>

            </div>

        </div>

    </div>

</div>

<script>

async function login(){


    document.getElementById("error-message").textContent = "";

    const email =
        document.getElementById("email").value;

    const password =
        document.getElementById("password").value;

    try{

        const response = await fetch(
            "http://localhost:8000/api/login",
            {
                method: "POST",
                headers: {
                    "Content-Type": "application/json"
                },
                body: JSON.stringify({
                    email: email,
                    password: password
                })
            }
        );

        const data = await response.json();

        console.log(data);

        if(response.ok){

        localStorage.setItem(
          "user_id",
          data.user.id
        );

    location.href = "/home";

}else{

    document.getElementById("error-message")
        .textContent = data.message;

}

    }catch(error){

        console.error(error);

        alert("通信エラー");

    }

}

</script>

</body>
</html>