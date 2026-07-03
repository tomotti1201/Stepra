<!DOCTYPE html>
<html lang="ja">
<head>

<meta charset="UTF-8">

<meta name="viewport"
      content="width=device-width, initial-scale=1.0">

<title>STEPRA グループ目標一覧</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
      rel="stylesheet">

</head>

<body class="bg-light">

<div class="container py-4 mb-5">

    <!-- タイトル -->

    <img src="/image/tit.png"
        alt="STEPRA"
        class="mb-3"
        style="width:200px;"
        alt="">

    <!-- タイトル -->

    <div class="card shadow mb-4">

        <div class="card-body">

            <div class="d-flex justify-content-between align-items-center">

                <h5 class="fw-bold mb-0">

                    グループ目標一覧

                </h5>

                     <a href="/gurupumokuhyosinki/{{ $group->id }}"
                   class="btn btn-success">

                    ＋追加

                </a>

            </div>

        </div>

    </div>

    <!-- 目標一覧 -->

    <div id="goalList"></div>

</div>

<!-- 下メニュー -->

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

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

<script>

/* =====================================
   一覧表示
===================================== */

async function renderGoals(){

    const goalList =
        document.getElementById(
            "goalList"
        );

    goalList.innerHTML =
        `
        <div class="card shadow">

            <div class="card-body text-center text-muted">

                読み込み中...

            </div>

        </div>
        `;

    try{

        const response =
            await fetch(
                "/api/grouptasks?group_id={{ $group->id }}",
                {
                    headers: {
                        "Accept": "application/json"
                    }
                }
            );

        const data =
            await response
                .json()
                .catch(()=>({}));

        if(!response.ok){

            goalList.innerHTML =
                `
                <div class="card shadow">

                    <div class="card-body text-center text-danger">

                        グループタスクの取得に失敗しました

                    </div>

                </div>
                `;

            return;
        }

        const goals =
            Array.isArray(data)
                ? data
                : data.tasks || [];

        goalList.innerHTML = "";

        /* データなし */

        if(goals.length === 0){

            goalList.innerHTML =

            `
            <div class="card shadow">

                <div class="card-body text-center text-muted">

                    登録されたグループ目標がありません

                </div>

            </div>
            `;

            return;
        }

        /* 一覧生成 */

        goals.forEach(goal=>{

            goalList.innerHTML +=

            `
            <div class="card shadow mb-3">

                <div class="card-body">

                    <div class="row align-items-center">

                        <!-- 目標名 -->

                        <div class="col-8">

                            <div
                                class="fw-bold p-2 rounded"
                                style="
                                border-left:8px solid ${goal.color || '#198754'};
                                ">

                                ${escapeHtml(goal.title)}

                                <div class="small text-muted fw-normal mt-1">
                                    ${formatTaskInfo(goal)}
                                </div>

                            </div>

                        </div>

                        <!-- ボタン -->

                        <div class="col-4">
                        
                            <button
                                class="btn btn-primary w-100 mb-2"
                                onclick="editTask(${goal.id})">

                                編集

                            </button>

                            <button
                                class="btn btn-danger w-100"
                                onclick="deleteGoal(${goal.id})">

                                削除

                            </button>

                        </div>

                    </div>

                </div>

            </div>
            `;

        });

    }catch(error){

        console.error(error);

        goalList.innerHTML =
            `
            <div class="card shadow">

                <div class="card-body text-center text-danger">

                    グループタスクの取得に失敗しました

                </div>

            </div>
            `;
    }

}

function editTask(id){

    location.href =
        `/gurutaskukuhen/{{ $group->id }}?task_id=${id}`;
}

/* =====================================
   削除
===================================== */

async function deleteGoal(id){

    if(!confirm("この目標を削除しますか？")){
        return;
    }

    try{

        const response =
            await fetch(
                `/api/grouptasks/${id}`,
                {
                    method: "DELETE",
                    headers: {
                        "Accept": "application/json"
                    }
                }
            );

        if(!response.ok){

            alert(
                "削除に失敗しました"
            );

            return;
        }

        renderGoals();

    }catch(error){

        console.error(error);

        alert(
            "削除に失敗しました"
        );
    }
}

function formatTaskInfo(goal){

    const infos = [];

    if(goal.start_time){
        infos.push(
            goal.start_time.slice(0, 5)
        );
    }

    if(goal.required_minutes){
        infos.push(
            `${goal.required_minutes}分`
        );
    }

    if(goal.priority){
        infos.push(
            goal.priority
        );
    }

    return infos.length
        ? escapeHtml(infos.join(" / "))
        : "";
}

function escapeHtml(value){

    return String(value ?? "")
        .replaceAll("&", "&amp;")
        .replaceAll("<", "&lt;")
        .replaceAll(">", "&gt;")
        .replaceAll('"', "&quot;")
        .replaceAll("'", "&#039;");
}

/* =====================================
   下メニュー
===================================== */

const menuButtons =
    document.querySelectorAll(
        ".navbar button"
    );

menuButtons.forEach(button=>{

    button.addEventListener(

        "click",

        ()=>{

            menuButtons.forEach(btn=>{

                btn.classList.remove(
                    "btn-success"
                );

                btn.classList.add(
                    "btn-outline-secondary"
                );

            });

            button.classList.remove(
                "btn-outline-secondary"
            );

            button.classList.add(
                "btn-success"
            );

        }

    );

});

/* =====================================
   初期表示
===================================== */

renderGoals();

</script>

</body>
</html>
