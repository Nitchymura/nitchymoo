document.addEventListener("DOMContentLoaded", () => {
    const fileInput = document.getElementById("image");
    const previewContainer = document.getElementById("main-image-preview");

    if (!fileInput || !previewContainer) return;

    fileInput.addEventListener("change", (e) => {
        const file = e.target.files[0];

        // 選択解除された場合はプレビューをクリア
        if (!file) {
            previewContainer.innerHTML = "";
            return;
        }

        // 画像ファイル以外は無視
        if (!file.type.startsWith("image/")) {
            previewContainer.innerHTML = "<p class='text-danger small'>Invalid file type</p>";
            return;
        }

        const reader = new FileReader();
        reader.onload = (ev) => {
            previewContainer.innerHTML = `
                <img src="${ev.target.result}" alt="Preview" class="img-thumbnail" >
            `;
        };
        reader.readAsDataURL(file);
    });
});
