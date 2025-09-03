@extends('layouts.app')

@section('title', 'Show Posts')

<link rel="stylesheet" href="{{ asset('css/carousel.css') }}">
  <!-- slick -->
  <link rel="stylesheet" href="{{ asset('slick/slick.css') }}">
  <link rel="stylesheet" href="{{ asset('slick/slick-theme.css') }}">
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

    <div class="d-flex justify-content-between mb-3">
        {{-- 右側: Next / Last --}}
        <div class="d-flex gap-1">
            {{-- 一番最新へ --}}
            @if($latestPost && $latestPost->id !== $post->id)
                <a href="{{ route('post.show', $latestPost->id) }}" class="btn btn-outline-primary">
                    <i class="fa-solid fa-angle-double-left"></i>
                </a>
            @endif             
            {{-- 前のポスト --}}
            @if($nextPost)
                <a href="{{ route('post.show', $nextPost->id) }}" class="btn btn-outline-secondary">
                    <i class="fa-solid fa-angle-left"></i>
                </a>
            @endif{{-- 一番古いへ --}}           
            


        </div>
        {{-- 左側: First / Prev --}}
        <div class="d-flex gap-1">
            {{-- 次のポスト --}}
            @if($previousPost)
                <a href="{{ route('post.show', $previousPost->id) }}" class="btn btn-outline-secondary">
                    <i class="fa-solid fa-angle-right"></i>
                </a>
            @endif
            @if($oldestPost && $oldestPost->id !== $post->id)
                <a href="{{ route('post.show', $oldestPost->id) }}" class="btn btn-outline-primary">
                    <i class="fa-solid fa-angle-double-right"></i>
                </a>
            @endif
        </div>


    </div>



    <div class="row border shadow "> 
        <div class="col-12 col-md-8 p-0 border-end">
            <div class="post-slider" id="postSlider-{{ $post->id }}">
                <div class="post-photo-container">
                    <img src="{{ $post->image }}" class="post-photo" alt="">
                </div>

            @foreach ($bodies as $body)
                @if (!empty($body->photo))
                <div class="post-photo-container">
                    <img src="{{ $body->photo }}" class="post-photo" alt="">
                </div>
                @endif
            @endforeach
            </div>
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
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
<script src="{{ asset('slick/slick.min.js') }}"></script>
<script>
    $('.post-slider').slick({
    dots: true,
    arrows: true,      // 前後矢印も出すなら
    autoplay: false
    });
</script>

@endsection
