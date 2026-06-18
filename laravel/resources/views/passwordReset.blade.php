<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>パスワード再設定 | STEPRA</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="bg-light">

    <div class="container">
        <div class="row justify-content-center min-vh-100 align-items-center">
            <div class="col-12 col-md-8 col-lg-5">

                <!-- ロゴ -->
                <div class="text-center mb-4">
                    <img src="{{ asset('image/t.png') }}" alt="ロゴ" class="mb-3" style="width:500px; height:auto;">
                </div>

                <div id="message" class="text-danger text-center mb-3"></div>

                <div class="mb-3">
                    <label class="form-label">メールアドレス</label>
                    <input type="email" id="email" class="form-control" placeholder="example@email.com">
                </div>

                <div class="mb-3">
                    <label class="form-label">生年月日</label>
                    <input type="text" id="birth_date" class="form-control" placeholder="20000101">
                </div>

                <div class="mb-3">
                    <label class="form-label">新しいパスワード</label>
                    <input type="password" id="password" class="form-control">
                </div>

                <div class="mb-4">
                    <label class="form-label">新しいパスワード（確認）</label>
                    <input type="password" id="password_confirmation" class="form-control">
                </div>

                <button type="button" id="submitButton" class="btn btn-success w-100 mb-2" onclick="resetPassword()">再設定する</button>

                <button type="button" class="btn btn-secondary w-100" onclick="history.back()">戻る</button>

            </div>
        </div>
    </div>

    <script src="{{ asset('/js/passwordReset.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>