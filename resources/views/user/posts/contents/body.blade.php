<div class="row align-items-center mb-2">
<div class="col-auto d-flex align-items-center gap-3 post-actions">
    {{-- like/heart button --}}
    @auth
    <form action="{{ route('post.toggleLike', $post->id) }}" method="POST"
          data-post-id="{{ $post->id }}" class="like-post-form m-0">
        @csrf

        <button type="button"
                class="btn btn-sm p-0 d-inline-flex align-items-center gap-1 post-like-btn {{ request()->routeIs('posts.show','post.show') ? 'is-show' : 'is-home' }}"
                data-post-id="{{ $post->id }}">
            <i class="{{ $post->likes->where('user_id', Auth::id())->isNotEmpty() ? 'fa-solid text-danger' : 'fa-regular' }} fa-heart"></i>
            <span class="post-like-count" data-post-id="{{ $post->id }}">{{ $post->likes->count() }}</span>
        </button>

    </form>
    @else
    <button type="button" class="btn btn-sm p-0 d-inline-flex align-items-center gap-1"
            data-bs-toggle="modal" data-bs-target="#heart-icon">
        <i class="fa-regular fa-heart align-middle"></i>
        <span class="align-middle">{{ $post->likes->count() }}</span>
    </button>
    @include('user.posts.contents.modals.heart-icon')
    @endauth

    {{-- comments --}}
    @auth
    <button type="button" class="btn btn-sm p-0 d-inline-flex align-items-center gap-1"
            data-bs-toggle="modal" data-bs-target="#commentModal{{ $post->id }}">
        @if($post->comments->count() >= 1)
            <i class="fa-solid fa-comment text-info align-middle"></i>
        @else
            <i class="fa-regular fa-comment text-dark align-middle"></i>
        @endif
        <span class="align-middle">{{ $post->comments->count() }}</span>
    </button>
    @else
    <button class="btn btn-sm p-0 d-inline-flex align-items-center gap-1"
            data-bs-toggle="modal" data-bs-target="#comment-icon">
        <i class="fa-regular fa-comment text-dark align-middle"></i>
        <span class="align-middle">{{ $post->comments->count() }}</span>
    </button>
    @include('user.posts.contents.modals.comment-icon')
    @endauth
    @include('user.posts.contents.modals.comment')

    {{-- bodies --}}
    @if($post->postBodies->count() > 0)
    <div class="d-inline-flex align-items-center gap-1">
        <i class="fa-solid fa-photo-film text-secondary align-middle"></i>
        <span class="align-middle">{{ $post->postBodies->count() }}</span>
    </div>
    @endif
</div>

    
    <div class="col text-end">
        {{-- categories --}}
        @forelse($post->categoryPosts as $category_post)
            @php
            // 今表示中のカテゴリを $category と仮定
            $badgeClass = match($category_post->category_id) {
                1 => 'bg-success',
                2 => 'bg-primary',
                3 => 'bg-warning',
                4 => 'bg-danger',
                5 => 'bg-info',
                6 => 'bg-secondary',
                default => 'bg-white text-dark border border-dark',
            };
            @endphp  
                <div class="d-inline-block me-1"> 
                    <a href="{{ route('category.show', $category_post->category_id) }}" class="text-decoration-none badge badge-sm fs-6 {{ $badgeClass }} {{ $category_post->category_id }}" >{{ $category_post->category->name }}</a>                
                </div>
            
        @empty
            <div class="badge bg-dark">Uncategorized</div>
        @endforelse
    </div>
</div>

<!-- owner and description -->
{{-- <a href="{{ route('profile.show', $post->user->id) }}" class="text-decoration-none text-dark fw-bold">{{ $post->user->name}}</a> --}}
{{-- <br> --}}
@auth
    @if($post->likes->count()>0)
        <h3 type="button" class="fs-3" data-bs-toggle="modal" data-bs-target="#like-list{{ $post->id }}">
            {{ $post->title }}
        </h3> 
        @include('user.posts.contents.modals.like-list')
    @else
        <h3 class="fs-3">
            {{ $post->title }}
        </h3>
    @endif
    
@else
    <h3 class="fs-3">
        {{ $post->title }}
    </h3> 
@endauth

