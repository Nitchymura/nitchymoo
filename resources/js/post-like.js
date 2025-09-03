import { getCsrfToken } from './app';

document.addEventListener('click', async (e) => {
  const btn = e.target.closest('.js-like-btn');
  if (!btn) return;

  e.preventDefault();

  const url = btn.dataset.url;
  const counterEl = document.querySelector(btn.dataset.counter);
  const icon = btn.querySelector('i');

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

    if (counterEl && typeof json.like_count !== 'undefined') {
      counterEl.textContent = json.like_count;

      // アイコン切り替え
      if (json.like_count > 0) {
        icon.classList.remove('fa-regular', 'text-dark');
        icon.classList.add('fa-solid', 'text-danger');
      } else {
        icon.classList.remove('fa-solid', 'text-danger');
        icon.classList.add('fa-regular', 'text-dark');
      }
    }
  } catch (err) {
    console.error('Like toggle failed:', err);
  } finally {
    btn.disabled = false;
  }
});
