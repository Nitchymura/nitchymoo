document.addEventListener('DOMContentLoaded', () => {
  const wrappers = document.querySelectorAll('.description-wrapper');

  const measureAndToggle = (wrapper) => {
    const p   = wrapper.querySelector('.description');
    const btn = wrapper.querySelector('.read-more');
    if (!p || !btn) return;

    // 背景色をCSS変数へ
    const bg = wrapper.dataset.bg || getComputedStyle(wrapper).backgroundColor || '#fff';
    wrapper.style.setProperty('--desc-bg', bg);

    // 行高もCSSから取得して同期（数値のズレ防止）
    const lh = getComputedStyle(p).lineHeight;
    if (lh && lh !== 'normal') wrapper.style.setProperty('--line-h', lh);

    // ボタン幅を実測してCSS変数へ（重なり回避）
    const prev = btn.style.display;
    btn.style.display = 'inline-block';
    const btnWidth = btn.offsetWidth;
    btn.style.display = prev;
    wrapper.style.setProperty('--readmore-width', btnWidth + 'px');

    // 折り畳み判定（誤差吸収）
    const clamped = p.scrollHeight - 1 > p.clientHeight;

    wrapper.classList.toggle('has-readmore', clamped);

    if (clamped) {
      // フェード幅を動的に（スマホで広くなりすぎない）
      const containerW = wrapper.clientWidth || 320;
      const min = 28;                                   // 最小
      const max = Math.max(72, Math.floor(containerW * 0.28)); // 上限: 幅の28%
      const fadePx = Math.min(Math.max(btnWidth + 8, min), max);
      wrapper.style.setProperty('--fade-width', fadePx + 'px');
    } else {
      // 不要な余白/フェードを抑制するため初期値に戻す（任意）
      wrapper.style.removeProperty('--fade-width');
      wrapper.style.removeProperty('--readmore-width');
    }
  };

  const recheckAll = () => wrappers.forEach(measureAndToggle);

  // 初回
  recheckAll();

  // 1) ページ完全読み込み後（画像・スタイル確定後）
  window.addEventListener('load', recheckAll);

  // 2) Webフォント読み込み後（行折返しが変わる）
  if (document.fonts && document.fonts.ready) {
    document.fonts.ready.then(recheckAll).catch(()=>{});
  }

  // 3) サイズ変化監視（レスポンシブ・タブ切替等）
  const ro = new ResizeObserver(() => recheckAll());
  wrappers.forEach(w => ro.observe(w));

  // 4) 初期安定化のため数回追いリトライ（SPAや遅延要素対策）
  [250, 800, 1500].forEach(ms => setTimeout(recheckAll, ms));
});