@if($post->subtitle)
    <p class="text-muted xsmall py-0">～{{ $post->subtitle }}～</p>
@endif

@if($post->city && $post->country)
    <div class="d-flex align-items-center text-muted ">
        <i class="fa-solid fa-location-dot me-1"></i>
        <span>{{ $post->city }} / {{ $post->country }}</span>
    </div>
@elseif($post->city)
    <div class="d-flex align-items-center text-muted ">
        <i class="fa-solid fa-location-dot me-1"></i>
        <span>{{ $post->city }} / ---</span>
    </div>
@elseif($post->country)
        <div class="d-flex align-items-center text-muted ">
        <i class="fa-solid fa-location-dot me-1"></i>
        <span>--- / {{ $post->country }}</span>
    </div>
@else
    <div class="d-flex align-items-center text-muted">
        <i class="fa-solid fa-location-dot me-1"></i>
        <span class="text-uppercase">---</span>
    </div>
@endif

@if($post->term_start && $post->term_end && $post->term_start != $post->term_end)
    <p class="text-muted xsmall">{{ date('M d, Y', strtotime($post->term_start))}} ~ {{ date('M d, Y', strtotime($post->term_end))}}</p>
@elseif($post->term_start)
    <p class="text-muted xsmall">{{ date('M d, Y', strtotime($post->term_start))}}</p>
@else 
    <p>---</p>
@endif
@php
    $isShow = request()->routeIs('post.show');
    $hasJa  = filled($post->translation ?? null);
    $linkDescription = $linkDescription ?? !$isShow;  // 既存の方針を継承
@endphp

{{-- 本文（初期表示は英語 description） --}}
@if($linkDescription)
  <a href="{{ route('post.show', $post->id) }}" class="text-decoration-none text-dark">
    <p id="post-text-{{ $post->id }}" class="fw-light {{ ($noClamp ?? false) ? '' : 'description' }}">
      {{ $post->description }}
    </p>
  </a>
@else
  <p id="post-text-{{ $post->id }}" class="fw-light {{ ($noClamp ?? false) ? '' : 'description' }}">
    {{ $post->description }}
  </p>
@endif

{{-- Japanese/English トグル（showページ かつ translation がある時のみ） --}}
@if($isShow && $hasJa)
  <button
    class="btn btn-sm btn-outline-primary mt-2 lang-toggle-btn"
    data-id="{{ $post->id }}"
    data-state="en"  {{-- 現在表示中の言語 --}}
    data-en="{{ e($post->description) }}"
    data-ja="{{ e($post->translation) }}"
    aria-pressed="false"
    aria-controls="post-text-{{ $post->id }}">
    Japanese
  </button>

  <script>
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
  </script>
@endif

{{-- @if($showTranslate)
  <button
    class="btn btn-sm btn-outline-primary mt-2 translate-btn"
    data-id="{{ $post->id }}"
    data-url="{{ route('posts.translate', ['post' => $post->id]) }}">
    翻訳する
  </button>

  <div id="translation-result-{{ $post->id }}" class="mt-2 text-muted"></div>

  <script>
  document.addEventListener('DOMContentLoaded', () => {
    document.querySelectorAll('.translate-btn').forEach(btn => {
      if (btn.dataset.bound) return; // 二重バインド防止
      btn.dataset.bound = "1";

      btn.addEventListener('click', async () => {
        const id  = btn.dataset.id;
        const url = btn.dataset.url;
        const box = document.getElementById(`translation-result-${id}`);

        const original = btn.innerHTML;
        btn.disabled = true; btn.innerHTML = '翻訳中…';

        try {
          const res = await fetch(url, {
            headers: { 'Accept': 'application/json' },
            credentials: 'same-origin',
            cache: 'no-store'
          });
          const text = await res.text();
          let data = {};
          try { data = JSON.parse(text); } catch {}

          if (!res.ok) throw new Error(`HTTP ${res.status} | ${text}`);

          box.innerText = data.translation || '翻訳が空でした。';
        } catch (e) {
          console.error(e);
          box.innerText = '翻訳取得に失敗しました。';
        } finally {
          btn.disabled = false; btn.innerHTML = original;
        }
      });
    });
  });
  </script>
@endif --}}
