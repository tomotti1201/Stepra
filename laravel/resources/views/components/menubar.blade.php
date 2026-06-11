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
            class="btn {{ request()->is('calendar') ? 'btn-success' : 'btn-outline-secondary' }}"
            onclick="location.href='/calendar'">
            📅 月間カレンダー
        </button>

        <button
            class="btn {{ request()->is('group') ? 'btn-success' : 'btn-outline-secondary' }}"
            onclick="location.href='/group'">
            👥 グループ
        </button>

        <button
            class="btn {{ request()->is('setting') ? 'btn-success' : 'btn-outline-secondary' }}"
            onclick="location.href='/setting'">
            ⚙️ 設定・継続率
        </button>

    </div>

</nav>