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
            <button type="button" class="user-icon-circle shadow-sm" onclick="selectImage()">
              <img id="iconImage" src="" alt="ユーザーアイコン">
              <span id="iconText" class="small fw-bold text-white">画像選択</span>
            </button>
            <input type="file" id="imageInput" accept="image/*" style="display: none;" onchange="changeIcon(event)">

            <div class="form-control d-flex align-items-center justify-content-center fw-bold bg-light" style="height: 80px; font-size: 1.1rem;">
              ユーザー名
            </div>
          </div>

          <div class="border rounded bg-white p-3 mb-4 shadow-sm">
            <h3 class="text-center fw-bold fs-5 mb-2 text-secondary">現在のステータス</h3>
            
            <div class="text-center py-2">
              <div class="display-1 fw-bold text-success">85<span class="fs-4">%</span></div>
              <p class="text-muted small mb-0">現在の継続率</p>
            </div>

            <div class="bg-light rounded p-3 mb-3">
              <div class="d-flex align-items-center justify-content-center" style="height: 180px;">
                <p class="text-secondary mb-0">📊 グラフ描画エリア</p>
              </div>
            </div>

            <div class="p-2 bg-light rounded text-center">
              <p class="mb-0 small fw-bold text-secondary">この調子で頑張りましょう！</p>
            </div>
          </div>
          <div class="mb-2">
            <button type="button" class="btn btn-primary w-100 py-3 shadow-sm" onclick="location.href='/logout'">
              <div class="fw-bold fs-5">設定</div>
              <div class="small opacity-75">ユーザー情報の変更・削除など</div>
            </button>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<x-menubar />


<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

<script>
  /* ===================================================== */
  /* 画像選択を開く */
  /* ===================================================== */
  function selectImage(){
    document.getElementById("imageInput").click();
  }

  /* ===================================================== */
  /* アイコン変更 */
  /* ===================================================== */
  function changeIcon(event){
    const file = event.target.files[0];
    if(!file){
      return;
    }

    const reader = new FileReader();
    reader.onload = function(e){
      const image = document.getElementById("iconImage");
      const text = document.getElementById("iconText");

      image.src = e.target.result;
      image.style.display = "block";
      text.style.display = "none";
    };

    reader.readAsDataURL(file);
  }

  /* =======================================
     設定画面へ
  ======================================= */
  function goSetting(){
    window.location.href = "settei.html";
  }
</script>

</body>
</html>