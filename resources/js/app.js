import 'bootstrap';

// （任意）axios を使う場合
import axios from 'axios';

// CSRF 設定（fetchでも使えるように取得関数を用意）
export function getCsrfToken() {
  const el = document.querySelector('meta[name="csrf-token"]');
  return el ? el.getAttribute('content') : '';
}

// axios にCSRFを適用（使う場合）
axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';
axios.defaults.headers.common['X-CSRF-TOKEN'] = getCsrfToken();

// 独自機能をまとめて import
import './post-like';
import './follow';

