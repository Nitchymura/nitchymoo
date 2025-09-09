document.addEventListener('DOMContentLoaded', () => {
  const wrappers = document.querySelectorAll('.description-wrapper');

  const naturalLineCount = (el) => {
    const cs = getComputedStyle(el);
    const lineHeightPx = cs.lineHeight === 'normal'
      ? parseFloat(cs.fontSize) * 1.2
      : parseFloat(cs.lineHeight);
    // 計測用クローンを作成（画面に見えない場所で）
    const clone = el.cloneNode(true);
    clone.style.position = 'absolute';
    clone.style.visibility = 'hidden';
    clone.style.pointerEvents = 'none';
    clone.style.left = '-99999px';
    clone.style.top = '0';
    clone.style.webkitLineClamp = 'unset';     // ★ クランプ解除
    clone.style.display = 'block';
    clone.style.maxWidth = el.clientWidth + 'px';
    clone.style.width = el.clientWidth + 'px';
    document.body.appendChild(clone);
    const h = clone.scrollHeight;
    clone.remove();
    return Math.ceil(h / lineHeightPx);
  };

  const measureAndToggle = (wrapper) => {
    const p   = wrapper.querySelector('.description');
    const btn = wrapper.querySelector('.read-more');
    if (!p || !btn) return;

    // 背景色をCSS変数へ
    const bg = wrapper.dataset.bg || getComputedStyle(wrapper).backgroundColor || '#fff';
    wrapper.style.setProperty('--desc-bg', bg);

    // 行高を同期
    const lh = getComputedStyle(p).lineHeight;
    if (lh && lh !== 'normal') wrapper.style.setProperty('--line-h', lh);

    // 3行を超えるかを“自然行数”で判定
    const lines = naturalLineCount(p);
    const clamped = lines > 3;
    wrapper.classList.toggle('has-readmore', clamped);

    if (clamped) {
      // ボタン幅（実測）
      const prev = btn.style.display;
      btn.style.display = 'inline-block';
      const btnWidth = btn.offsetWidth;
      btn.style.display = prev;
      wrapper.style.setProperty('--readmore-width', btnWidth + 'px');

      // フェード幅（画面幅に応じて自動調整）
      const containerW = wrapper.clientWidth || 320;
      const min = 28;
      const max = Math.max(72, Math.floor(containerW * 0.28));
      const fadePx = Math.min(Math.max(btnWidth + 8, min), max);
      wrapper.style.setProperty('--fade-width', fadePx + 'px');
    } else {
      // 余白が残らないように初期化
      wrapper.style.removeProperty('--readmore-width');
      wrapper.style.removeProperty('--fade-width');
    }
  };

  const recheckAll = () => wrappers.forEach(measureAndToggle);

  // 初回
  recheckAll();

  // 完全ロード後・フォント後・リサイズ・遅延リトライ
  window.addEventListener('load', recheckAll);
  if (document.fonts && document.fonts.ready) document.fonts.ready.then(recheckAll);
  const ro = new ResizeObserver(() => recheckAll());
  wrappers.forEach(w => ro.observe(w));
  [250, 800, 1500].forEach(ms => setTimeout(recheckAll, ms));
});