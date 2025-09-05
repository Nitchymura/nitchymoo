@extends('layouts.app')

@section('title', 'Home')

@section('content')
    <div class="row mt-5">        
            @if($search)
                <h3 class="h4 text-muted mb-4">Search results for '<span class="fw-bold">{{ $search }}</span>'</h3>
            @else
                <video autoplay muted loop playsinline class="header_video px-0 w-100">
                    <source src="{{ asset('videos/intro.mp4') }}" type="video/mp4">
                </video>
                <p class="mt-3 mb-5 h5">{{ $user_intro }}</p>
            @endif

        <div class="col">
            <div class="row">
                <div class="col-auto me-auto text-secondary">
                    {{ $all_posts->links() }}
                </div>              
            </div>
            <div class="row">
                @forelse($all_posts as $post)
                <div class="col-lg-4 col-md-6 col-sm-12 px-2">
                    <div class="card mb-4">
                        {{-- title --}}
                        @include('user.posts.contents.title')
                        {{-- image --}}
                        <div class="container p-0">
                            <a href="{{ route('post.show', $post->id) }}">
                                <img src="{{ $post->image }}" alt="" class="w-100" style="height: 250px; object-fit: cover">
                            </a>
                        </div>
                        {{-- body --}}
                        <div class="card-body">
                            @include('user.posts.contents.body')
                        </div>
                    </div>
                </div>
                @empty
                    @if(!empty($search))
                        <p class="text-center text-muted">Nothing found.</p>
                    @else
                        <div class="text-center mt-5">
                            <h2>Share Photos</h2>
                            <p class="text-muted">When you share photos, they'll appear on your profile.</p>
                            <a href="{{ route('post.create') }}" class="text-decoration-none">Share your first photo</a>
                        </div>
                    @endif
                @endforelse
            </div>
            <div class="d-flex me-auto">
                {{ $all_posts->links() }}
            </div>
        </div>

            
    <button id="scrollTopBtn" title="Go to top" class="col-auto btn btn-primary">
        <i class="fa-solid fa-angles-up"></i>
    </button>
    <script>
        const scrollTopBtn = document.getElementById("scrollTopBtn");

        // スクロール量を監視
        window.onscroll = function() { toggleScrollButton() };

        function toggleScrollButton() {
            // 200px 以上スクロールしたら表示
            if (document.body.scrollTop > 200 || document.documentElement.scrollTop > 200) {
                scrollTopBtn.style.display = "block";
            } else {
                scrollTopBtn.style.display = "none";
            }
        }

        // ボタンクリックでトップにスクロール
        scrollTopBtn.addEventListener("click", function() {
            window.scrollTo({ top: 0, behavior: 'smooth' });
        });
    </script>

    </div>

@endsection