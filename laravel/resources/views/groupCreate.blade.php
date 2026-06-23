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

    <button class="btn btn-success w-100 mb-3 py-3 fw-bold fs-5" 
            id="createGroupBtn" 
            onclick="createGroup()" 
            disabled>
      グループ作成
    </button>

    <button class="btn btn-secondary w-100 py-3 fw-bold fs-5" 
            onclick="location.href='/group'">
      戻る
    </button>

  </div>

  <nav class="navbar navbar-light bg-light border-top fixed-bottom py-2">
    <div class="container-fluid justify-content-around">
      <a href="kiroku.html" class="text-decoration-none text-secondary text-center" style="font-size: 12px;">
        <div>📅</div><div>記録</div>
      </a>
      <a href="rireki.html" class="text-decoration-none text-secondary text-center" style="font-size: 12px;">
        <div>🔄</div><div>履歴</div>
      </a>
      <a href="gekkankarenda.html" class="text-decoration-none text-secondary text-center" style="font-size: 12px;">
        <div>📊</div><div>分析</div>
      </a>
      <a href="mokuhyouitiran.html" class="text-decoration-none text-secondary text-center" style="font-size: 12px;">
        <div>✅</div><div>目標</div>
      </a>
      <a href="guru-pu.html" class="text-decoration-none text-primary fw-bold text-center" style="font-size: 12px;">
        <div>◉</div><div>グループ</div>
      </a>
    </div>
  </nav>

</div>

<x-menubar />

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

<script>
/* ===================================================== */
/* グループ作成 */
/* ===================================================== */
function createGroup(){
  alert("グループを作成しました");
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
  window.location.href = "gurupu.html";
}
</script>

</body>
</html>