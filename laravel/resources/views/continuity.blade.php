<!DOCTYPE html>
<html lang="ja">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>STEPRA ユーザー情報</title>

  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

  <style>
    body { background-color: #f8f9fa; }

    /* アイコンサークルのスタイル */
    .user-icon-circle {
      width: 80px;
      height: 80px;
      border-radius: 50%;
      background-color: #7fa7d8;
      border: 2px solid #333;
      overflow: hidden;
      display: flex;
      justify-content: center;
      align-items: center;
      cursor: pointer;
      padding: 0;
    }
    .user-icon-circle img {
      width: 100%;
      height: 100%;
      object-fit: cover;
      display: none;
    }

    /* フッターナビゲーションのスタイル（画面下部固定） */
    .bottom-nav {
      border-top: 1px solid #dee2e6;
      background-color: #fff;
      padding: 10px 0;
    }
    .menu-item {
      color: #6c757d;
      text-decoration: none;
      font-size: 12px;
    }
    .menu-item.active {
      color: #0d6efd;
      font-weight: bold;
    }
    .status-card {
        min-height: 150px; 
    }
  </style>
</head>
<body>

<div class="container py-4">
    <img src="{{ asset('image/tit.png') }}" class="mb-3" style="width:200px;">
  <div class="row justify-content-center">
    <div class="col-12 col-md-8 col-lg-5">

      <div class="card shadow border-0 mb-5">
        <div class="card-body p-4">
          
          <h2 class="text-center fw-bold mb-4 fs-4">ユーザー情報</h2>

          <div class="d-flex align-items-center gap-3 mb-4">
            <button type="button" class="user-icon-circle shadow-sm" disabled>
              <img id="iconImage" src="" alt="ユーザーアイコン">
              <span id="iconText" class="small fw-bold text-white"></span>
            </button>
            <input type="file" id="imageInput" accept="image/*" style="display: none;" onchange="changeIcon(event)">

            <div id="userName" class="form-control d-flex align-items-center justify-content-center fw-bold bg-light" style="height: 80px; font-size: 1.1rem;">
              ユーザー名
            </div>
          </div>

          <div class="border rounded bg-white p-3 mb-4 shadow-sm">
            <h3 class="text-center fw-bold fs-5 mb-2 text-secondary">現在の継続率</h3>
            
            <div class="text-center py-2">
                <div id="rate" class="display-1 fw-bold text-success">
                    0<span class="fs-4">%</span>
                </div>

                <p class="text-muted small mb-0">仮</p>
            </div>

            <div class="bg-light rounded p-3 mb-3">
              <div class="row g-3 mt-2">

                  <div class="col-4">
<div class="border rounded text-center p-2 bg-light status-card d-flex flex-column justify-content-center">                          <div class="fs-3">🔥</div>
                          <div id="streak" class="fw-bold">0日</div>
                          <small>連続達成</small>
                      </div>
                  </div>

                  <div class="col-4">
<div class="border rounded text-center p-2 bg-light status-card d-flex flex-column justify-content-center">                          <div class="fs-3">✔</div>
                          <div id="month" class="fw-bold">0/0</div>
                          <small>今月</small>
                      </div>
                  </div>

                  <div class="col-4">
<div class="border rounded text-center p-2 bg-light h-100 d-flex flex-column justify-content-center align-items-center">                          <div class="text-center">
                              <img id="medal"
                                  src="{{ asset('image/bronze-medal.png') }}"
                                  width="85"
                                  class="mb-2"
                                  alt="ランク">
                          </div>
                          <small>ランク</small>
                      </div>
                  </div>

              </div>
</div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<x-menubar />

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

<script>
  document.addEventListener("DOMContentLoaded", loadContinuity);

async function loadContinuity() {

    const userId = localStorage.getItem("user_id");

    const res = await fetch(`/api/continuity?user_id=${userId}`);

    const data = await res.json();

    document.getElementById("userName").textContent =
        data.name || "ユーザー名";
    renderUserIcon(data.icon, data.name);

    document.getElementById("rate").innerHTML =
        `${Number(data.rate).toFixed(1)}<span class="fs-4">%</span>`;

    document.getElementById("streak").textContent =
        `${data.streak}日`;

    document.getElementById("month").textContent =
        `${data.month_completed}/${data.month_total}`;

    document.getElementById("medal").src =
        `/image/${data.medal || "bronze-medal.png"}`;
}

function renderUserIcon(icon, name) {
    const iconImage = document.getElementById("iconImage");
    const iconText = document.getElementById("iconText");
    const value = String(icon || "").trim();

    iconImage.style.display = "none";
    iconImage.removeAttribute("src");
    iconText.textContent = "";

    if (!value) {
        iconText.textContent = String(name || "U").trim().slice(0, 1);
        return;
    }

    const isImagePath =
        value.startsWith("/") ||
        value.startsWith("http://") ||
        value.startsWith("https://") ||
        /\.(png|jpe?g|gif|webp|svg)$/i.test(value);

    if (isImagePath) {
        iconImage.src = value;
        iconImage.style.display = "block";
        iconImage.onerror = () => {
            iconImage.style.display = "none";
            iconText.textContent = String(name || "U").trim().slice(0, 1);
        };
        return;
    }

    iconText.textContent = value;
}
</script>

</body>
</html>
