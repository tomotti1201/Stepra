<!DOCTYPE html>
<html lang="ja">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>グループ一覧 | STEPRA</title>

  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    body { background-color: #f8f9fa; }
  </style>
</head>
<body>

<div class="container-fluid py-4">
        <img src="{{ asset('image/tit.png') }}" class="mb-3" style="width:200px;">
  
  <div class="row justify-content-center">
    <div class="col-12 col-md-8 col-lg-5">

      <div class="card shadow border-0 mb-4">
        <div class="card-body p-4">
          
          <h2 class="text-center fw-bold mb-4 fs-4">
            グループ
          </h2>

          <div class="row g-2 mb-4">
            <div class="col-6">
              <button class="btn btn-success w-100 py-3 fw-bold small" onclick="location.href='/groupCreate'">
                新規グループ作成
              </button>
            </div>
            <div class="col-6">
              <button class="btn btn-outline-primary w-100 py-3 fw-bold small" data-bs-toggle="modal" data-bs-target="#joinModal">
                グループに入る
              </button>
            </div>
          </div>

          <div class="mb-2">
            <p class="fw-bold mb-2 text-muted small">グループ一覧</p>
            
            <div class="d-flex flex-column gap-2" style="max-height: 350px; overflow-y: auto;">
              <button class="btn btn-light border text-start p-3 fw-bold" onclick="openGroup(1)">
                グループ1
              </button>
              <button class="btn btn-light border text-start p-3 fw-bold" onclick="openGroup(2)">
                グループ2
              </button>
              <button class="btn btn-light border text-start p-3 fw-bold" onclick="openGroup(3)">
                グループ3
              </button>
              <button class="btn btn-light border text-start p-3 fw-bold" onclick="openGroup(4)">
                グループ4
              </button>
              <button class="btn btn-light border text-start p-3 fw-bold" onclick="openGroup(5)">
                グループ5
              </button>
            </div>
          </div>

        </div>
      </div>

    </div>
  </div>
</div>

<div class="modal fade" id="joinModal" tabindex="-1" aria-labelledby="joinModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered px-3">
    <div class="modal-content shadow border-0">
      
      <div class="modal-header border-0 pb-0">
        <h5 class="modal-title fw-bold" id="joinModalLabel">グループを探す</h5>
        <button type="button" class="btn-close" data-bs-shadow="none" data-bs-dismiss="modal" aria-label="Close" onclick="resetModal()"></button>
      </div>
      
      <div class="modal-body p-4">
        <div class="mb-3">
          <input type="text" id="groupCode" class="form-control py-2" placeholder="グループコードを入力してください">
        </div>
        
        <button type="button" class="btn btn-success w-100 py-2 fw-bold mb-3" onclick="searchGroup()">
          グループを探す
        </button>

        <div id="searchResult" class="card bg-light border p-3 text-center" style="display: none;">
        </div>
      </div>

    </div>
  </div>
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

<div style="height: 80px;"></div>

<x-menubar />

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

<script>
  /* ===================================================== */
  /* 新規グループ作成画面へ遷移 */
  /* ===================================================== */
  function goCreateGroup() {
    window.location.href = "sinnkiguru.html";
  }

  /* ===================================================== */
  /* グループを開く */
  /* ===================================================== */
  function openGroup(number) {
    alert("グループ" + number + " を開きます");
  }

  /* ===================================================== */
  /* グループ検索（モーダル内部） */
  /* ===================================================== */
  function searchGroup() {
    const code = document.getElementById("groupCode").value.trim();
    const resultDiv = document.getElementById("searchResult");

    if (!code) {
      alert("コードを入力してください");
      return;
    }

    resultDiv.style.display = "block";

    if (code === "12345") {
      // グループが見つかった場合
      resultDiv.innerHTML = `
        <p class="fw-bold text-success mb-1">検索結果</p>
        <p class="mb-3 small">グループ名：<strong>STEPRA勉強会</strong></p>
        <div class="d-flex gap-2">
          <button class="btn btn-sm btn-secondary w-50 fw-bold" onclick="searchAgain()">別検索</button>
          <button class="btn btn-sm btn-primary w-50 fw-bold" onclick="enterGroup()">参加する</button>
        </div>
      `;
    } else {
      // グループが見つからなかった場合（戻る/別検索はbtn-secondary）
      resultDiv.innerHTML = `
        <p class="fw-bold text-danger mb-2">グループが見つかりませんでした</p>
        <button class="btn btn-sm btn-secondary w-100 fw-bold" onclick="searchAgain()">他のコードで探す</button>
      `;
    }
  }

  /* ===================================================== */
  /* 検索のやり直し（クリア処理） */
  /* ===================================================== */
  function searchAgain() {
    document.getElementById("groupCode").value = "";
    const resultDiv = document.getElementById("searchResult");
    resultDiv.style.display = "none";
    resultDiv.innerHTML = "";
  }

  /* ===================================================== */
  /* グループへ参加確定 */
  /* ===================================================== */
  function enterGroup() {
    alert("グループに参加しました");
    
    // BootstrapのAPIを使ってモーダルを閉じる
    const modalElement = document.getElementById('joinModal');
    const modalInstance = bootstrap.Modal.getInstance(modalElement);
    if (modalInstance) {
      modalInstance.hide();
    }
    resetModal();
  }

  /* ===================================================== */
  /* モーダルが閉じられた際のリセット処理 */
  /* ===================================================== */
  function resetModal() {
    document.getElementById("groupCode").value = "";
    const resultDiv = document.getElementById("searchResult");
    resultDiv.style.display = "none";
    resultDiv.innerHTML = "";
  }
</script>

</body>
</html>