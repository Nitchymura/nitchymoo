//photo-uploader-bridge.js

// public/js/photos-uploader-bridge.js
document.addEventListener("DOMContentLoaded", () => {
  const bulk = document.getElementById("photos-uploader");
  if (!bulk) return;

  bulk.addEventListener("change", (e) => {
    const files = Array.from(e.target.files || []);
    if (!files.length) return;

    // すべてのスロット用 hidden input を取得（順番どおりに配分）
    const slotInputs = Array.from(document.querySelectorAll("input.photo-input"));

    files.forEach((file) => {
      // 空いている（=プレビュー画像がまだない）スロットを探す
      const targetInput = findNextEmptySlot(slotInputs);
      if (!targetInput) {
        console.warn("No empty photo slot available.");
        return;
      }
      // 1ファイルをそのスロットの input.files にセット（DataTransfer 経由）
      assignFileToInput(targetInput, file);

      // 既存の edit-photo.js の change ハンドラに乗せる
      targetInput.dispatchEvent(new Event("change", { bubbles: true }));
    });

    // 同じファイルを再選択できるよう、値をクリア
    bulk.value = "";
  });

  /**
   * 空きスロット判定：
   * - プレビュー領域に <img> が無い（=未使用）
   * - かつ input 自体にファイル未設定
   * - 既存画像が表示中の枠はスキップ（置換はユーザーが削除ボタンを押して空ける想定）
   */
  function findNextEmptySlot(slotInputs) {
    for (const input of slotInputs) {
      const idParts = input.id.split("_"); // photo_{postId}_{i}
      const postId = idParts[1];
      const slot = idParts[2];
      const previewWrap = document.getElementById(`preview_${postId}_${slot}`);
      const hasImg = previewWrap?.querySelector(`#preview_img_${postId}_${slot}`);
      const hasFile = input.files && input.files.length > 0;
      if (!hasImg && !hasFile) {
        return input;
      }
    }
    return null;
  }

  /**
   * DataTransfer を使って input.files に 1ファイルを設定
   * （モダンブラウザ対応：Chrome/Edge/Firefox）
   */
  function assignFileToInput(input, file) {
    const dt = new DataTransfer();
    dt.items.add(file);
    input.files = dt.files;
  }
});