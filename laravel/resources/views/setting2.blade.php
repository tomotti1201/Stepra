<!DOCTYPE html>
<html lang="ja">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>STEPRA 設定画面</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light" style="padding-bottom: 100px;">

<div class="container py-3 py-md-4">
        <img src="{{ asset('image/tit.png') }}" class="mb-3" style="width:200px;">
<div class="row justify-content-center">
    <div class="col-12 col-md-11 col-lg-10">

    <h2 class="text-center fw-bold mb-4 fs-4">設定</h2>

    <div class="border rounded bg-white p-3 p-md-4 mb-5 shadow-sm">

        <div class="d-flex flex-column gap-2">
        <button type="button" class="btn btn-outline-dark text-start py-2.5" onclick="openSetting('username')">👤 ユーザー名</button>
        <button type="button" class="btn btn-outline-dark text-start py-2.5" onclick="openSetting('mail')">✉️ メールアドレス</button>
        <button type="button" class="btn btn-outline-dark text-start py-2.5" onclick="openSetting('password')">🔒 パスワード</button>
        
        <button type="button" class="btn btn-outline-dark text-start py-2.5" onclick="openToggleSetting('通知設定')">🔔 通知設定</button>
        <button type="button" class="btn btn-outline-dark text-start py-2.5" onclick="openToggleSetting('プライバシー設定')">🛡️ プライバシー設定</button>
        <button type="button" class="btn btn-outline-dark text-start py-2.5" onclick="openToggleSetting('アカウント管理')">⚙️ アカウント管理</button>
        <button type="button" class="btn btn-outline-dark text-start py-2.5" onclick="openToggleSetting('テーマ変更')">🎨 テーマ変更</button>
        <button type="button" class="btn btn-outline-dark text-start py-2.5" onclick="openToggleSetting('バックアップ')">💾 バックアップ</button>
        <button type="button" class="btn btn-outline-dark text-start py-2.5" onclick="openToggleSetting('ヘルプ')">❓ ヘルプ</button>
        
        <button type="button" class="btn btn-outline-danger text-start py-2.5" onclick="logout()">🚪 ログアウト</button>
        </div>
    </div>
    </div>
</div>
</div>

<div class="modal fade" id="userModal" tabindex="-1" aria-hidden="true">
<div class="modal-dialog modal-dialog-centered mx-auto p-3" style="max-width: 400px;">
    <div class="modal-content">
    <div class="modal-header border-0 pb-0">
        <h5 class="modal-title fw-bold fs-5 mx-auto ps-4">ユーザー名変更</h5>
        <button type="button" class="btn-close ms-0" data-bs-dismiss="modal" aria-label="Close"></button>
    </div>
    <div class="modal-body p-4">
        <div class="mb-3">
        <label class="form-label small fw-bold text-muted">現在のユーザー名</label>
        <div class="form-control bg-light fw-bold text-secondary" id="currentUserName"></div>
        </div>
        <div class="mb-3">
        <label class="form-label small fw-bold">変更後のユーザー名</label>
        <input type="text" id="newUserName" class="form-control" oninput="checkUserNameInput()">
        </div>
        <div class="mb-4">
        <label class="form-label small fw-bold">ユーザー名確認</label>
        <input type="text" id="confirmUserName" class="form-control" oninput="checkUserNameInput()">
        </div>
        <button type="button" class="btn btn-primary w-100 py-2 fw-bold opacity-50" id="changeUserBtn" onclick="changeUserName()" disabled>変更する</button>
    </div>
    </div>
</div>
</div>

<div class="modal fade" id="mailModal" tabindex="-1" aria-hidden="true">
<div class="modal-dialog modal-dialog-centered mx-auto p-3" style="max-width: 400px;">
    <div class="modal-content">
    <div class="modal-header border-0 pb-0">
        <h5 class="modal-title fw-bold fs-5 mx-auto ps-4">メールアドレス変更</h5>
        <button type="button" class="btn-close ms-0" data-bs-dismiss="modal" aria-label="Close"></button>
    </div>
    <div class="modal-body p-4">
        <div class="mb-3">
        <label class="form-label small fw-bold text-muted">現在のメールアドレス</label>
        <div class="form-control bg-light fw-bold text-secondary" id="currentMail"></div>
        </div>
        <div class="mb-3">
        <label class="form-label small fw-bold">変更後のメールアドレス</label>
        <input type="email" id="newMail" class="form-control" oninput="checkMailInput()">
        </div>
        <div class="mb-4">
        <label class="form-label small fw-bold">メールアドレス確認</label>
        <input type="email" id="confirmMail" class="form-control" oninput="checkMailInput()">
        </div>
        <button type="button" class="btn btn-primary w-100 py-2 fw-bold opacity-50" id="changeMailBtn" onclick="changeMail()" disabled>変更する</button>
    </div>
    </div>
</div>
</div>

<div class="modal fade" id="passwordModal" tabindex="-1" aria-hidden="true">
<div class="modal-dialog modal-dialog-centered mx-auto p-3" style="max-width: 400px;">
    <div class="modal-content">
    <div class="modal-header border-0 pb-0">
        <h5 class="modal-title fw-bold fs-5 mx-auto ps-4">パスワード変更</h5>
        <button type="button" class="btn-close ms-0" data-bs-dismiss="modal" aria-label="Close"></button>
    </div>
    <div class="modal-body p-4">
        <div class="mb-3">
        <label class="form-label small fw-bold text-muted">現在のパスワード</label>
        <div class="form-control bg-light fw-bold text-secondary" id="currentPassword"></div>
        </div>
        <div class="mb-3">
        <label class="form-label small fw-bold">変更後のパスワード</label>
        <input type="password" id="newPassword" class="form-control" oninput="checkPasswordInput()">
        </div>
        <div class="mb-4">
        <label class="form-label small fw-bold">パスワード確認</label>
        <input type="password" id="confirmPassword" class="form-control" oninput="checkPasswordInput()">
        </div>
        <button type="button" class="btn btn-primary w-100 py-2 fw-bold opacity-50" id="changePasswordBtn" onclick="changePassword()" disabled>変更する</button>
    </div>
    </div>
