<!DOCTYPE html>
<html lang="ja">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>グループ管理画面</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

  <div id="screen-main" class="container-fluid py-4" style="padding-bottom: 80px;">
    <div class="row justify-content-center">
      
      <div class="col-12">
        <div class="card shadow p-3 bg-body-tertiary">
          
          <div class="row g-2 mb-3 align-items-center">
            <div class="col-12 col-md-9">
              <h1 class="text-center fw-bold display-6 m-0 border py-2 bg-white rounded fs-4">グループ(管理者)</h1>
            </div>
            <div class="col-12 col-md-3">
              <button class="btn btn-outline-secondary w-100 py-2" onclick="Showinvate({{ $group->id }})">招待ID</button>
            </div>
          </div>

          <div class="card shadow mb-3">
            <div class="card-body text-center py-4 bg-white rounded fw-bold text-primary">
              下のメンバー一覧から選択してください
            </div>
          </div>

          <div class="card shadow mb-3">
            <div class="card-body bg-white rounded p-0" style="height: 280px; overflow-y: auto;">
              <div id="member-list" class="list-group list-group-flush">
                
                <button type="button" class="list-group-item list-group-item-action p-3 d-flex align-items-center" 
                        onclick="selectMember('👨‍💻', '山田 太郎', '趣味はプログラミングです。よろしくお願いします！')">
                  <div class="rounded-circle border bg-light text-center me-3" style="width: 40px; height: 40px; line-height: 40px;">👨‍💻</div>
                  <span class="fw-bold">山田 太郎</span>
                </button>
                
                <button type="button" class="list-group-item list-group-item-action p-3 d-flex align-items-center" 
                        onclick="selectMember('👩‍🎨', '佐藤 花子', 'デザインとイラスト作成を担当しています。')">
                  <div class="rounded-circle border bg-light text-center me-3" style="width: 40px; height: 40px; line-height: 40px;">👩‍🎨</div>
                  <span class="fw-bold">佐藤 花子</span>
                </button>
                
                <button type="button" class="list-group-item list-group-item-action p-3 d-flex align-items-center" 
                        onclick="selectMember('🏃‍♂️', '鈴木 一郎', '毎朝ランニングをしています。目標達成に向けて頑張ります！')">
                  <div class="rounded-circle border bg-light text-center me-3" style="width: 40px; height: 40px; line-height: 40px;">🏃‍♂️</div>
                  <span class="fw-bold">鈴木 一郎</span>
                </button>

                <button type="button" class="list-group-item list-group-item-action p-3 d-flex align-items-center" 
                        onclick="selectMember('🐱', '高橋 次郎', '猫を飼っています。バックエンド開発が得意です。')">
                  <div class="rounded-circle border bg-light text-center me-3" style="width: 40px; height: 40px; line-height: 40px;">🐱</div>
                  <span class="fw-bold">高橋 次郎</span>
                </button>

              </div>
            </div>
          </div>

          <div class="row g-2 mb-3">
            <div class="col-12 col-md-6">
              <button class="btn btn-primary w-100 py-3 fw-bold" onclick="openGroupEdit()">グループの編集</button>
            </div>
            <div class="col-12 col-md-6">
              <button class="btn btn-success w-100 py-3 fw-bold" onclick="openGroupTasks()">グループタスクの追加・編集</button>
            </div>
          </div>

          <div class="text-center">
            <button id="admin-mode-button" class="btn btn-secondary w-100 py-2 fw-bold" onclick="toggleAdminMode()">管理者モード切り替え</button>
          </div>
          
        </div>
      </div>

    </div>
  </div>


  <div id="screen-detail" class="container py-4 mx-auto d-none" style="max-width: 800px; margin-bottom: 80px;">
    <div class="card shadow p-4 bg-white" style="min-height: 85vh;">
      
      <div class="text-start mb-5">
        <button class="btn btn-secondary px-4" onclick="navigateBack()">← 戻る</button>
      </div>

      <div class="container-fluid">
        <div class="row g-3 align-items-center justify-content-center mb-4 mx-auto" style="max-width: 600px;">
          <div class="col-4 text-center">
            <div id="mobile-profile-icon" class="rounded-circle border d-flex align-items-center justify-content-center bg-secondary-subtle fw-bold mx-auto display-4 shadow-sm" style="width: 100px; height: 100px;">
              ？
            </div>
          </div>
          <div class="col-8">
            <div id="mobile-profile-username" class="border p-3 rounded bg-white text-center fw-bold fs-3 shadow-sm">
              ユーザー名
            </div>
          </div>
        </div>
        
        <hr class="my-5 text-muted mx-auto" style="max-width: 600px;">

        <div class="text-center py-4 mx-auto" style="max-width: 600px;">
          <p id="mobile-profile-text" class="text-secondary fs-4 lh-lg">
            プロフィール内容
          </p>
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
            <label for="edit-group-icon" class="form-label fw-bold">アイコン</label>
            <input id="edit-group-icon" type="text" class="form-control" maxlength="20">
          </div>
          <div class="mb-3">
            <label for="edit-group-name" class="form-label fw-bold">グループ名</label>
            <input id="edit-group-name" type="text" class="form-control" maxlength="255">
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

  <script>
    const groupId = @json($group->id);
    const groupData = {
      name: @json($group->name),
      icon: @json($group->icon),
      description: @json($group->description),
      invite_code: @json($group->invite_code),
      is_public: @json((bool) $group->is_public),
    };
    let adminMode = true;

    function openGroupEdit() {
      document.getElementById('edit-group-icon').value = groupData.icon || '';
      document.getElementById('edit-group-name').value = groupData.name || '';
      document.getElementById('edit-group-description').value = groupData.description || '';
      document.getElementById('edit-group-public').checked = groupData.is_public;

      bootstrap.Modal.getOrCreateInstance(document.getElementById('groupEditModal')).show();
    }

    async function saveGroup() {
      const name = document.getElementById('edit-group-name').value.trim();

      if (!name) {
        alert('グループ名を入力してください');
        return;
      }

      const response = await fetch(`/api/groups/${groupId}`, {
        method: 'PUT',
        headers: {
          'Content-Type': 'application/json',
        },
        body: JSON.stringify({
          icon: document.getElementById('edit-group-icon').value.trim() || null,
          name,
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
      location.href = `/gtasutkuitiran/${groupId}`;
    }

    function Showinvate() {
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

    function toggleAdminMode() {
      adminMode = !adminMode;
      /*
      document.getElementById('admin-mode-button').textContent = adminMode
        ? '管理者モード切り替え'
        : '管理者モードに戻す';
      */
      document.getElementById('admin-mode-button').textContent = adminMode
        ? '管理者モード切り替え'
        : '管理者モードに戻す';
      document.querySelectorAll('#screen-main .btn-primary, #screen-main .btn-success')
        .forEach((button) => {
          button.disabled = !adminMode;
        });
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
//メンバー表示
    function renderMembers(members) {
      const memberList = document.getElementById('member-list');
      memberList.innerHTML = '';

      if (!members.length) {
        memberList.innerHTML = '<div class="list-group-item p-3 text-center text-secondary">メンバーがいません</div>';
        return;
      }

      members.forEach((member) => {
        const icon = member.icon || '👤';
        const username = member.name || `ユーザー${member.user_id}`;
        const info = member.profile_text || `ロール: ${member.role}`;

        const button = document.createElement('button');
        button.type = 'button';
        button.className = 'list-group-item list-group-item-action p-3 d-flex align-items-center';
        button.addEventListener('click', () => selectMember(icon, username, info));

        const iconElement = document.createElement('div');
        iconElement.className = 'rounded-circle border bg-light text-center me-3';
        iconElement.style.width = '40px';
        iconElement.style.height = '40px';
        iconElement.style.lineHeight = '40px';
        iconElement.textContent = icon;

        const nameElement = document.createElement('span');
        nameElement.className = 'fw-bold';
        nameElement.textContent = username;

        button.append(iconElement, nameElement);
        memberList.appendChild(button);
      });
    }

   

    function selectMember(icon, username, info) {
      document.getElementById('mobile-profile-icon').innerText = icon;
      document.getElementById('mobile-profile-username').innerText = username;
      document.getElementById('mobile-profile-text').innerText = info;

      document.getElementById('screen-main').classList.add('d-none');
      document.getElementById('main-menu-bar').classList.add('d-none');
      document.getElementById('screen-detail').classList.remove('d-none');
      window.scrollTo(0, 0);
    }

    function navigateBack() {
      document.getElementById('screen-detail').classList.add('d-none');
      document.getElementById('screen-main').classList.remove('d-none');
      document.getElementById('main-menu-bar').classList.remove('d-none');
    }

    document.addEventListener('DOMContentLoaded', loadMembers);
  </script>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
