<!DOCTYPE html>
<html lang="ja">
<head>
<meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
<link rel="stylesheet" href="{{ asset('css/passwordReset.css') }}">
<title>パスワード再設定</title>

</head>

<body>

<div class="container">

    <div class="title">パスワード再発行</div>

    <form id="resetForm">
        @csrf
        <div class="form-group">
            <label>メールアドレス</label>
            <input type="email" id="email" placeholder="example@email.com" required>
        </div>

        <div class="form-group">
            <label>生年月日</label>
            <input type="text" id="birth_date" placeholder="例: 2000/01/01" required>
        </div>

        <div class="form-group">
            <label>新しいパスワード</label>
            <input type="password" id="password" required>
        </div>

        <div class="form-group">
            <label>新しいパスワード（確認）</label>
            <input type="password" id="password_confirmation" required>
        </div>

        <button type="submit" class="submit-btn" id="submitButton">再設定する</button>
        <button type="button" class="back-btn" id="backButton" onclick="history.back()">戻る</button>
    </form>

</div>
    <script src="{{asset('/js/passwordReset.js')}}"></script>
</body>
</html>
