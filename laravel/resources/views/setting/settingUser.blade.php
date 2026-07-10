<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>STEPRA ユーザー情報</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="bg-light">

<script>
    if (!localStorage.getItem("user_id")) {
        location.href = "/login";
    }
</script>

<div class="container py-4 mb-5">
    <img src="{{ asset('image/tit.png') }}" class="mb-4" style="width:200px;">

    <div class="row">
        <div class="col-md-3 mb-3">
            @include('components.settingmenubar')
        </div>
        <div class="col-md-9">
            <div class="card shadow">
                <div class="card-body">
                    <h4 class="fw-bold mb-4">
                        ユーザー情報
                    </h4>

                    <div class="mb-4">

                        <label class="form-label fw-bold">
                            ユーザー名
                        </label>

                        <div class="input-group">

                            <input type="text" id="currentUserName" class="form-control" readonly>

                            <button
                                id="userNameBtn"
                                class="btn btn-success rounded-end"
                                onclick="changeUserName()"
                            >
                                変更
                            </button>

                            <button
                                id="cancelUserNameBtn"
                                class="btn btn-danger d-none"
                                onclick="cancelUserName()"
                            >
                                キャンセル
                            </button>
                        </div>
                    </div>
                    <div class="mb-4">

                        <label class="form-label fw-bold">
                            メールアドレス
                        </label>

                        <div class="input-group">

                            <input
                                type="email"
                                id="currentMail"
                                class="form-control"
                                readonly
                            >

                            <button
                                id="mailBtn"
                                class="btn btn-success rounded-end"
                                onclick="changeMail()"
                            >
                                変更
                            </button>

                            <button
                                id="cancelMailBtn"
                                class="btn btn-danger d-none"
                                onclick="cancelMail()"
                            >
                                キャンセル
                            </button>
                        </div>
                    </div>
                    <div class="mb-4">

                        <label class="form-label fw-bold">
                            パスワード
                        </label>

                        <div class="input-group">

                            <input
                                type="password"
                                id="currentPassword"
                                class="form-control"
                                value="********"
                                readonly
                            >

                            <button
                                id="passwordViewBtn"
                                class="btn btn-success"
                                onclick="togglePassword()"
                            >
                                表示
                            </button>
                        </div>
                        <button
                            class="btn btn-link p-0 mt-2"
                            onclick="location.href='/passwordReset'"
                        >
                            パスワードを忘れた場合
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<x-menubar />

<script>

document.addEventListener(
    "DOMContentLoaded",
    loadUser
);

async function loadUser(){

    const userId = localStorage.getItem("user_id");

    const response = await fetch(
            `http://localhost:8000/api/user/${userId}`
        );

    const data = await response.json();

    if(data.status === "success"){

        document.getElementById(
            "currentUserName"
        ).value =
            data.user.name;

        document.getElementById(
            "currentMail"
        ).value =
            data.user.email;

    }

}

let editingUserName = false;
let oldUserName = "";

async function changeUserName(){

    const input = document.getElementById("currentUserName");
    const button = document.getElementById("userNameBtn");

    // 編集開始
    if (!editingUserName) {

        oldUserName = input.value;

        input.readOnly = false;
        input.focus();

        button.textContent = "保存";
        button.classList.remove("btn-success");
        button.classList.remove("rounded-end");
        button.classList.add("btn-primary");

        document.getElementById("cancelUserNameBtn")
            .classList.remove("d-none");

        editingUserName = true;
        return;
    }

    // 保存
    const userId = localStorage.getItem("user_id");
    const response = await fetch(
        `/api/user/${userId}/name`,
        {
            method:"POST",
            headers:{
                "Content-Type":"application/json"
            },
            body:JSON.stringify({
                name:input.value
            })
        }
    );

    const data = await response.json();

    alert(data.message);

    if (response.ok) {

        input.readOnly = true;

        button.textContent = "変更";
        button.classList.remove("btn-primary");
        button.classList.add("btn-success");
        button.classList.add("rounded-end");

        document.getElementById("cancelUserNameBtn")
            .classList.add("d-none");

        editingUserName = false;
    }
}
function cancelUserName() {

        const input = document.getElementById("currentUserName");
        const button = document.getElementById("userNameBtn");
        const cancel = document.getElementById("cancelUserNameBtn");

        input.value = oldUserName;
        input.readOnly = true;

        button.textContent = "変更";
        button.classList.remove("btn-primary");
        button.classList.add("btn-success");
        button.classList.add("rounded-end");

        cancel.classList.add("d-none");

        editingUserName = false;
}

let editingMail = false;
let oldMail = "";

async function changeMail(){

    const input = document.getElementById("currentMail");
    const button = document.getElementById("mailBtn");

    if(!editingMail){

        oldMail = input.value;

        input.readOnly = false;

        input.focus();

        button.textContent = "保存";

        button.classList.remove("btn-success");
        button.classList.remove("rounded-end");
        button.classList.add("btn-primary");

        document.getElementById("cancelMailBtn")
        .classList.remove("d-none");

        editingMail = true;

        return;
    }

    const userId = localStorage.getItem("user_id");
    const response = await fetch(
        `/api/user/${userId}/email`,
        {
            method:"POST",

            headers:{
                "Content-Type":"application/json"
            },

            body:JSON.stringify({

                email:input.value

            })
        }
    );

    const data = await response.json();

    alert(data.message);

    if(response.ok){

        input.readOnly = true;

        button.textContent = "変更";

        button.classList.remove("btn-primary");
        button.classList.add("btn-success");
        button.classList.add("rounded-end");

        document.getElementById("cancelMailBtn")
        .classList.add("d-none");

        editingMail = false;
    }
}
function cancelMail(){

        const input = document.getElementById("currentMail");
        const button = document.getElementById("mailBtn");
        const cancel = document.getElementById("cancelMailBtn");

        input.value = oldMail;
        input.readOnly = true;

        button.textContent = "変更";
        button.classList.remove("btn-primary");
        button.classList.add("btn-success");
        button.classList.add("rounded-end");

        cancel.classList.add("d-none");

        editingMail = false;
}
let passwordVisible = false;

async function togglePassword() {

    const input = document.getElementById("currentPassword");
    const button = document.getElementById("passwordViewBtn");

    // 非表示 → 表示
    if (!passwordVisible) {

        const password = prompt("現在のパスワードを入力してください");

        if (password === null) {
            return;
        }

        const userId = localStorage.getItem("user_id");
        const response = await fetch(
            `/api/user/${userId}/password/check`,
            {
                method: "POST",
                headers: {
                    "Content-Type": "application/json"
                },
                body: JSON.stringify({
                    password: password
                })
            }
        );

        const data = await response.json();

        if (!response.ok) {
            alert(data.message);
            return;
        }

        // DBで一致したら表示
        input.type = "text";
        input.value = password;

        button.textContent = "非表示";
        button.classList.remove("btn-success");
        button.classList.add("btn-warning");

        passwordVisible = true;

    } else {

        // 表示 → 非表示
        input.type = "password";
        input.value = "********";

        button.textContent = "表示";
        button.classList.remove("btn-warning");
        button.classList.add("btn-success");

        passwordVisible = false;
    }
}
</script>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>