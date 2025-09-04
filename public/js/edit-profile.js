//edit-profile.js

document.addEventListener("DOMContentLoaded", function () {
    const avatarInput = document.getElementById('avatar');
    const preview = document.getElementById('avatar-preview');
    const userIcon = document.getElementById('user-icon');
    const deleteBtn = document.getElementById('delete-avatar');
    const avatarDeleted = document.getElementById('avatar-deleted');

    // --- 初期状態でアイコンを正しく表示/非表示 ---
    if (!preview.src || preview.src.trim() === '') {
        preview.style.display = 'none';
        if (userIcon) userIcon.classList.remove('d-none');
    } else {
        preview.style.display = 'block';
        if (userIcon) userIcon.classList.add('d-none');
    }

    // --- ファイル選択時 ---
    avatarInput.addEventListener('change', function(event) {
        const file = event.target.files[0];

        if (!file) {
            // ファイル選択解除時
            if (preview.dataset.original) {
                preview.src = preview.dataset.original;
                preview.style.display = 'block';
                if (userIcon) userIcon.classList.add('d-none');
                if (deleteBtn) deleteBtn.style.display = 'inline-block';
                avatarDeleted.value = 'false';
            } else {
                preview.style.display = 'none';
                if (userIcon) userIcon.classList.remove('d-none');
            }
            return;
        }

        if (!file.type.startsWith("image/")) return;

        const reader = new FileReader();
        reader.onload = function(e) {
            preview.src = e.target.result;
            preview.style.display = 'block';
            if (userIcon) userIcon.classList.add('d-none');
            avatarDeleted.value = 'false';
            if (deleteBtn) deleteBtn.style.display = 'none';
        };
        reader.readAsDataURL(file);
    });

    // --- 削除ボタン ---
    if (deleteBtn) {
        deleteBtn.addEventListener('click', function () {
            fetch("/profile/avatar", {    
                method: "DELETE",
                headers: {
                    "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').getAttribute("content"),
                    "Accept": "application/json"
                }
            })
            .then(response => {
                if (response.ok) {
                    preview.src = '';
                    preview.style.display = 'none';
                    if (userIcon) userIcon.classList.remove('d-none');
                    avatarDeleted.value = 'true';
                    deleteBtn.style.display = 'none';
                    avatarInput.value = '';
                } else {
                    alert('削除に失敗しました');
                }
            })
            .catch(() => alert('通信エラーが発生しました'));
        });
    }
});
//# sourceMappingURL=edit-profile.js.map
