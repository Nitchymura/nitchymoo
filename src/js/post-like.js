// public/js/post-like.js
document.addEventListener("DOMContentLoaded", () => {
  // フォーム単位でロック
  const busyForms = new WeakSet();

  document.body.addEventListener("click", async (e) => {
    const likeBtn = e.target.closest(".post-like-btn");
    if (!likeBtn) return;

    e.preventDefault();

    const form = likeBtn.closest(".like-post-form");
    if (!form) return;

    // すでに処理中なら無視
    if (busyForms.has(form)) return;
    busyForms.add(form);
    likeBtn.disabled = true;

    const postId    = form.dataset.postId;
    const url       = form.action;
    const token     = form.querySelector('input[name="_token"]').value;
    const icon      = likeBtn.querySelector("i");
    const countSpan = form.querySelector(".post-like-count"); // ← form内を対象に

    try {
      const res = await fetch(url, {
        method: "POST",
        headers: {
          "Content-Type": "application/json",
          "X-CSRF-TOKEN": token,
          "Accept": "application/json",
          "X-Requested-With": "XMLHttpRequest",
        },
        body: JSON.stringify({}),
        credentials: "same-origin",
      });

      if (!res.ok) {
        console.error("toggle-like失敗:", await res.text());
        return;
      }

      const { liked, like_count } = await res.json();

      icon.classList.remove("fa-regular", "fa-solid", "text-danger");
      liked ? icon.classList.add("fa-solid", "text-danger")
            : icon.classList.add("fa-regular");

      if (countSpan) countSpan.textContent = like_count;
    } catch (err) {
      console.error("JSエラー:", err);
    } finally {
      // ロック解除
      busyForms.delete(form);
      likeBtn.disabled = false;
    }
  });
});
