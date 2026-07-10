<nav class="navbar bg-white border-top fixed-bottom">

    <div class="container d-flex justify-content-around">

        <button
            class="btn {{ request()->is('home') ? 'btn-success' : 'btn-outline-secondary' }}"
            onclick="location.href='/home'">
            🏠 ホーム
        </button>

        <button
            class="btn {{ request()->is('task') ? 'btn-success' : 'btn-outline-secondary' }}"
            onclick="location.href='/task'">
            🎯 目標
        </button>

        <button
            class="btn {{ request()->is('schedules') ? 'btn-success' : 'btn-outline-secondary' }}"
            onclick="location.href='/schedules'">
            📅 月間カレンダー
        </button>

        <button
            class="btn {{ request()->is('gurupu') ? 'btn-success' : 'btn-outline-secondary' }}"
            onclick="location.href='/gurupu?user_id=' + encodeURIComponent(localStorage.getItem('user_id') || '')">
            👥 グループ
        </button>

        <button
            class="btn {{ request()->is('continuity') ? 'btn-success' : 'btn-outline-secondary' }}"
            onclick="location.href='/continuity'">
            ⚙️ 継続率
        </button>

        <button
            class="btn {{ request()->is('setting') ? 'btn-success' : 'btn-outline-secondary' }}"
            onclick="location.href='/setting'">
            ⚙️ 設定
        </button>

    </div>

</nav>
