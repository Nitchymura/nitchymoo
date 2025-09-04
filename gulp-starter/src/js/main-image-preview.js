//main-image-preview.js

document.addEventListener("DOMContentLoaded", () => {
    const fileInput = document.getElementById("image");
    const previewContainer = document.getElementById("main-image-preview") || document.getElementById("image-icon");

    if (!fileInput || !previewContainer) return;

    fileInput.addEventListener("change", (e) => {
        const file = e.target.files[0];

        // 選択解除された場合
        if (!file) {
            // edit ページなら既存画像を戻す
            if (previewContainer.dataset.existing) {
                previewContainer.src = previewContainer.dataset.existing;
            } else {
                // create ページならアイコン表示
                previewContainer.outerHTML = `<i class="fa-solid fa-image text-secondary icon-lg d-block text-center" id="image-icon"></i>`;
            }
            return;
        }

        // 画像ファイル以外は無視
        if (!file.type.startsWith("image/")) {
            previewContainer.outerHTML = `<p class='text-danger small'>Invalid file type</p>`;
            return;
        }

        const reader = new FileReader();
        reader.onload = (ev) => {
            // プレビュー表示
            if (previewContainer.tagName === "IMG") {
                previewContainer.src = ev.target.result;
            } else {
                // アイコンだった場合は置き換え
                const img = document.createElement("img");
                img.src = ev.target.result;
                img.alt = "Preview";
                img.id = "main-image-preview";
                img.className = "d-block w-50 img-thumbnail mb-2";
                previewContainer.replaceWith(img);
            }
        };
        reader.readAsDataURL(file);
    });
});