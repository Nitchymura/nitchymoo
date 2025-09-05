@extends('layouts.app')

@section('title', 'Profile')

@section('content')
    @include('user.profiles.header')

    <div class="row ">
        @forelse($user->posts->sortByDesc('term_start') as $post)
            <div class="col-lg-4 col-md-6 mb-4">
                {{-- <a href="{{ route('post.show', $post->id) }}" > --}}
                    {{-- <img src="{{ $post->image }}" alt="" class="grid-img"> --}}
                {{-- </a> --}}
                {{ $post->title }}
            </div>
        @empty
            <h4 class="h5 text-center text-secondary">No posts yet.</h4>
        @endforelse
    </div>
@endsection