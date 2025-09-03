import { getCsrfToken } from './app';

// ユーザーの follow/unfollow
document.addEventListener('click', async (e) => {
  const btn = e.target.closest('.js-follow-btn');
  if (!btn) return;

  e.preventDefault();

  const url = btn.dataset.url; // 例: route('users.toggle-follow', userId)
  const label = btn.querySelector('.js-follow-label');

  btn.disabled = true;

  try {
    const res = await fetch(url, {
      method: 'POST',
      headers: {
        'X-CSRF-TOKEN': getCsrfToken(),
        'Accept': 'application/json',
      },
    });

    if (!res.ok) throw new Error(`HTTP ${res.status}`);
    const json = await res.json();

    // 期待: { following: true/false, followers_count: 12 }
    if (typeof json.following !== 'undefined' && label) {
      label.textContent = json.following ? 'Following' : 'Follow';
      btn.classList.toggle('btn-outline-primary', !json.following);
      btn.classList.toggle('btn-primary', json.following);
    }

    const counterSel = btn.dataset.counter; // 例: "#followers-count-42"
    if (counterSel && typeof json.followers_count !== 'undefined') {
      const counter = document.querySelector(counterSel);
      if (counter) counter.textContent = json.followers_count;
    }
  } catch (err) {
    console.error('Follow failed:', err);
  } finally {
    btn.disabled = false;
  }
});
