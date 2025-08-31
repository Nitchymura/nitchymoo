// public/js/post-like.js
// 両方のマークアップで動く版：
// ① <form class="like-post-form" action="..."><button .post-like-btn ...></button></form>
// ② <button .post-like-btn data-post-id=".." data-toggle-url="..."></button>

document.addEventListener("DOMContentLoaded", () => {
  // 「同じ投稿」に対する連打防止（フォーム単位でなく postId 単位でロック）
  const busyByPostId = new Map();

  // ページ内の同一投稿のカウント表示を一括更新
  const updateAllCounts = (postId, likeCount) => {
    document.querySelectorAll(`.post-like-count[data-post-id="${postId}"]`)
      .forEach(el => { el.textContent = likeCount; });
  };

  // 同一投稿の全アイコンを liked / unliked に同期
  const updateAllIcons = (postId, liked) => {
    document.querySelectorAll(`.post-like-btn[data-post-id="${postId}"] i.fa-heart`)
      .forEach(icon => {
        icon.classList.remove("fa-regular", "fa-solid", "text-danger");
        if (liked) {
          icon.classList.add("fa-solid", "text-danger");
        } else {
          icon.classList.add("fa-regular");
        }
      });
  };

  // CSRF トークン取得（form 内 hidden or <meta>）
  const getCsrfToken = (form) => {
    if (form) {
      const hidden = form.querySelector('input[name="_token"]');
      if (hidden?.value) return hidden.value;
    }
    const meta = document.querySelector('meta[name="csrf-token"]');
    return meta?.getAttribute("content") || "";
  };

  // トグル URL を決定（form.action 優先。なければ data-toggle-url、最後に /posts/{id}/toggle-like を試す）
  const resolveToggleUrl = (form, btn, postId) => {
    if (form?.action) return form.action;
    const dataUrl = btn?.dataset.toggleUrl;
    if (dataUrl) return dataUrl;
    // ルートが /posts/{id}/toggle-like でない場合は、btnに data-toggle-url を付与してください
    return `/posts/${postId}/toggle-like`;
  };

  document.body.addEventListener("click", async (e) => {
    const likeBtn = e.target.closest(".post-like-btn");
    if (!likeBtn) return;

    e.preventDefault();

    // どのマークアップでも必須：data-post-id
    const postId = likeBtn.dataset.postId || likeBtn.closest("[data-post-id]")?.dataset.postId;
    if (!postId) {
      console.warn("post-like-btn に data-post-id がありません。");
      return;
    }

    if (busyByPostId.get(postId)) return; // 連打防止
    busyByPostId.set(postId, true);

    const form = likeBtn.closest(".like-post-form");
    const token = getCsrfToken(form);
    const url   = resolveToggleUrl(form, likeBtn, postId);

    // 楽観的 UI（サクッと切り替え）：失敗時はロールバック
    const anyIcon = likeBtn.querySelector("i.fa-heart") ||
                    document.querySelector(`.post-like-btn[data-post-id="${postId}"] i.fa-heart`);
    const wasLiked = anyIcon?.classList.contains("fa-solid");
    const countNow = (() => {
      // 最初に見つかったカウントを基準にする
      const first = document.querySelector(`.post-like-count[data-post-id="${postId}"]`);
      return first ? parseInt(first.textContent, 10) || 0 : null;
    })();

    // 楽観的反映
    if (countNow !== null) {
      const optimistic = wasLiked ? Math.max(0, countNow - 1) : countNow + 1;
      updateAllCounts(postId, optimistic);
    }
    updateAllIcons(postId, !wasLiked);
    likeBtn.setAttribute("aria-pressed", String(!wasLiked));
    likeBtn.disabled = true;

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
        // 419 などで失敗したらロールバック
        if (countNow !== null) updateAllCounts(postId, countNow);
        updateAllIcons(postId, wasLiked);
        console.error("toggle-like失敗:", await res.text());
        return;
      }

      const { liked, like_count } = await res.json();

      // 確定値で同期
      updateAllIcons(postId, liked);
      if (!Number.isNaN(parseInt(like_count, 10))) {
        updateAllCounts(postId, like_count);
      }
    } catch (err) {
      // 通信エラー → ロールバック
      if (countNow !== null) updateAllCounts(postId, countNow);
      updateAllIcons(postId, wasLiked);
      console.error("JSエラー:", err);
    } finally {
      likeBtn.disabled = false;
      busyByPostId.delete(postId);
    }
  });
});
