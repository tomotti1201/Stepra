<!DOCTYPE html>
<html lang="ja">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta name="csrf-token" content="{{ csrf_token() }}">
<title>目標編集 | STEPRA</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">


<button class="btn btn-danger" onclick="logout()">
    ログアウト
</button>

<script>
function logout() {

    localStorage.removeItem("user_id");

    location.href = "/login";
}
</script>
<x-menubar />
</html>