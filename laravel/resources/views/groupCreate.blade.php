<!DOCTYPE html>
<html lang="ja">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>STEPRA 新規グループ作成</title>

  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<div class="container py-4 mb-5">
  
      <!-- タイトル -->
      <img src="{{ asset('image/tit.png') }}" class="mb-3" style="width:200px;">


    <h2 class="text-center fw-bold display-6 mb-5 fs-4">
      新規作成
    </h2>

    <div class="mb-4">
      <label for="groupName" class="form-label fw-bold">グループ名</label>
      <input type="text" 
             class="form-control form-control-lg" 
             id="groupName" 
             placeholder="グループ名を入力" 
             oninput="checkGroupName()">
    </div>

    <div class="mb-5">
      <label for="groupDesc" class="form-label fw-bold">グループ説明（任意）</label>
      <textarea class="form-control form-control-lg" 
                id="groupDesc" 
                rows="5" 
                placeholder="グループ説明を入力" 
                oninput="autoResize(this)"></textarea>
    </div>

    <div class="mb-5">
      <label for="groupRole" class="form-label fw-bold">ロール</label>
      <select id="groupRole" class="form-select form-select-lg">
        <option value="admin">管理者</option>
        <option value="member">メンバー</option>
      </select>
    </div>

    <button class="btn btn-success w-100 mb-3 py-3 fw-bold fs-5" 
            id="createGroupBtn" 
            onclick="createGroup()" >
      グループ作成
    </button>
</div>

    <button class="btn btn-secondary w-100 py-3 fw-bold fs-5" 
            onclick="goBack()">
      戻る
    </button>

  </div>
</div>
  <x-menubar />

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

<script>
/* ===================================================== */
/* グループ作成 */
/* ===================================================== */
async function createGroup() {
  const description = document.getElementById("groupDesc").value;
  const groupname = document.getElementById("groupName").value;
  const role = document.getElementById("groupRole").value;

  const userResponse = await fetch("/api/current-user");

  if (!userResponse.ok) {
    alert("ログイン情報を取得できませんでした。もう一度ログインしてください");
    return;
  }

  const currentUser = await userResponse.json();

  const groupResponse = await fetch("/api/groups", {
    method: "POST",
    headers: {
      "Content-Type": "application/json",
    },
    body: JSON.stringify({
      name: groupname,
      description: description,
    }),
  });

  if (!groupResponse.ok) {
    alert("グループ作成に失敗しました");
    return;
  }

  const data = await groupResponse.json();

  const joinResponse = await fetch("/api/groups/join", {
    method: "POST",
    headers: {
      "Content-Type": "application/json",
    },
    body: JSON.stringify({
      group_id: data.id,
      user_id: currentUser.user.id,
      role: role,
    }),
  });

  if (joinResponse.ok) {
    alert("グループを作成しました");
  } else {
    alert("グループメンバー登録に失敗しました");
  }
}


/* ===================================================== */
/* 高さ自動変更 */
/* ===================================================== */
function autoResize(textarea){
  textarea.style.height = "auto";
  let newHeight = textarea.scrollHeight;
  const maxHeight = 300;

  if(newHeight > maxHeight){
    newHeight = maxHeight;
  }
  textarea.style.height = newHeight + 2 + "px";
}

/* ===================================================== */
/* グループ名入力チェック */
/* ===================================================== */
function checkGroupName(){
  const groupName = document.getElementById("groupName").value;
  const button = document.getElementById("createGroupBtn");

  if(groupName.trim() !== ""){
    button.disabled = false;
  } else {
    button.disabled = true;
  }
}

/* ===================================================== */
/* 戻る */
/* ===================================================== */
function goBack(){
  window.location.href = "/group?user_id=" + encodeURIComponent(localStorage.getItem("user_id") || "");
}
</script>

</body>
</html>
