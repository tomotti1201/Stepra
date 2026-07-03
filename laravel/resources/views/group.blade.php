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
  <img src="/image/tit.png" alt="STEPRA" class="mb-3" style="width:200px;">

  <div class="row justify-content-center">
    <div class="col-12 col-md-8 col-lg-5">

      <div class="card shadow border-0 mb-4">
        <div class="card-body p-4">

          <h2 class="text-center fw-bold mb-4 fs-4">
            グループ
          </h2>

          <div class="row g-2 mb-4">
            <div class="col-6">
              <button class="btn btn-success w-100 py-3 fw-bold small" onclick="goCreateGroup()">
                新規<br>グループ作成
              </button>
            </div>
            <div class="col-6">
              <button class="btn btn-outline-primary w-100 py-3 fw-bold small" data-bs-toggle="modal" data-bs-target="#joinModal">
                グループに<br>入る
              </button>
            </div>
          </div>

          <div class="mb-2">
            <p class="fw-bold mb-2 text-muted small">グループ一覧</p>

            <div class="d-flex flex-column gap-2" style="max-height: 350px; overflow-y: auto;">
              @forelse ($groups as $group)
                <button class="btn btn-light border text-start p-3 fw-bold" onclick="openGroup({{ $group->id }})">
                  {{ $group->name }}
                </button>
              @empty
                <p class="text-muted small mb-0">まだグループがありません</p>
              @endforelse
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
        <button type="button" class="btn-close" data-bs-shadow="none" data-bs-dismiss="modal" aria-label="Close" title="Close" onclick="resetModal()"></button>
      </div>

      <div class="modal-body p-4">
        <div class="mb-3">
          <input type="text" id="groupCode" class="form-control py-2" placeholder="グループコードを入力してください" aria-label="Group code" title="Group code">
        </div>

        <button type="button" class="btn btn-success w-100 py-2 fw-bold mb-3" onclick="searchGroup()">
          グループを探す
        </button>

        <div id="searchResult" class="card bg-light border p-3 text-center" style="display: none;"></div>
      </div>

    </div>
  </div>
</div>

<nav class="navbar bg-white border-top fixed-bottom">
  <div class="container d-flex justify-content-around">
    <button class="btn btn-outline-secondary" onclick="location.href='/home'">
      ホーム
    </button>
    <button class="btn btn-outline-secondary" onclick="location.href='/mokuhyouitiran'">
      目標
    </button>
    <button class="btn btn-outline-secondary" onclick="location.href='/gekkankarenda'">
      カレンダー
    </button>
    <button class="btn btn-success" onclick="location.href='/gurupu'">
      グループ
    </button>
    <button class="btn btn-outline-secondary" onclick="location.href='/setting'">
      設定
    </button>
  </div>
</nav>

<div style="height: 80px;"></div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

<script>
  function goCreateGroup() {
    window.location.href = "/sinnkiguru";
  }

  function openGroup(id) {
    window.location.href = `/gurupusyu/${id}`;
  }

  function searchGroup() {
    const code = document.getElementById("groupCode").value.trim();
    const resultDiv = document.getElementById("searchResult");

    if (!code) {
      alert("コードを入力してください");
      return;
    }

    resultDiv.style.display = "block";
    resultDiv.innerHTML = `
      <p class="fw-bold text-danger mb-2">グループ検索はまだ実装中です</p>
      <button class="btn btn-sm btn-secondary w-100 fw-bold" onclick="searchAgain()">別のコードで探す</button>
    `;
  }

  function searchAgain() {
    document.getElementById("groupCode").value = "";
    const resultDiv = document.getElementById("searchResult");
    resultDiv.style.display = "none";
    resultDiv.innerHTML = "";
  }

  function resetModal() {
    document.getElementById("groupCode").value = "";
    const resultDiv = document.getElementById("searchResult");
    resultDiv.style.display = "none";
    resultDiv.innerHTML = "";
  }
</script>

</body>
</html>

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