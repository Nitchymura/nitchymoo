//follow.js

// public/js/follow.js
document.addEventListener("DOMContentLoaded", () => {
  const busyForms = new WeakSet();

  document.body.addEventListener("click", async (e) => {
    const followBtn = e.target.closest(".follow-btn");
    if (!followBtn) return;

    e.preventDefault();

    const form = followBtn.closest(".follow-form");
    if (!form) return;

    if (busyForms.has(form)) return;
    busyForms.add(form);
    followBtn.disabled = true;

    const url    = form.action;
    const token  = form.querySelector('input[name="_token"]').value;
    const userId = form.dataset.userId;

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
        console.error("toggle-follow失敗:", res.status, await res.text());
        return;
      }

      const data = await res.json(); // 期待: { following: true|false }
      const following = data?.following;

      // following が boolean で返ってこないと、ここで止まる
      if (typeof following !== "boolean") {
        console.error("不正なレスポンス:", data);
        return;
      }

      // 同じユーザーIDのボタンを全部更新
      document
        .querySelectorAll(`form.follow-form[data-user-id="${userId}"] .follow-btn`)
        .forEach((btn) => {
          btn.classList.remove("btn-primary", "btn-outline-secondary");
          const label = btn.querySelector(".label") || btn;
          if (following) {
            btn.classList.add("btn-outline-secondary");
            label.textContent = "Following";
          } else {
            btn.classList.add("btn-primary");
            label.textContent = "Follow";
          }
        });
    } catch (err) {
      console.error("FOLLOW JS error:", err);
    } finally {
      busyForms.delete(form);
      followBtn.disabled = false;
    }
  });
});