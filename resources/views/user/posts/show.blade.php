@extends('layouts.app')

@section('title', 'Show Posts')

<link rel="stylesheet" href="{{ asset('css/carousel.css') }}">
@section('content')

    <style>
        .col-4.side{
            overflow-y: scroll;
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

    @php
        // null安全にコレクション化
        $bodies = collect($all_bodies ?? []);
    @endphp

    <div class="row border shadow mt-5 pt-3">
        <div class="col-12 col-md-8 p-0 border-end">
            @if ($bodies->isNotEmpty())
                {{-- 本文画像 + Body画像をスライドで表示 --}}
                <div id="postCarousel-{{ $post->id }}" class="carousel slide" data-bs-ride="carousel">
                    <div class="carousel-inner">

                        {{-- 1枚目はメイン画像を active で表示 --}}
                        <div class="carousel-item active">
                            <img src="{{ $post->image }}" alt="" class="w-100 post-photo" >
                        </div>

                        {{-- 以降、$all_bodies の画像をスライドとして追加 --}}
                        @foreach ($bodies as $body)
                            @if (!empty($body->photo))
                                <div class="carousel-item">
                                    <img src="{{ $body->photo }}" alt="" class="w-100 post-photo" >
                                </div>
                            @endif
                        @endforeach
                    </div>

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
