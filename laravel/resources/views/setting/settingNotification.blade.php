<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>STEPRA ユーザー情報</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="bg-light">

<script>
    if (!localStorage.getItem("user_id")) {
        location.href = "/login";
    }
</script>

<div class="container py-4 mb-5">

    <!-- タイトル -->
    <img
        src="{{ asset('image/tit.png') }}"
        class="mb-4"
        style="width:200px;"
    >

    <div class="row">

        <!-- 左メニュー -->
        <div class="col-md-3 mb-3">

            @include('components.settingmenubar')

        </div>

    </div>

</div>

<x-menubar />


<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>