<div class="row align-items-center mb-2">
    <div class="col-auto ">
        {{-- like/heart button --}}
        @auth
            <form action="{{ route('post.toggleLike', $post->id) }}"
                method="POST"
                data-post-id="{{ $post->id }}"
                class="like-post-form">
                @csrf
                <button type="button" class="btn btn-sm shadow-none post-like-btn">
                    <i class="{{ $post->likes->where('user_id', Auth::id())->isNotEmpty() ? 'fa-solid text-danger' : 'fa-regular' }} fa-heart"></i>
                    
                    &nbsp;<span class="post-like-count" data-post-id="{{ $post->id }}">{{ $post->likes->count() }}</span>
                </button>
            </form>

        @else
            <button type="sumbit" class="btn p-0" data-bs-toggle="modal" data-bs-target="#heart-icon">
                <i class="fa-regular fa-heart"></i>&nbsp;  {{ $post->likes->count() }}
            </button> 
            @include('user.posts.contents.modals.heart-icon')  
        @endauth
    </div>

    <div class="col-auto">
        @auth
            @if($post->comments->count() >= 1)
                <button type="button" class="btn btn-sm p-0" data-bs-toggle="modal" data-bs-target="#commentModal{{ $post->id }}">
                    <i class="fa-solid fa-comment text-info"></i>&nbsp; {{ $post->comments->count() }}
                </button>
            @else
                <button type="button" class="btn btn-sm p-0" data-bs-toggle="modal" data-bs-target="#commentModal{{ $post->id }}">
                    <i class="fa-regular fa-comment text-dark"></i>&nbsp; {{ $post->comments->count() }}
                </button>
            @endif
        @else       
            <button class="btn btn-sm p-0" data-bs-toggle="modal" data-bs-target="#comment-icon">
                <i class="fa-regular fa-comment text-dark"></i>&nbsp; {{ $post->comments->count() }}
            </button>
            @include('user.posts.contents.modals.comment-icon') 
        @endauth
        @include('user.posts.contents.modals.comment')  
    </div>
    {{-- <div class="col-auto px-0 me-1">
        {{ $post->comments->count() }}
    </div> --}}

    @if($post->postBodies->count() > 0)
    <div class="col-auto">
        <div class="col-auto"> 
        <i class="fa-solid fa-photo-film text-secondary"></i>&nbsp; {{ $post->postBodies->count() }}
        </div>
    </div>        
    @endif
    
    <div class="col text-end">
        {{-- categories --}}
        @forelse($post->categoryPosts as $category_post)
            @if($category_post->category_id == 1)
                <div class="badge bg-success bg-opacity-30">
            @elseif($category_post->category_id == 2)
                <div class="badge bg-primary bg-opacity-30">
            @elseif($category_post->category_id == 3)
                <div class="badge bg-warning bg-opacity-30">                
            @elseif($category_post->category_id == 4)
                <div class="badge bg-danger bg-opacity-30">
            @elseif($category_post->category_id == 5)
                <div class="badge bg-info bg-opacity-30">
            @else
                <div class="badge bg-secondary bg-opacity-30">
            @endif    
                    <a href="{{ route('category.show', $category_post->category_id) }}" class="text-decoration-none text-white " >{{ $category_post->category->name }}</a>                
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
    // いまのルートが post.show なら true
    $isShow = request()->routeIs('post.show');

    // 明示指定が無ければ、以下をデフォルトにする
    // showページ： 翻訳ボタン=表示 / 説明リンク=無効
    // homeページ： 翻訳ボタン=非表示 / 説明リンク=有効
    $showTranslate   = $showTranslate   ?? $isShow;
    $linkDescription = $linkDescription ?? !$isShow;
@endphp

@if($linkDescription)
  <a href="{{ route('post.show', $post->id) }}" class="text-decoration-none text-dark">
    <p class="fw-light {{ ($noClamp ?? false) ? '' : 'description' }}">
      {{ $post->description }}
    </p>
  </a>
@else
  <p class="fw-light {{ ($noClamp ?? false) ? '' : 'description' }}">
    {{ $post->description }}
  </p>
@endif

@if($showTranslate)
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
@endif
