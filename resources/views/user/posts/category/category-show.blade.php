@extends('layouts.app')

@section('title', 'Category')

@section('content')

<div class="row mt-5 pt-5 ms-1" >
    <div class="d-flex gap-1 align-items-end mb-4 pb-2">
        @foreach($all_categories as $cat)
            @php
                // カラーをカテゴリごとに切り替える
                $badgeClass = match($cat->id) {
                    1 => 'bg-success',
                    2 => 'bg-primary',
                    3 => 'bg-warning',
                    4 => 'bg-danger',
                    5 => 'bg-info',
                    default => 'bg-secondary',
                };
            @endphp

            <span class="top-badge badge {{ $badgeClass }} 
                {{ $category->id == $cat->id ? '' : 'bg-opacity-10 ' }}" >
                <a href="{{ route('category.show', $cat->id) }}" class="text-decoration-none text-white">{{ $cat->name }}</a>
            </span>
        @endforeach
    </div>      
</div>


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