// public/js/follow.js
document.addEventListener("DOMContentLoaded", () => {
  const busyByUser = new Set();

  document.body.addEventListener("click", async (e) => {
    const followBtn = e.target.closest(".follow-btn");
    if (!followBtn) return;

    e.preventDefault();

    const form   = followBtn.closest(".follow-form");
    if (!form) return;

    const userId = form.dataset.userId;
    const url    = followBtn.dataset.url || form.action;

    if (busyByUser.has(userId)) return; // 同一ユーザー連打防止
    busyByUser.add(userId);
    followBtn.disabled = true;

    // CSRF
    const tokenInput = form.querySelector('input[name="_token"]');
    const tokenMeta  = document.querySelector('meta[name="csrf-token"]');
    const csrfToken  = tokenInput?.value || tokenMeta?.getAttribute('content') || '';

    // 楽観的UIのために、現在の状態を取得
    const isFollowingNow =
      followBtn.classList.contains('btn-outline-secondary'); // ← フォロー中の見た目

    // 楽観的に画面上の同一ユーザーのボタンを全部更新する関数
    const paintAll = (following) => {
      document.querySelectorAll(`form.follow-form[data-user-id="${userId}"] .follow-btn`)
        .forEach(btn => {
          btn.classList.remove('btn-outline-secondary','btn-info','text-white');
          const label = btn.querySelector('.label');
          if (following) {
            btn.classList.add('btn-outline-secondary');
            if (label) label.textContent = 'Following';
          } else {
            btn.classList.add('btn-info','text-white');
            if (label) label.textContent = 'Follow';
          }
          btn.setAttribute('aria-pressed', String(following));
        });
    };

    // ① 楽観的UI：まず見た目だけ先に切り替え
    paintAll(!isFollowingNow);

    try {
      const res = await fetch(url, {
        method: "POST",
        headers: {
          "Content-Type": "application/json",
          "X-CSRF-TOKEN": csrfToken,
          "Accept": "application/json",
          "X-Requested-With": "XMLHttpRequest",
        },
        body: JSON.stringify({}),
        credentials: "same-origin",
      });

      if (!res.ok) {
        // ロールバック
        paintAll(isFollowingNow);
        console.error("toggle-follow失敗:", res.status, await res.text());
        return;
      }

      // 期待レスポンス: { following: true|false, followers_count?: number }
      const data = await res.json();
      if (typeof data.following === 'boolean') {
        paintAll(data.following);
      } else {
        // 形式が違う場合はサーバー値不明なのでいったんロールバック
        paintAll(isFollowingNow);
        console.error("不正なレスポンス形式:", data);
      }

      // フォロワー数を表示している要素があれば同期（任意）
      if (typeof data.followers_count !== 'undefined') {
        document.querySelectorAll(`[data-followers-count-user-id="${userId}"]`)
          .forEach(el => { el.textContent = String(data.followers_count); });
      }

    } catch (err) {
      // 通信失敗 → ロールバック
      paintAll(isFollowingNow);
      console.error("FOLLOW JS error:", err);
    } finally {
      busyByUser.delete(userId);
      followBtn.disabled = false;
    }
  });
});
