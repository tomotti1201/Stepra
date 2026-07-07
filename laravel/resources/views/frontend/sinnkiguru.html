<!DOCTYPE html>
<html lang="ja">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>STEPRA 新規グループ作成</title>

  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<div class="container-fluid min-vh-100 d-flex flex-column justify-content-between p-0">
  
  <div class="p-4 flex-grow-1 mb-5">
    
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

    <button class="btn btn-success w-100 mb-3 py-3 fw-bold fs-5" 
            id="createGroupBtn" 
            onclick="createGroup()" 
            disabled>
      グループ作成
    </button>

    <button class="btn btn-secondary w-100 py-3 fw-bold fs-5" 
            onclick="goBack()">
      戻る
    </button>

  </div>

  <nav class="navbar bg-white border-top fixed-bottom">
    <div class="container d-flex justify-content-around">
      <button class="btn btn-outline-secondary" onclick="location.href='/home'">
        🏠 ホーム
      </button>
      <button class="btn btn-outline-secondary" onclick="location.href='/mokuhyouitiran'">
        🎯 目標
      </button>
      <button class="btn btn-outline-secondary" onclick="location.href='/gekkankarenda'">
        📅 月間カレンダー
      </button>
      <button class="btn btn-success" onclick="location.href='/gurupu'">
        👥 グループ
      </button>
      <button class="btn btn-outline-secondary" onclick="location.href='/setting'">
        ⚙️ 設定・継続率
      </button>
    </div>
  </nav>

</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

<script>
/* ===================================================== */
/* グループ作成 */
/* ===================================================== */
async function createGroup(){
  const description = document.getElementById("groupDesc").value;
  const groupname = document.getElementById("groupName").value;
  const response = await fetch("/api/groups/",{
    method:"POST",
    headers:{
      "Content-Type":"application/json",
    },
    body:JSON.stringify({
      name:groupname,
      description:description
    })
  });

  if(response.ok){
    const data = await response.json();
    alert("グループを作成しました");
  }
  else{
    alert("グループ作成に失敗しました");
    return
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
  window.location.href = "/gurupu";
}
</script>

</body>
</html>
