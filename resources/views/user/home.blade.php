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
                <div class="col-3 me-auto">
                    {{ $all_posts->links() }}
                </div>
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

            {{-- POSTS --}}
            


        {{-- <div class="col-4">
            <!-- USER INFO -->
            <div class="row mb-5 bg-white align-items-center shadow-sm rounded-2 py-3">
                <div class="col-auto">
                    <a href="{{ route('profile.show', Auth::user()->id) }}">
                        @if(Auth::user()->avatar)
                            <img src="{{ Auth::user()->avatar }}" alt="" class="rounded-circle avatar-md">
                        @else
                            <i class="fa-solid fa-circle-user text-secondary icon-md"></i>
                        @endif
                    </a>
                </div>
                <div class="col ps-0">
                    <a href="{{ route('profile.show', Auth::user()->id) }}" class="text-decoration-none text-dark fw-bold">{{ Auth::user()->name }}</a>
                    <p class="mb-0 text-secondary">{{ Auth::user()->email }}</p>
                </div>
            </div>

            <!-- SUGGESTED USERS -->
            @if($suggested_users)
                <div class="row mb-3">
                    <div class="col">
                        <h5 class="h6 mb-0 fw-bold text-secondary">Suggestions For You</h5>
                    </div>
                    <div class="col-auto">
                        <!-- See All -->
                        <a href="{{ route('all.suggested') }}" class="text-decoration-none fw-bold text-dark">See all</a>
                    </div>
                </div>
                @foreach($suggested_users as $user)
                    <div class="row mb-3 align-items-center">
                        <div class="col-auto">
                            <a href="{{ route('profile.show', $user->id) }}">
                            @if($user->avatar)
                                <img src="{{ $user->avatar }}" alt="" class="rounded-circle avatar-sm">
                            @else
                                <i class="fa-solid fa-circle-user text-secondary icon-sm"></i>
                            @endif
                            </a>
                        </div>
                        <div class="col ps-0 text-truncate">
                            <a href="{{ route('profile.show', $user->id) }}" class="text-decoration-none text-dark fw-bold">{{ $user->name }} </a>
                        </div>
                        <div class="col-auto">
                            <form action="{{ route('follow.store', $user->id) }}" method="post">
                                @csrf
                                <button type="submit" class="btn p-0 bg-transparent text-primary">Follow</button>
                            </form>
                        </div>
                    </div>
                @endforeach
            @endif
        </div> --}}

    </div>

@endsection