</div>
</div>

<div class="modal fade" id="toggleModal" tabindex="-1" aria-hidden="true">
<div class="modal-dialog modal-dialog-centered mx-auto p-3" style="max-width: 400px;">
    <div class="modal-content">
    <div class="modal-header border-0 pb-0">
        <h5 class="modal-title fw-bold fs-5 mx-auto ps-4" id="toggleModalTitle">設定項目</h5>
        <button type="button" class="btn-close ms-0" data-bs-dismiss="modal" aria-label="Close"></button>
    </div>
    <div class="modal-body p-4 text-center">
        <p class="text-muted small mb-4">この機能は現在制作中ですが、設定の切り替えが可能です。</p>
        
        <div class="form-check form-switch d-inline-block fs-5 mb-4">
        <input class="form-check-input" type="checkbox" role="switch" id="settingSwitch" checked>
        <label class="form-check-label fw-bold" for="settingSwitch" id="switchLabel">有効</label>
        </div>

        <button type="button" class="btn btn-primary w-100 py-2 fw-bold" onclick="saveToggleSetting()">設定を保存</button>
    </div>
    </div>
</div>
</div>

<x-menubar />

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

<script>
/* 保存データ・初期表示 */
async function loadUser(){

    const userId = localStorage.getItem("user_id");

    if(!userId){
        alert("ログインしてください");
        location.href="/login";
        return;
    }

    const response = await fetch(
        `http://localhost:8000/api/user/${userId}`
    );

    const data = await response.json();

    if(data.status==="success"){

        document.getElementById("currentUserName").textContent =
            data.user.name;

        document.getElementById("currentMail").textContent =
            data.user.email;

    }

}

let userPassword = "password123";

document.getElementById("currentPassword").textContent = userPassword;

// モーダルインスタンス初期化
const userModalInstance = new bootstrap.Modal(document.getElementById('userModal'));
const mailModalInstance = new bootstrap.Modal(document.getElementById('mailModal'));
const passwordModalInstance = new bootstrap.Modal(document.getElementById('passwordModal'));
const toggleModalInstance = new bootstrap.Modal(document.getElementById('toggleModal'));

/* スイッチのラベルテキスト変更イベント */
document.getElementById('settingSwitch').addEventListener('change', function() {
document.getElementById('switchLabel').textContent = this.checked ? "有効" : "無効";
});

/* 設定を開く */
function openSetting(type){
if(type === "username") { userModalInstance.show(); }
else if(type === "mail") { mailModalInstance.show(); }
else if(type === "password") { passwordModalInstance.show(); }
}

/* 制作中の切り替えモーダルを開く関数 */
function openToggleSetting(title) {
document.getElementById('toggleModalTitle').textContent = title;
toggleModalInstance.show();
}

/* 制作中設定の保存シミュレーション */
function saveToggleSetting() {
const title = document.getElementById('toggleModalTitle').textContent;
const status = document.getElementById('settingSwitch').checked ? "有効" : "無効";
toggleModalInstance.hide();
alert(`${title}を【${status}】に設定しました`);
}

/* 入力チェック＆変更処理 */
function checkUserNameInput(){
const newName = document.getElementById("newUserName").value;
const confirmName = document.getElementById("confirmUserName").value;
const button = document.getElementById("changeUserBtn");
if(newName !== "" && confirmName !== "" && newName === confirmName){
    button.disabled = false; button.classList.remove("opacity-50");
} else {
    button.disabled = true; button.classList.add("opacity-50");
}
}
function changeUserName(){
userName = document.getElementById("newUserName").value;
document.getElementById("currentUserName").textContent = userName;
userModalInstance.hide();
alert("ユーザー名を変更しました");
}

function checkMailInput(){
const newMail = document.getElementById("newMail").value;
const confirmMail = document.getElementById("confirmMail").value;
const button = document.getElementById("changeMailBtn");
if(newMail !== "" && confirmMail !== "" && newMail === confirmMail){
    button.disabled = false; button.classList.remove("opacity-50");
} else {
    button.disabled = true; button.classList.add("opacity-50");
}
}
function changeMail(){
userMail = document.getElementById("newMail").value;
document.getElementById("currentMail").textContent = userMail;
mailModalInstance.hide();
alert("メールアドレスを変更しました");
}

/* パスワード用 */
function checkPasswordInput(){
const newPassword = document.getElementById("newPassword").value;
const confirmPassword = document.getElementById("confirmPassword").value;
const button = document.getElementById("changePasswordBtn");
if(newPassword !== "" && confirmPassword !== "" && newPassword === confirmPassword){
    button.disabled = false; button.classList.remove("opacity-50");
} else {
    button.disabled = true; button.classList.add("opacity-50");
}
}
function changePassword(){
userPassword = document.getElementById("newPassword").value;
document.getElementById("currentPassword").textContent = userPassword;
passwordModalInstance.hide();
alert("パスワードを変更しました");
}

function logout(){
alert("ログアウトしました");
}
window.onload = loadUser;

</script>

</body>
</html>