<button class="btn btn-danger" onclick="logout()">
    ログアウト
</button>

<script>
function logout() {

    localStorage.removeItem("user_id");

    location.href = "/login";
}
</script>