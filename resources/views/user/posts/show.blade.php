@extends('layouts.app')

@section('title', 'Show Posts')

<link rel="stylesheet" href="{{ asset('css/carousel.css') }}">
@section('content')

    <style>
        /* .col-4.side{
            overflow-y: scroll;
        } */
        .side{
            overflow-y: scroll;
            height: 600px
        }
        .card-body{
            position: absolute;
            top:65px;
        }
        .fa-heart{
            margin-top: 12px;
        }
        .description{

    overflow: hidden;

}

    </style>

    @php // null安全にコレクション化 
    $bodies = collect($all_bodies ?? []); 
    @endphp 
    
    <div class="row" style="height: 70px">
        </div> 

<div class="d-flex justify-content-between mb-1">
    @if($previousPost)
        <a href="{{ route('post.show', $previousPost->id) }}" class="btn btn-outline-dark btn-sm">
            <i class="fa-solid fa-angles-left"></i> 
        </a>
    @else
        <span></span>
    @endif

    @if($nextPost)
        <a href="{{ route('post.show', $nextPost->id) }}" class="btn btn-outline-dark btn-sm">
            <i class="fa-solid fa-angles-right"></i>
        </a>
    @else
        <span></span>
    @endif
</div>


    <div class="row border shadow "> 
        <div class="col-12 col-md-8 p-0 border-end"> 
            @if ($bodies->isNotEmpty()) {{-- 本文画像 + Body画像をスライドで表示 --}} 
            <div id="postCarousel-{{ $post->id }}" class="carousel slide" data-bs-ride="carousel">  
                <div class="carousel-inner"> {{-- 1枚目はメイン画像を active で表示 --}} 
                    <div class="carousel-item active"> 
                        <div class="post-photo-container"> 
                            <img src="{{ $post->image }}" alt="" class="post-photo"> 
                        </div> 
                    </div> 
                    @foreach ($bodies as $body) 
                        @if (!empty($body->photo)) 
                            <div class="carousel-item"> 
                                <div class="post-photo-container"> 
                                    <img src="{{ $body->photo }}" alt="" class="post-photo"> 
                                </div> 
                            </div> 
                        @endif 
                    @endforeach 
                </div> 
            <script>
            (function () {
            const carousel = document.getElementById('postCarousel-{{ $post->id }}');
            if (!carousel) return;

            const center = (container) => {
                if (!container) return;
                container.scrollTop = (container.scrollHeight - container.clientHeight) / 2;
            };

            // 1) 画像が読み込まれたら、そのスライドを中央に
            carousel.querySelectorAll('.post-photo').forEach(img => {
                const container = img.closest('.post-photo-container');
                if (img.complete) {
                center(container);
                } else {
                img.addEventListener('load', () => center(container));
                }
            });

            // 2) 初期表示の active スライドも一応再センタリング（描画後）
            const activeContainer = carousel.querySelector('.carousel-item.active .post-photo-container');
            setTimeout(() => center(activeContainer), 0);

            // 3) スライド切替後にも中央へ
            carousel.addEventListener('slid.bs.carousel', (e) => {
                const shownItem = e.relatedTarget; // 今表示された .carousel-item
                const container = shownItem.querySelector('.post-photo-container');
                // 画像の高さが確定していない可能性があるので少し後で
                setTimeout(() => center(container), 0);
            });
            })();
            </script>



                    {{-- 前後ボタン --}}
                    <button class="carousel-control-prev" type="button" data-bs-target="#postCarousel-{{ $post->id }}" data-bs-slide="prev">
                        <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                        <span class="visually-hidden">Previous</span>
                    </button>
                    <button class="carousel-control-next" type="button" data-bs-target="#postCarousel-{{ $post->id }}" data-bs-slide="next">
                        <span class="carousel-control-next-icon" aria-hidden="true"></span>
                        <span class="visually-hidden">Next</span>
                    </button>
                </div>
            @else
                {{-- $all_bodies が空なら従来どおり1枚表示 --}}
                <img src="{{ $post->image }}" alt="" class="w-100">
            @endif
        </div>

        <div class="col-12 col-md-4 px-0 bg-white side">
            <div class="card border-0">
                @include('user.posts.contents.title')
                <div class="card-body w-100 bg-white post-body">
                    @include('user.posts.contents.body', ['noClamp' => true])
                    
                    {{-- COMMENTS --}}
                    @include('user.posts.contents.comments.create')

                    {{-- List --}}
                    @foreach($post->comments as $comment)
                        @include('user.posts.contents.comments.list-item')
                    @endforeach
                </div>
            </div>
        </div>
    </div>

@endsection
