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
                <p class="mt-3 mb-3 h5">{{ $user_intro }}</p>
            @endif

        <div class="col">
            <div class="row">
                <div class="col-3 ms-auto dropdown">
                {{-- 並び替えフォーム：現在URLにGETで投げ直す --}}
                <form method="GET" action="{{ route('home') }}">
                    @if(request('search'))
                        <input type="hidden" name="search" value="{{ request('search') }}">
                    @endif

                    <select name="sort" onchange="this.form.submit()" class="form-control mb-2">
                        <option value="" disabled {{ !in_array(request('sort'), ['latest','oldest']) ? 'selected' : '' }}>Sort</option>
                        <option value="latest" {{ request('sort','latest') === 'latest' ? 'selected' : '' }}>Newest first</option>
                        <option value="oldest" {{ request('sort') === 'oldest' ? 'selected' : '' }}>Oldest first</option>
                    </select>
                </form>
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
                            {{-- COMMENTS --}}
                            {{-- @if($post->comments->isNotEmpty())
                                <hr class="mt-3 mb-1">

                                @foreach($post->comments->take(3) as $comment)
                                    @include('user.posts.contents.comments.list-item')
                                @endforeach

                                @if($post->comments->count() > 3)
                                    <a href="{{ route('post.show', $post->id) }}" class="text-decoration-none small mt-2">View all {{ $post->comments->count() }} comments</a>
                                @endif
                            @endif --}}

                            {{-- @include('user.posts.contents.comments.create') --}}
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
            <div class="d-flex justify-content-end">
                {{ $all_posts->links() }}
            </div>
        </div>
    </div>

@endsection