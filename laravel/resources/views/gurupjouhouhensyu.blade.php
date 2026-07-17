<!DOCTYPE html>
<html lang="ja">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>グループ管理</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

  <div id="screen-main" class="container py-4 mb-5">

    <!-- アイコン -->
    <img src="{{ asset('image/tit.png') }}"
         class="mb-3"
         style="width:200px;">

    <div class="d-flex">

        <!-- サイドバー -->
        <div class="flex-shrink-0 me-4" style="width:180px;">

            <x-groupmenubar
                :group="$group"
                active="info"/>

        </div>

        <!-- メイン -->
        <div class="flex-grow-1">

            <div class="card shadow p-3 bg-body-tertiary">
          <div class="row g-2 mb-3 align-items-center">
            <div class="col-12 col-md-9">
              <h1 class="text-center fw-bold display-6 m-0 border py-2 bg-white rounded fs-4">グループ管理</h1>
            </div>
            <div class="col-12 col-md-3">
              <button class="btn btn-outline-secondary w-100 py-2" onclick="showInviteCode()">招待ID</button>
            </div>
          </div>

          <div class="card shadow mb-3">
            <div class="card-body text-center py-4 bg-white rounded fw-bold text-primary">
              下のメンバー一覧から選択してください
            </div>
          </div>

          <div class="card shadow mb-3">
            <div class="card-body bg-white rounded p-0" style="height: 280px; overflow-y: auto;">
              <div id="member-list" class="list-group list-group-flush"></div>
            </div>
          </div>

          <div class="row g-3 mt-4 mb-5">
            <div class="col-12 col-md-4">
              <button id="group-edit-button" class="btn btn-primary w-100 py-3 fw-bold shadow-sm" onclick="openGroupEdit()" disabled>
                グループの編集
              </button>
            </div>
            <div class="col-12 col-md-4">
              <button class="btn btn-success w-100 py-3 fw-bold shadow-sm" onclick="openGroupTasks()">
                グループタスクの追加・編集
              </button>
            </div>
            <div class="col-12 col-md-4">
              <button id="home-group-button" class="btn btn-outline-success w-100 py-3 fw-bold shadow-sm" onclick="setHomeGroup()">
                ホーム画面に設定
              </button>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <div id="screen-detail" class="container py-4 mx-auto d-none" style="max-width: 800px; margin-bottom: 80px;">
    <div class="card shadow p-4 bg-white" style="min-height: 85vh;">
      <div class="text-start mb-5">
        <button class="btn btn-secondary px-4" onclick="navigateBack()">戻る</button>
      </div>

      <div class="container-fluid">
        <div class="row g-3 align-items-center justify-content-center mb-4 mx-auto" style="max-width: 600px;">
          <div class="col-4 text-center">
            <div id="mobile-profile-icon" class="rounded-circle border d-flex align-items-center justify-content-center bg-secondary-subtle fw-bold mx-auto display-4 shadow-sm" style="width: 100px; height: 100px;">👤</div>
          </div>
          <div class="col-8">
            <div id="mobile-profile-username" class="border p-3 rounded bg-white text-center fw-bold fs-3 shadow-sm">ユーザー名</div>
          </div>
        </div>

        <hr class="my-5 text-muted mx-auto" style="max-width: 600px;">

        <div class="text-center py-4 mx-auto" style="max-width: 600px;">
          <p id="mobile-profile-text" class="text-secondary fs-4 lh-lg">プロフィール内容</p>
        </div>
      </div>
    </div>
  </div>

  <div class="modal fade" id="groupEditModal" tabindex="-1" aria-labelledby="groupEditModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title fw-bold" id="groupEditModalLabel">グループの編集</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="閉じる"></button>
        </div>
        <div class="modal-body">
          <div class="mb-3">
            <label for="edit-group-name" class="form-label fw-bold">グループ名</label>
            <input id="edit-group-name" type="text" class="form-control" maxlength="255">
          </div>
          <div class="mb-3">
            <label for="edit-group-icon" class="form-label fw-bold">アイコン</label>
            <input id="edit-group-icon" type="text" class="form-control" maxlength="20">
          </div>
          <div class="mb-3">
            <label for="edit-group-description" class="form-label fw-bold">説明</label>
            <textarea id="edit-group-description" class="form-control" rows="4"></textarea>
          </div>
          <div class="form-check">
            <input id="edit-group-public" class="form-check-input" type="checkbox">
            <label class="form-check-label" for="edit-group-public">公開グループにする</label>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">キャンセル</button>
          <button type="button" class="btn btn-primary" onclick="saveGroup()">保存</button>
        </div>
      </div>
    </div>
  </div>

  <div class="modal fade" id="inviteCodeModal" tabindex="-1" aria-labelledby="inviteCodeModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title fw-bold" id="inviteCodeModalLabel">招待コード</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="閉じる"></button>
        </div>
        <div class="modal-body text-center">
          <div class="text-secondary mb-2">このコードを共有してください</div>
          <div id="invite-code-text" class="fs-2 fw-bold border rounded bg-light py-3"></div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">閉じる</button>
          <button type="button" class="btn btn-primary" onclick="copyInviteCode()">コピー</button>
        </div>
      </div>
    </div>
  </div>
