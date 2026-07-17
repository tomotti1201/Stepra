<div class="card shadow-sm">

    <div class="list-group list-group-flush">

        <a href="/group/{{ $group->id }}/schedule"
           class="list-group-item list-group-item-action {{ request()->is('group/'.$group->id.'/schedule') ? 'bg-secondary-subtle' : 'bg-light' }}">
            📅 スケジュール
        </a>

        <a href="/group/{{ $group->id }}/tasks"
           class="list-group-item list-group-item-action {{ request()->is('group/'.$group->id.'/tasks') ? 'bg-secondary-subtle' : 'bg-light' }}">
            ✅ タスク一覧
        </a>

        <a href="/group/{{ $group->id }}/edit"
           class="list-group-item list-group-item-action {{ request()->is('group/'.$group->id.'/edit') ? 'bg-secondary-subtle' : 'bg-light' }}">
            👥 グループ情報
        </a>

    </div>

</div>