document.addEventListener("DOMContentLoaded", function () {
    const avatarInput = document.getElementById('avatar');
    const preview = document.getElementById('avatar-preview');
    const userIcon = document.getElementById('user-icon');  // user-iconを取得
    const deleteBtn = document.getElementById('delete-avatar');
    const avatarDeleted = document.getElementById('avatar-deleted');  // hiddenフィールドを取得

    // 画像が選択された時にプレビュー表示
    avatarInput.addEventListener('change', function(event) {
        const file = event.target.files[0];
        if (!file) return;

        const reader = new FileReader();
        reader.onload = function(e) {
            preview.src = e.target.result;  // Base64データを設定
            preview.style.display = 'block';  // プレビュー画像を表示
            if (userIcon) userIcon.style.display = 'none';  // user-iconを非表示にする
            avatarDeleted.value = 'false';  // 画像が選ばれた場合、削除フラグをfalseに設定

            // 画像選択後に削除ボタンを非表示にする
            deleteBtn.style.display = 'none';  // 新たにファイルが選択されたらdeleteボタンを非表示
        };
        reader.readAsDataURL(file);  // 画像をBase64形式で読み込む
    });

    // 削除ボタンクリックで画像削除処理
    deleteBtn.addEventListener('click', function () {
        // ローカル開発環境の場合はlocalhostにする
        // fetch("http://127.0.0.1:8000/profile/avatar", {
        fetch("https://nitchymoo-d0fdfc674f5d.herokuapp.com/profile/avatar", {    
        method: "DELETE",
            headers: {
                "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').getAttribute("content"),
                "Accept": "application/json"
            }
        })
        .then(response => {
            if (response.ok) {
                preview.src = '';  // プレビュー画像をリセット
                preview.style.display = 'none';  // プレビュー画像を非表示
                if (userIcon) userIcon.style.display = 'block';  // user-iconを再表示
                avatarDeleted.value = 'true';  // 画像削除フラグをtrueに設定
                deleteBtn.style.display = 'none';  // 削除後にdeleteボタンを非表示に設定
                // inputタグの値をリセット（選択されたファイルを削除）
                avatarInput.value = ''; // inputのvalueを空にすることでファイル選択をリセット
            } else {
                alert('削除に失敗しました');
            }
        })
        .catch(() => {
            alert('通信エラーが発生しました');
        });
    });
});
