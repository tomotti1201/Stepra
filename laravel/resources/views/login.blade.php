<!DOCTYPE html>
<html lang="ja">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <link rel="stylesheet" href="{{asset('/css/login.css')}}">
  <title>STEPRA ログイン画面</title>

</head>

<body>

  <div class="phone">

    <div class="title">
      STEPRA
    </div>

    <div class="form-area">

      <div class="form-group">
        <input type="text" id="username" placeholder="ユーザー名を入力する場所">
      </div>

      <div class="form-group">
        <input type="password" id="password" placeholder="パスワードを入力する場所">
      </div>

      <button class="login-btn" onclick="login()">
        ログイン
      </button>

      <button class="sub-btn" onclick="location.href='passwordReset.html'">
        パスワードを忘れた場合
      </button>

      <button class="sub-btn" onclick="location.href='/newuser'">
        新規アカウント作成
      </button>

      <div class="message" id="message"></div>

    </div>

  </div>

    <script src="{{asset('/js/login.js')}}"></script>


</body>
</html>