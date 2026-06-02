<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <link rel="stylesheet" href="{{asset('css/newuser.css')}}">
    <title>新規登録</title>

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
            <input type="text" id="birth_date"  name="birth_date" placeholder="生年月日(例:2000/01/01)" required>
        </div>
        <div class="form-group">
            <input type="password" id="password"  name="password" placeholder="パスワード" required>
        </div>
        <div class="form-group">
            <input type="password" id="password_confirm"  name="password_confirm" placeholder="パスワード再入力" required>
        </div>
        <button type="submit" class="submit-btn" id="submitButton">登録する</button>
        <button type="button" class="back-btn" onclick="location.href='/login'">取消・戻る</button>
    </form>
    <div id="message"></div>
</div>
    <script src="{{asset('/js/newuser.js')}}"></script>
</body>
</html>