<!DOCTYPE html>
<html lang="ja">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>新規登録 | STEPRA</title>

  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<!-- 【共通ルール準拠】レイアウトコンテナ -->
<div class="container py-5">
  <img src="{{ asset('image/tit.png') }}" class="mb-3" style="width:200px;">
  <div class="row justify-content-center">
    <!-- 【共通ルール準拠】レスポンシブカラム幅（ログイン・新規登録に最適化） -->
    <!-- <div class="col-12 col-md-8 col-lg-5"> -->

      <!-- 【共通ルール準拠】カード（card shadow） -->
      <!-- <div class="card shadow border-0"> -->
        <div class="card-body p-4">
          
          <!-- 【共通ルール準拠】タイトル（中央寄せ＋太字） -->
          <h2 class="text-center fw-bold mb-4 fs-4">新規登録</h2>

          <div id="error-message"
     class="text-danger text-center mb-3">
    </div>
          <form>

    <div class="mb-3">
        <input type="text"
               class="form-control"
               id="name"
               placeholder="ユーザー名"
               required>
    </div>

    <div class="mb-3">
        <input type="email"
               class="form-control"
               id="email"
               placeholder="メールアドレス"
               required>
    </div>

    <div class="mb-3">
        <input type="text"
               class="form-control"
               id="birth_date"
               placeholder="生年月日 (例: 20000101)"
               required>
    </div>

    <div class="mb-3">
        <input type="password"
               class="form-control"
               id="password"
               placeholder="パスワード"
               required>
    </div>

    <div class="mb-4">
        <input type="password"
               class="form-control"
               id="password_confirm"
               placeholder="パスワード再入力"
               required>
    </div>

    <button type="button"
            class="btn btn-success w-100 mb-2 py-2 fw-bold"
            onclick="signup()">
        登録する
    </button>
    <button
        type="button"
        class="btn btn-secondary w-100"
        onclick="location.href='/login'">

        ログイン

        </button>

</form>
        </div>
      <!-- </div> -->

    <!-- </div> -->
  </div>
</div>

<script>

async function signup(){

document.getElementById("error-message")
    .textContent = "";
    const name =
        document.getElementById("name").value;

    const email =
        document.getElementById("email").value;

    const birth_date =
        document.getElementById("birth_date").value;

    const password =
        document.getElementById("password").value;

    const password_confirm =
        document.getElementById("password_confirm").value;

    if(password !== password_confirm){

    document.getElementById("error-message")
        .textContent = "パスワードが一致しません";

    return;
}

    try{

        const response = await fetch(
            "http://localhost:8000/api/signup",
            {
                method: "POST",
                headers:{
                    "Content-Type":"application/json"
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

if(response.ok){

    location.href = "/login";

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