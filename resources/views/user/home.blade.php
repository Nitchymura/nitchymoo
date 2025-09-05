@extends('layouts.app')

@section('title', 'Home')

@push('head')
<link rel="stylesheet" href="{{ asset('slick/slick.css') }}">
<link rel="stylesheet" href="{{ asset('slick/slick-theme.css') }}">
<style>
/* 画像ブロック周り：上下マージンを完全ゼロに */
.post-media{ margin:0; padding:0; }
.post-card-link{ display:block; margin:0; padding:0; line-height:0; font-size:0; } /* ベースライン隙間を殺す */

/* スライダー本体：常に余白ゼロ、ドット重ね用の土台 */
.post-slider{ position:relative; margin:0 !important; padding:0 !important; }
.post-slider.slick-slider,
.post-slider.slick-dotted.slick-slider{ margin-top:0 !important; margin-bottom:0 !important; }

/* 画像エリア（カード用固定高さ） */
.post-photo-container.--card{ height:250px; overflow:hidden; position:relative; margin:0 !important; }
.post-photo{ width:100%; height:100%; object-fit:cover; object-position:center; display:block; }

/* Slick 内部もゼロに寄せる */
.post-slider .slick-list,
.post-slider .slick-track,
.post-slider .slick-slide{ margin:0 !important; padding:0 !important; }

/* ドット = 画像の中の下端に“重ねる”（高さを食わせない） */
.post-slider .slick-dots{
  position:absolute !important;
  left:0; right:0; bottom:-18px !important;
  margin:0 !important; padding:0;
}
.post-slider .slick-dots li{ margin:0 4px; }
.post-slider .slick-dots li button:before{ font-size:5px; opacity:.4; }
.post-slider .slick-dots li.slick-active button:before{ opacity:.9; }

/* モバイル高さ */
/* @media (max-width: 768px){
  .post-photo-container.--card{ height:200px; }
} */

/* FOUC対策 */
.js-slick{ visibility:hidden; }
.js-slick.slick-initialized{ visibility:visible; }
</style>
@endpush



@section('content')
    <div class="row" style="height: 5px">
    </div> 
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
                        {{-- 画像ブロック --}}
                        <div class="post-media">
                        <a href="{{ route('post.show', $post->id) }}" class="text-decoration-none d-block post-card-link">
                            <div class="post-slider js-slick" data-post-id="{{ $post->id }}">
                            <div class="post-photo-container --card">
                                <img src="{{ $post->image }}" class="post-photo" alt="">
                            </div>
                            @foreach($post->postBodies ?? [] as $body)
                                @if(!empty($body->photo))
                                <div class="post-photo-container --card">
                                    <img src="{{ $body->photo }}" class="post-photo" alt="">
                                </div>
                                @endif
                            @endforeach
                            </div>
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

<script src="{{ asset('slick/slick.min.js') }}"></script>
<script>
$(function(){
  $('.js-slick').each(function(){
    const $slider = $(this);
    const count = $slider.children().length;

    $slider.on('init', function(){
      $slider.css('visibility','visible');
    }).slick({
      dots: true,            // ★ 1枚でもドット表示
      arrows: count > 1,     // 1枚時は矢印OFF（必要なら true）
      infinite: count > 1,
      slidesToShow: 1,
      slidesToScroll: 1,
      autoplay: false,
      adaptiveHeight: false, // 高さはCSS固定
      lazyLoad: 'ondemand'
    });
  });
});
</script>

@endsection