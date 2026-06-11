<!DOCTYPE html>
<html lang="ja">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>目標一覧 | STEPRA</title>

  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

  <style>
    body { background-color: #f8f9fa; }

    /* 目標アイテムの枠線と背景 */
    .target-item {
      border: 1px solid #dee2e6;
      border-radius: 8px;
      overflow: hidden;
      background-color: #fff;
    }
    .target-content {
      font-weight: bold;
    }
  </style>
</head>
<body>
    <script>
if (!localStorage.getItem("user_id")) {
    location.href = "/login";
}
</script>

<div class="container-fluid py-4">
  <img src="{{ asset('image/tit.png') }}" class="mb-3" style="width:200px;">
  <div class="row justify-content-center">
    
    <div class="col-12 px-3 px-md-4">

      <div class="card-body">
        
        <h2 class="text-center fw-bold mb-4 fs-4">
          目標一覧<br>
          <span class="fs-6 fw-normal text-muted">作成・編集</span>
        </h2>

        <div id="target-list" class="d-flex flex-column gap-2">
        </div>

        <div class="mt-4">
          <a href='/taskCreate' class="btn btn-success w-100 py-3 fw-bold fs-5 shadow-sm">
            ＋ 新規目標作成
          </a>
        </div>

      </div>

    </div>
  </div>
</div>

<x-menubar />


<div style="height: 100px;"></div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

<script>
  const storageKey = 'goals';

  // 画面に目標一覧を組み立てて表示する関数
  async function renderList() {

    const listContainer =
        document.getElementById('target-list');

    listContainer.innerHTML =
        '<div class="text-center py-3">読み込み中...</div>';

    try {

        const response =
            await fetch('/tasks');

        const data =
            await response.json();

        const userId =
            localStorage.getItem("user_id");

        const goals =
            data.tasks.filter(
                task => task.user_id == userId
            );

        listContainer.innerHTML = '';

        if (goals.length === 0) {

            listContainer.innerHTML =
                '<div class="text-center text-muted small py-4">登録された目標がありません</div>';

            return;
        }

        goals.forEach((goal) => {

            const item =
                document.createElement('div');

            item.className =
                'target-item d-flex row g-0 align-items-center mb-2 shadow-sm';

            const content =
                document.createElement('div');

            content.className =
                'target-content col-8 p-3 text-start';

            content.innerHTML = `
                <div>${goal.title}</div>
                <div class="small text-muted">
                    ${goal.start_time}
                    ${goal.priority ? '｜' + goal.priority : ''}
                </div>
            `;

            if (goal.color) {
                content.style.borderLeft =
                    `8px solid ${goal.color}`;
            }

            const editBtnWrapper =
                document.createElement('div');

            editBtnWrapper.className =
                'col-4 px-3 text-end';

            const editLink =
                document.createElement('a');

            editLink.className =
                'btn btn-primary w-100 py-2 fw-bold';

            editLink.textContent = '編集';

            editLink.href =
                `/taskedit?id=${goal.id}`;

            editBtnWrapper.appendChild(editLink);

            item.appendChild(content);
            item.appendChild(editBtnWrapper);

            listContainer.appendChild(item);
        });

    } catch (error) {

        console.error(error);

        listContainer.innerHTML =
            '<div class="text-center text-danger py-4">読み込みに失敗しました</div>';
    }
}

document.addEventListener(
    'DOMContentLoaded',
    renderList
);

  // 画面が表示されたタイミングに、最新データを読み込んで一覧を表示する
  document.addEventListener('DOMContentLoaded', renderList);
</script>
</body>
</html>