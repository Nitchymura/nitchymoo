// public/js/post-like.js でもOK
document.addEventListener('click', async (event) => {
  const button = event.target.closest('.like-button');
  if (!button) return;

  const postId    = button.getAttribute('data-id');
  const toggleUrl = button.getAttribute('data-url') || `/posts/${postId}/toggle-like`;

  // CSRF（meta か hidden input を想定）
  const csrfMeta  = document.querySelector('meta[name="csrf-token"]');
  const csrfToken = csrfMeta ? csrfMeta.getAttribute('content') : '';

  // 自分の現状態
  const wasLiked = button.getAttribute('data-liked') === '1';

  // アイコンHTML（<i>丸ごと入れ替え：FAのi→svg変換問題を回避）
  const iconHtml = (liked) =>
    liked
      ? '<i class="fa-solid fa-heart text-danger"></i>'
      : '<i class="fa-regular fa-heart text-dark"></i>';

  // 画面内の同一postIdの表示を同期
  const updateAllIcons = (liked) => {
    document.querySelectorAll(`.like-button[data-id="${postId}"] .like-icon`)
      .forEach(c => { c.innerHTML = iconHtml(liked); });
  };
  const updateAllCounts = (count) => {
    document.querySelectorAll(`.like-count[data-id="${postId}"]`)
      .forEach(el => { el.textContent = count; });
  };

  // ドクンドクン（Like時だけ）
  const pulse = (btn) => {
    const box = btn.querySelector('.like-icon');
    if (!box) return;
    box.classList.remove('heart-animate'); // 連打対策
    void box.offsetWidth;                  // 再描画トリガ
    box.classList.add('heart-animate');
    box.addEventListener('animationend', () => {
      box.classList.remove('heart-animate');
    }, { once: true });
  };

  // 現在カウント
  const firstCount = document.querySelector(`.like-count[data-id="${postId}"]`);
  const countNow = firstCount ? (parseInt(firstCount.textContent, 10) || 0) : 0;

  // 多重クリック防止
  if (button.dataset.busy === '1') return;
  button.dataset.busy = '1';

  // 楽観的UI
  const willBeLiked = !wasLiked;
  const optimistic  = willBeLiked ? countNow + 1 : Math.max(0, countNow - 1);
  updateAllIcons(willBeLiked);
  updateAllCounts(optimistic);
  document.querySelectorAll(`.like-button[data-id="${postId}"]`)
    .forEach(btn => btn.setAttribute('data-liked', willBeLiked ? '1' : '0'));
  if (willBeLiked) pulse(button);

  try {
    const res = await fetch(toggleUrl, {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
        'X-CSRF-TOKEN': csrfToken,
        'Accept': 'application/json',
        'X-Requested-With': 'XMLHttpRequest',
      },
      body: JSON.stringify({}),
      credentials: 'same-origin',
    });

    if (!res.ok) {
      // ロールバック
      updateAllIcons(wasLiked);
      updateAllCounts(countNow);
      document.querySelectorAll(`.like-button[data-id="${postId}"]`)
        .forEach(btn => btn.setAttribute('data-liked', wasLiked ? '1' : '0'));
      console.error('toggle-like失敗:', await res.text());
      return;
    }

    const data = await res.json(); // 期待: { liked: bool, like_count: number }
    if (typeof data.liked !== 'undefined') {
      updateAllIcons(!!data.liked);
      document.querySelectorAll(`.like-button[data-id="${postId}"]`)
        .forEach(btn => btn.setAttribute('data-liked', data.liked ? '1' : '0'));
    }
    if (typeof data.like_count !== 'undefined') {
      updateAllCounts(parseInt(data.like_count, 10));
    }
  } catch (err) {
    // 通信失敗 → ロールバック
    updateAllIcons(wasLiked);
    updateAllCounts(countNow);
    document.querySelectorAll(`.like-button[data-id="${postId}"]`)
      .forEach(btn => btn.setAttribute('data-liked', wasLiked ? '1' : '0'));
    console.error('AJAX Error:', err);
  } finally {
    delete button.dataset.busy;
  }
});

//# sourceMappingURL=post-like.js.map