<x-menubar />

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
  <script>
    const groupId = @json($group->id);
    const currentUserId = localStorage.getItem('user_id');
    const groupData = {
      name: @json($group->name),
      icon: @json($group->icon),
      description: @json($group->description),
      invite_code: @json($group->invite_code),
      is_public: @json((bool) $group->is_public),
    };
    let currentUserRole = null;

    function isCurrentUserAdmin() {
      return currentUserRole === 'admin';
    }

    function openGroupEdit() {
      if (!isCurrentUserAdmin()) {
        alert('管理者だけがグループ名を変更できます');
        return;
      }

      document.getElementById('edit-group-name').value = groupData.name || '';
      document.getElementById('edit-group-icon').value = groupData.icon || '';
      document.getElementById('edit-group-description').value = groupData.description || '';
      document.getElementById('edit-group-public').checked = groupData.is_public;
      bootstrap.Modal.getOrCreateInstance(document.getElementById('groupEditModal')).show();
    }

    async function saveGroup() {
      if (!isCurrentUserAdmin()) {
        alert('管理者だけがグループ名を変更できます');
        return;
      }

      const name = document.getElementById('edit-group-name').value.trim();

      if (!name) {
        alert('グループ名を入力してください');
        return;
      }

      const response = await fetch(`/api/groups/${groupId}`, {
        method: 'PUT',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({
          request_user_id: currentUserId,
          name,
          icon: document.getElementById('edit-group-icon').value.trim() || null,
          description: document.getElementById('edit-group-description').value.trim() || null,
          is_public: document.getElementById('edit-group-public').checked ? 1 : 0,
        }),
      });

      if (!response.ok) {
        alert('グループの更新に失敗しました');
        return;
      }

      location.reload();
    }

    function openGroupTasks() {
      location.href = `/group/${groupId}/task/create`;
    }

    function setHomeGroup() {
      const selectedGroupId = localStorage.getItem('group_id');

      if (String(selectedGroupId) === String(groupId)) {
        localStorage.setItem('group_id', '');
      } else {
        localStorage.setItem('group_id', groupId);
      }

      updateHomeGroupButton();
    }

    function updateHomeGroupButton() {
      const button = document.getElementById('home-group-button');
      const selectedGroupId = localStorage.getItem('group_id');
      const isSelected = String(selectedGroupId) === String(groupId);

      button.classList.toggle('btn-success', isSelected);
      button.classList.toggle('btn-outline-success', !isSelected);
      button.textContent = isSelected
        ? 'ホーム画面に設定中'
        : 'ホーム画面に設定';
    }

    function showInviteCode() {
      document.getElementById('invite-code-text').textContent = groupData.invite_code || '招待コードがありません';
      bootstrap.Modal.getOrCreateInstance(document.getElementById('inviteCodeModal')).show();
    }

    async function copyInviteCode() {
      if (!groupData.invite_code) {
        return;
      }

      try {
        await navigator.clipboard.writeText(groupData.invite_code);
        alert('招待コードをコピーしました');
      } catch (error) {
        alert(`招待コード: ${groupData.invite_code}`);
      }
    }

    async function loadMembers() {
      const memberList = document.getElementById('member-list');
      memberList.innerHTML = '<div class="list-group-item p-3 text-center text-secondary">読み込み中...</div>';

      try {
        const response = await fetch(`/api/groupmembers?group_id=${groupId}`);

        if (!response.ok) {
          throw new Error('Failed to load group members');
        }

        const members = await response.json();
        renderMembers(members);
      } catch (error) {
        console.error(error);
        memberList.innerHTML = '<div class="list-group-item p-3 text-center text-danger">メンバーを取得できませんでした</div>';
      }
    }

    function renderMembers(members) {
      const memberList = document.getElementById('member-list');
      memberList.innerHTML = '';

      if (!members.length) {
        currentUserRole = null;
        updateAdminControls();
        memberList.innerHTML = '<div class="list-group-item p-3 text-center text-secondary">メンバーがいません</div>';
        return;
      }

      const currentMember = members.find((member) => String(member.user_id) === String(currentUserId));
      currentUserRole = currentMember?.role || null;
      updateAdminControls();

      const canDeleteMembers = isCurrentUserAdmin();

      members.forEach((member) => {
        const icon = member.icon || '👤';
        const username = member.name || `ユーザー${member.user_id}`;
        const info = member.profile_text || `ロール: ${member.role}`;

        const row = document.createElement('div');
        row.className = 'list-group-item p-3 d-flex align-items-center gap-2';

        const profileButton = document.createElement('button');
        profileButton.type = 'button';
        profileButton.className = 'btn btn-light flex-grow-1 d-flex align-items-center text-start border-0 p-0';
        profileButton.addEventListener('click', () => selectMember(icon, username, info));

        const iconElement = document.createElement('div');
        iconElement.className = 'rounded-circle border bg-light text-center me-3';
        iconElement.style.width = '40px';
        iconElement.style.height = '40px';
        iconElement.style.lineHeight = '40px';
        iconElement.textContent = icon;

        const nameElement = document.createElement('span');
        nameElement.className = 'fw-bold';
        nameElement.textContent = username;

        profileButton.append(iconElement, nameElement);
        row.appendChild(profileButton);

        if (canDeleteMembers && String(member.user_id) !== String(currentUserId)) {
          const deleteButton = document.createElement('button');
          deleteButton.type = 'button';
          deleteButton.className = 'btn btn-outline-danger btn-sm fw-bold';
          deleteButton.textContent = '削除';
          deleteButton.addEventListener('click', () => deleteMember(member.id, username));
          row.appendChild(deleteButton);
        }

        memberList.appendChild(row);
      });
    }

    function updateAdminControls() {
      const editButton = document.getElementById('group-edit-button');
      editButton.disabled = !isCurrentUserAdmin();
      editButton.title = isCurrentUserAdmin()
        ? ''
        : '管理者だけがグループ名を変更できます';
    }

    async function deleteMember(memberId, username) {
      if (!confirm(`${username} をグループから削除しますか？`)) {
        return;
      }

      const response = await fetch(`/api/groupmembers/${memberId}?request_user_id=${encodeURIComponent(currentUserId || '')}`, {
        method: 'DELETE',
      });

      if (!response.ok) {
        alert('メンバーを削除できませんでした');
        return;
      }

      await loadMembers();
    }

    function selectMember(icon, username, info) {
      document.getElementById('mobile-profile-icon').innerText = icon;
      document.getElementById('mobile-profile-username').innerText = username;
      document.getElementById('mobile-profile-text').innerText = info;
      const menuBar = document.getElementById('main-menu-bar');
      document.getElementById('screen-main').classList.add('d-none');
      menuBar?.classList.add('d-none');
      document.getElementById('screen-detail').classList.remove('d-none');
      window.scrollTo(0, 0);
    }

    function navigateBack() {
      const menuBar = document.getElementById('main-menu-bar');
      document.getElementById('screen-detail').classList.add('d-none');
      document.getElementById('screen-main').classList.remove('d-none');
      menuBar?.classList.remove('d-none');
    }

    document.addEventListener('DOMContentLoaded', () => {
      updateHomeGroupButton();
      loadMembers();
    });
  </script>
</body>
</html>
