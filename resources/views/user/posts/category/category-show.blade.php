@extends('layouts.app')

@section('title', 'Category')

@section('content')

<div class="row mt-5 pt-5 ms-0">
  <div class="col-auto d-flex p-0 align-items-end mb-4 pb-2">
    @php
      // 今表示中のカテゴリを $category と仮定
      $badgeClass = match($category->id) {
          1 => 'bg-success',
          2 => 'bg-info',
          3 => 'bg-warning',
          4 => 'bg-danger',
          5 => 'bg-primary',
          6 => 'bg-secondary',
          default => 'bg-white text-dark border border-dark',
      };
    @endphp
    <span class="top-badge badge fs-5 {{ $badgeClass }} {{ $category->id }}">
      {{ $category->name }}
    </span>
  </div>

  <div class="col">
    {{-- <label for="jump-category" class="form-label mb-1 small text-muted"></label> --}}
    <select id="jump-category" class="form-select form-select-sm">
    <option value="" hidden>-- Select other category --</option>
    @foreach($all_categories as $cat)
        <option value="{{ route('category.show', $cat->id) }}"
                {{ $category->id === $cat->id ? 'hidden' : '' }}>
        {{ $cat->name }}
        </option>
    @endforeach
    </select>
    <noscript>
      {{-- JS無効時のフォールバック（選択後に押すボタン）--}}
      <button type="button" class="btn btn-sm btn-outline-secondary mt-2"
              onclick="var s=document.getElementById('jump-category'); if(s.value) location.href=s.value;">
        移動
      </button>
    </noscript>
  </div>
</div>

@push('scripts')
<script>
  // 選択したら即ジャンプ
  document.addEventListener('change', function (e) {
    const sel = e.target.closest('#jump-category');
    if (!sel) return;
    if (sel.value) window.location.href = sel.value;
  });
</script>
@endpush



    <div class="row gx-5">
    @forelse($all_posts as $post)
        <div class="col-lg-4 col-md-6 col-sm-12 px-2">
            <div class="card mb-4">
                <!-- title -->
                @include('user.posts.contents.title')
                <!-- image -->
                <div class="container p-0">
                    <a href="{{route('post.show', $post->id)}}">
                        @if($post->image)
                            <img src="{{$post->image}}" alt="" class="w-100" style="height: 250px; object-fit: cover">
                        @else
                            <i class="fa-solid fa-image fa-5x text-center"></i>
                        @endif
                    </a>
                </div>
                <!-- body -->
                <div class="card-body">
                    @include('user.posts.contents.body')
                    <!-- COMMENTS -->
                    {{-- @if($post->comments->isNotEmpty())
                        <hr class="mt-3 mb-1">
                        @foreach($post->comments->take(3) as $comment)
                            @include('user.posts.category.contents.comments.list-item')
                        @endforeach
                        @if($post->comments->count() > 3)
                            <a href="{{ route('post.show', $post->id) }}" class="text-decoration-none small mt-2">
                                View all {{ $post->comments->count() }} comments
                            </a>
                        @endif
                    @endif --}}
                    {{-- @include('user.posts.category.contents.comments.create') --}}
                </div>
            </div>
        </div>
    @empty
        <h4 class="h5 text-start text-secondary">No posts in this category.</h4>
    @endforelse
    </div>
                <div class="d-flex justify-content-end">
                {{ $all_posts->links() }}
            </div>


@endsection