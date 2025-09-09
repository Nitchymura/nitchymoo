// public/js/post-like.js
document.addEventListener('click', async function (event) {
  const button = event.target.closest('.like-button');
  if (!button) return;

  // デバッグ：ここが出なければJSが読まれていません
  // console.log('like-button clicked');

  const postId    = button.getAttribute('data-id');
  const toggleUrl = button.getAttribute('data-url') || `/posts/${postId}/toggle-like`;
  const csrfMeta  = document.querySelector('meta[name="csrf-token"]');
  const csrfToken = csrfMeta ? csrfMeta.getAttribute('content') : '';

  const wasLiked = button.getAttribute('data-liked') === '1';

  const iconHtml = (liked) =>
    liked
      ? '<i class="fa-solid fa-heart text-danger"></i>'
      : '<i class="fa-regular fa-heart text-dark"></i>';

  const updateAllIcons = (liked) => {
    document.querySelectorAll(`.like-button[data-id="${postId}"] .like-icon`)
      .forEach(c => { c.innerHTML = iconHtml(liked); });
  };
  const updateAllCounts = (count) => {
    document.querySelectorAll(`.like-count[data-id="${postId}"]`)
      .forEach(el => { el.textContent = count; });
  };

  const currentCountEl = document.querySelector(`.like-count[data-id="${postId}"]`);
  const countNow = currentCountEl ? (parseInt(currentCountEl.textContent, 10) || 0) : 0;
  const optimistic = wasLiked ? Math.max(0, countNow - 1) : countNow + 1;

  if (button.dataset.busy === '1') return;
  button.dataset.busy = '1';

  // 楽観的UI
  updateAllIcons(!wasLiked);
  updateAllCounts(optimistic);
  document.querySelectorAll(`.like-button[data-id="${postId}"]`)
    .forEach(btn => btn.setAttribute('data-liked', !wasLiked ? '1' : '0'));

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
      console.error('Like toggle failed:', await res.text());
      return;
    }

    const data = await res.json(); // { liked, like_count } を想定
    if (typeof data.liked !== 'undefined') {
      updateAllIcons(!!data.liked);
      document.querySelectorAll(`.like-button[data-id="${postId}"]`)
        .forEach(btn => btn.setAttribute('data-liked', data.liked ? '1' : '0'));
    }
    if (typeof data.like_count !== 'undefined') {
      updateAllCounts(parseInt(data.like_count, 10));
    }
  } catch (err) {
    updateAllIcons(wasLiked);
    updateAllCounts(countNow);
    document.querySelectorAll(`.like-button[data-id="${postId}"]`)
      .forEach(btn => btn.setAttribute('data-liked', wasLiked ? '1' : '0'));
    console.error('AJAX Error:', err);
  } finally {
    delete button.dataset.busy;
  }
});
