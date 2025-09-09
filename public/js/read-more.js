
document.addEventListener('DOMContentLoaded', () => {
  const wrappers = document.querySelectorAll('.description-wrapper');

  const measureAndToggle = (wrapper) => {
    const p   = wrapper.querySelector('.description');
    const btn = wrapper.querySelector('.read-more');
    if (!p || !btn) return;

    // 背景色を CSS 変数へ（カード背景に合わせたいときは data-bg を渡す）
    const bg = wrapper.dataset.bg || getComputedStyle(wrapper).backgroundColor || '#fff';
    wrapper.style.setProperty('--desc-bg', bg);

    // ボタン幅を実測して CSS 変数へ（重なり回避のため）
    // 一時的に表示して幅を測る
    const prevDisplay = btn.style.display;
    btn.style.display = 'inline-block';
    const btnWidth = btn.offsetWidth; // px
    btn.style.display = prevDisplay;
    wrapper.style.setProperty('--readmore-width', btnWidth + 'px');

    // “3行超え”判定（誤差吸収で -1）
    const clamped = p.scrollHeight - 1 > p.clientHeight;
    if (clamped) {
      wrapper.classList.add('has-readmore');
      // ボタンは .has-readmore の時だけ CSS で表示される
    } else {
      wrapper.classList.remove('has-readmore');
    }
  };

  wrappers.forEach(measureAndToggle);

  // リサイズ時も再計測（フォントサイズや幅で折返しが変わるため）
  let timer;
  window.addEventListener('resize', () => {
    clearTimeout(timer);
    timer = setTimeout(() => wrappers.forEach(measureAndToggle), 150);
  });
});

