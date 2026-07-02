<!DOCTYPE html>
<html lang="ja">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>月間カレンダー | STEPRA</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<div class="container-fluid py-4">
  <img src="im/tit.png" class="mb-3" style="width:200px;" alt="">
  <div class="row justify-content-center">
    
    <div class="col-12 px-3 px-md-4">
      
      <div class="card-body">
        
        <div class="text-center fw-bold mb-4 fs-4">月間カレンダー</div>

        <div class="d-flex justify-content-between align-items-center mb-4">
          <button class="btn btn-secondary px-4 py-2" onclick="changeMonth(-1)">&lt;</button>
          <span id="current-month-display" class="fw-bold fs-3">2024年 1月</span>
          <button class="btn btn-secondary px-4 py-2" onclick="changeMonth(1)">&gt;</button>
        </div>

        <div class="week d-grid text-center fw-bold border-bottom pb-3 mb-3 fs-5" style="grid-template-columns: repeat(7, 1fr);">
          <div class="text-danger">日</div><div>月</div><div>火</div><div>水</div><div>木</div><div>金</div><div class="text-primary">土</div>
        </div>
        
        <div id="calendar-days" class="d-grid gap-3 text-center fs-5" style="grid-template-columns: repeat(7, 1fr); align-items: center;">
        </div>

      </div>

    </div>
  </div>
</div>

<script src="js/main.js"></script>
<script>
// 現在表示している日付の状態
const now = new Date();
let currentViewDate = new Date(now.getFullYear(), now.getMonth(), 1);

/**
 * カレンダーを描画する
 */
function renderCalendar() {
  const year = currentViewDate.getFullYear();
  const month = currentViewDate.getMonth();
  
  // 1. 月表示の更新
  const display = document.getElementById('current-month-display');
  if (display) display.innerText = `${year}年 ${month + 1}月`;

  // 2. 描画エリアの取得
  const daysContainer = document.getElementById('calendar-days');
  if (!daysContainer) {
    console.error("ID: calendar-days が見つかりません！");
    return;
  }
  daysContainer.innerHTML = ''; // 一旦クリア

  // 3. 日付計算
  const firstDayOfWeek = new Date(year, month, 1).getDay();
  const lastDate = new Date(year, month + 1, 0).getDate();

  // 4. 余白（前の月分）の追加
  for (let i = 0; i < firstDayOfWeek; i++) {
    const emptyDay = document.createElement('div');
    emptyDay.className = 'day py-3'; 
    daysContainer.appendChild(emptyDay);
  }

  // 5. 日付の生成（ボタンとして生成）
  for (let date = 1; date <= lastDate; date++) {
    const dayEl = document.createElement('button');
    dayEl.type = 'button';
    
    // Bootstrapのボタンクラス
    dayEl.className = 'btn btn-light bg-white border rounded-3 py-3 shadow-sm w-100 h-100'; 
    dayEl.innerText = date; 

    // 各日付のクリックイベントを設定
    dayEl.setAttribute('onclick', `clickDate(${date})`);

    // 土日の個別色付け
    const dayOfWeek = new Date(year, month, date).getDay();
    if (dayOfWeek === 0) {
      dayEl.className = 'btn btn-danger-subtle border border-danger-subtle text-danger fw-bold rounded-3 py-3 shadow-sm w-100 h-100';
    } else if (dayOfWeek === 6) {
      dayEl.className = 'btn btn-primary-subtle border border-primary-subtle text-primary fw-bold rounded-3 py-3 shadow-sm w-100 h-100';
    }

    daysContainer.appendChild(dayEl);
  }
}

function changeMonth(offset) {
  currentViewDate.setMonth(currentViewDate.getMonth() + offset);
  renderCalendar();
}

/**
 * 【変更】日付がクリックされた時に画面遷移する処理
 */
function clickDate(date) {
  const year = currentViewDate.getFullYear();
  const month = currentViewDate.getMonth() + 1;

  // 例: 日付を押したら「/kiroku」に遷移する（URLの後ろに選んだ年月日をくっつけて送る設定）
  // パラメータが不要でただページを飛ばしたい場合は window.location.href = "/kiroku"; だけでも動きます
  window.location.href = `/kiroku?year=${year}&month=${month}&day=${date}`;
}

// 読み込み時に実行
document.addEventListener('DOMContentLoaded', renderCalendar);
</script>

<nav class="navbar navbar-light bg-light border-top fixed-bottom py-2">
  <div class="container-fluid justify-content-around">
    <a href="/kiroku" class="text-decoration-none text-secondary text-center" style="font-size: 12px;">
      <div>📅</div><div>記録</div>
    </a>
    <a href="/rireki" class="text-decoration-none text-secondary text-center" style="font-size: 12px;">
      <div>🔄</div><div>履歴</div>
    </a>
    <a href="/gekkankarenda" class="text-decoration-none text-primary fw-bold text-center" style="font-size: 12px;">
      <div>📊</div><div>分析</div>
    </a>
    <a href="/mokuhyouitiran" class="text-decoration-none text-secondary text-center" style="font-size: 12px;">
      <div>✅</div><div>目標</div>
    </a>
  </div>
</nav>

<div style="height: 100px;"></div>

</body>
</html>