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
          <h2 class="text-center fw-bold mb-4 fs-4">グループ</h2>

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
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" title="Close" onclick="resetModal()"></button>
      </div>

      <div class="modal-body p-4">
        <div class="mb-3">
          <input type="text" id="groupCode" class="form-control py-2" placeholder="招待コードを入力してください" aria-label="Group code" title="Group code">
        </div>

        <button type="button" class="btn btn-success w-100 py-2 fw-bold mb-3" onclick="searchGroup()">
          グループに参加する
        </button>

        <div id="searchResult" class="card bg-light border p-3 text-center" style="display: none;"></div>
      </div>
    </div>
  </div>
</div>
<x-menubar />
<div style="height: 80px;"></div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

<script>
  function goCreateGroup() {
    window.location.href = "/sinnkiguru";
  }

  function openGroup(id) {
    window.location.href = `/gurupusyu/${id}`;
  }

  async function searchGroup() {
    const code = document.getElementById("groupCode").value.trim();
    const resultDiv = document.getElementById("searchResult");
    const userId = localStorage.getItem("user_id");

    if (!code) {
      alert("招待コードを入力してください");
      return;
    }

    if (!userId) {
      alert("ログイン情報がありません。もう一度ログインしてください");
      return;
    }

    resultDiv.style.display = "block";
    resultDiv.innerHTML = '<p class="text-muted mb-0">参加処理中...</p>';

    try {
      const response = await fetch("/api/groupmembers/join-by-invite", {
        method: "POST",
        headers: {
          "Content-Type": "application/json",
        },
        body: JSON.stringify({
          invite_code: code,
          user_id: userId,
        }),
      });

      const data = await response.json();

      if (!response.ok) {
        resultDiv.innerHTML = `
          <p class="fw-bold text-danger mb-2">${data.message || "グループに参加できませんでした"}</p>
          <button class="btn btn-sm btn-secondary w-100 fw-bold" onclick="searchAgain()">別のコードで探す</button>
        `;
        return;
      }

      resultDiv.innerHTML = `
        <p class="fw-bold text-success mb-2">${data.already_joined ? "すでに参加済みです" : "グループに参加しました"}</p>
        <p class="mb-3">${data.group.name}</p>
        <button class="btn btn-sm btn-success w-100 fw-bold" onclick="location.href='/group?user_id=' + encodeURIComponent(localStorage.getItem('user_id') || '')">グループ一覧へ戻る</button>
      `;
    } catch (error) {
      console.error(error);
      resultDiv.innerHTML = `
        <p class="fw-bold text-danger mb-2">通信エラーが発生しました</p>
        <button class="btn btn-sm btn-secondary w-100 fw-bold" onclick="searchAgain()">もう一度試す</button>
      `;
    }
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
