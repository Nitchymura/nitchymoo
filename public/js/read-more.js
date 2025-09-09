document.addEventListener('DOMContentLoaded', () => {
  const wrappers = document.querySelectorAll('.description-wrapper');

  const measureAndToggle = (wrapper) => {
    const p   = wrapper.querySelector('.description');
    const btn = wrapper.querySelector('.read-more');
    if (!p || !btn) return;

    // 背景色（必要なら data-bg で渡す）
    const bg = wrapper.dataset.bg || getComputedStyle(wrapper).backgroundColor || '#fff';
    wrapper.style.setProperty('--desc-bg', bg);

    // 一旦表示して幅を測る
    const prev = btn.style.display;
    btn.style.display = 'inline-block';
    const btnWidth = btn.offsetWidth; // px
    btn.style.display = prev;

    // “3行超え”判定
    const clamped = p.scrollHeight - 1 > p.clientHeight;
    wrapper.classList.toggle('has-readmore', clamped);

    if (clamped) {
      // ボタン幅+少しの余裕を右側確保
      wrapper.style.setProperty('--readmore-width', btnWidth + 'px');

      // フェード幅を動的に決定：
      // - 最小 32px
      // - ボタン幅と同等以上
      // - コンテナ幅の 30% を上限
      const containerW = wrapper.clientWidth || 320;
      const min = 32;
      const max = Math.max(80, containerW * 0.30);
      const fadePx = Math.min(Math.max(btnWidth + 8, min), max);
      wrapper.style.setProperty('--fade-width', Math.round(fadePx) + 'px');
    }
  };

  wrappers.forEach(measureAndToggle);

  // リサイズ時も再計測
  let t;
  window.addEventListener('resize', () => {
    clearTimeout(t);
    t = setTimeout(() => wrappers.forEach(measureAndToggle), 150);
  });
});

