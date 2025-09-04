//edit-photo.js

// public/js/edit-photo.js
document.addEventListener("DOMContentLoaded", () => {
  // 既存の削除ボタン（Blade側で描画済み）にも対応するため、イベント委譲で受ける
  document.addEventListener("click", (e) => {
    const btn = e.target.closest(".delete-photo-btn");
    if (!btn) return;

    e.preventDefault();

    // data-preview-id は "{postId}_{i}" 形式
    const previewKey = btn.dataset.previewId;
    if (!previewKey) return;

    const [postId, slot] = previewKey.split("_");
    handleDelete(postId, slot, btn);
  });

  // すべての file input に change をバインド
  document.querySelectorAll(".photo-input").forEach((input) => {
    input.addEventListener("change", (e) => {
      const id = e.currentTarget.id; // "photo_{postId}_{i}"
      const parts = id.split("_");
      // ["photo", "{postId}", "{i}"]
      const postId = parts[1];
      const slot = parts[2];
      handleFileChange(postId, slot, e.currentTarget.files);
    });
  });

  /**
   * 画像選択時の処理（プレビュー表示、削除ボタン生成、置換時の削除フラグ設定）
   */
  function handleFileChange(postId, slot, files) {
    if (!files || !files.length) return;

    const file = files[0];
    // 軽いバリデーション（任意）
    if (!file.type.startsWith("image/")) return;

    const previewWrap = document.getElementById(`preview_${postId}_${slot}`);
    const placeholder = document.getElementById(`placeholder_${postId}_${slot}`);
    const imgId = `preview_img_${postId}_${slot}`;
    let img = document.getElementById(imgId);

    // 既存画像がなければ img を作る
    if (!img) {
      img = document.createElement("img");
      img.id = imgId;
      img.alt = `Photo ${slot}`;
      img.className = "image-lg img-thumbnail mb-2";
      // inputより前に差し込む（配置の都合で先頭へ）
      previewWrap.insertBefore(img, previewWrap.querySelector("input[type=file]"));
    }

    // プレビュー反映
    const reader = new FileReader();
    reader.onload = (ev) => {
      img.src = ev.target.result;
      if (placeholder) placeholder.classList.add("d-none");
      ensureDeleteButton(postId, slot, previewWrap);
    };
    reader.readAsDataURL(file);

    // もし既存写真がある枠に新ファイルを入れたら「置換」とみなし削除フラグを1に
    const existingIdInput = document.querySelector(
      `input[name="existing_photos[${postId}][${slot}]"]`
    );
    const deleteFlagInput = document.getElementById(`delete_photo_${postId}_${slot}`);
    if (existingIdInput && existingIdInput.value) {
      // 既存がある → 置換＝削除フラグON（サーバ側で古い方を消して新規を保存する想定）
      if (deleteFlagInput) deleteFlagInput.value = "1";
    } else {
      // 新規枠 → 削除フラグは常に0
      if (deleteFlagInput) deleteFlagInput.value = "0";
    }
  }

  /**
   * プレビュー領域に削除ボタンがなければ作る
   */
  function ensureDeleteButton(postId, slot, previewWrap) {
    let btn = previewWrap.querySelector(".delete-photo-btn");
    if (btn) return;

    btn = document.createElement("button");
    btn.type = "button";
    btn.className = "btn btn-danger delete-photo-btn";
    btn.dataset.previewId = `${postId}_${slot}`;
    btn.innerHTML = '<i class="fa-solid fa-trash-can py-1 px-1"></i>';
    // 位置指定は既存のCSSに依存。position-relative 親の中で重ねたいならここで調整可
    previewWrap.appendChild(btn);
  }

  /**
   * 削除ボタンクリック時の処理
   * - プレビュー画像を消す
   * - input[type=file] をリセット
   * - placeholder を戻す
   * - 既存画像がある場合は delete_flag=1、新規だけなら delete_flag=0
   */
  function handleDelete(postId, slot, btnEl) {
    const previewWrap = document.getElementById(`preview_${postId}_${slot}`);
    if (!previewWrap) return;

    // 画像プレビュー削除
    const img = document.getElementById(`preview_img_${postId}_${slot}`);
    if (img) img.remove();

    // placeholder 戻す
    const placeholder = document.getElementById(`placeholder_${postId}_${slot}`);
    if (placeholder) placeholder.classList.remove("d-none");

    // file input をクリア
    const fileInput = document.getElementById(`photo_${postId}_${slot}`);
    if (fileInput) fileInput.value = "";

    // 削除フラグ設定
    const existingIdInput = document.querySelector(
      `input[name="existing_photos[${postId}][${slot}]"]`
    );
    const deleteFlagInput = document.getElementById(`delete_photo_${postId}_${slot}`);
    if (deleteFlagInput) {
      // 既存があったなら1（サーバで削除）。無ければ0（単に未選択に戻しただけ）
      deleteFlagInput.value = existingIdInput && existingIdInput.value ? "1" : "0";
    }

    // ボタン自体は残しても良いが、見た目上消す
    if (btnEl && btnEl.parentElement === previewWrap) {
      btnEl.remove();
    }
  }
});