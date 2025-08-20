// resources/js/app.js
document.addEventListener('DOMContentLoaded', () => {
  document.querySelectorAll('.translate-btn').forEach(btn => {
    btn.addEventListener('click', async () => {
      const id = btn.dataset.id;
      try {
        const res = await fetch(`/posts/${id}/translate`);
        if (!res.ok) throw new Error(`HTTP ${res.status}`);
        const data = await res.json();
        document.getElementById(`translation-result-${id}`).innerText = data.translation;
      } catch (e) {
        console.error(e);
        document.getElementById(`translation-result-${id}`).innerText = '翻訳取得に失敗しました。';
      }
    });
  });
});
