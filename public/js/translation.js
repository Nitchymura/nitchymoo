document.addEventListener('DOMContentLoaded', () => {
    // 同一ページで複数ボタンがあってもOK
    document.querySelectorAll('.lang-toggle-btn').forEach(btn => {
      if (btn.dataset.bound) return;  // 二重バインド防止
      btn.dataset.bound = "1";

      btn.addEventListener('click', () => {
        const id   = btn.dataset.id;
        const node = document.getElementById(`post-text-${id}`);
        if (!node) return;

        const state = btn.dataset.state; // 'en' or 'ja'
        if (state === 'en') {
          // 英語 -> 日本語へ
          node.textContent = btn.dataset.ja || '';
          btn.dataset.state = 'ja';
          btn.textContent = 'English';
          btn.setAttribute('aria-pressed', 'true');
        } else {
          // 日本語 -> 英語へ
          node.textContent = btn.dataset.en || '';
          btn.dataset.state = 'en';
          btn.textContent = 'Japanese';
          btn.setAttribute('aria-pressed', 'false');
        }
      });
    });
  });