<div class="row align-items-center">
    <div class="col-auto">
        {{-- like/heart button --}}
        @auth
            {{-- @if($post->isLiked())
                <!-- red heart/unlike -->
                <form action="{{route('like.delete', $post->id)}}" method="post">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn p-0">
                        <i class="fa-solid fa-heart text-danger"></i>&nbsp; {{ $post->likes->count() }}
                    </button>
                </form>
            @else
                @auth
                <form action="{{route('like.store', $post->id)}}" method="post">
                    @csrf
                    <button type="sumbit" class="btn p-0">
                        <i class="fa-regular fa-heart"></i>&nbsp;  {{ $post->likes->count() }}
                    </button>
                </form>
                @endauth
            @endif --}}

            <form action="{{ route('post.toggleLike', $post->id) }}"
                method="POST"
                data-post-id="{{ $post->id }}"
                class="like-post-form">
                @csrf
                <button type="button" class="btn btn-sm shadow-none post-like-btn">
                    <i class="{{ $post->likes->where('user_id', Auth::id())->isNotEmpty() ? 'fa-solid text-danger' : 'fa-regular' }} fa-heart"></i>
                    &nbsp;<span class="post-like-count" data-post-id="{{ $post->id }}">{{ $post->likes->count() }}</span>
                </button>
            </form>

        @else
            <button type="sumbit" class="btn p-0" data-bs-toggle="modal" data-bs-target="#heart-icon">
                <i class="fa-regular fa-heart"></i>&nbsp;  {{ $post->likes->count() }}
            </button> 
            @include('user.posts.contents.modals.heart-icon')  
        @endauth
    </div>

    <div class="col-auto">
        @if($post->comments->count() >= 1)
            <i class="fa-solid fa-comment text-info"></i>&nbsp;  {{ $post->comments->count() }}
        @else
            <i class="fa-regular fa-comment text-dark"></i>&nbsp;  {{ $post->comments->count() }}
        @endif
    </div>
    {{-- <div class="col-auto px-0 me-1">
        {{ $post->comments->count() }}
    </div> --}}

    @if($post->postBodies->count() > 0)
    <div class="col-auto">
        <i class="fa-solid fa-photo-film text-secondary"></i>&nbsp;  {{ $post->postBodies->count() }}
    </div>
        
    @endif
    <div class="col text-end">
        {{-- categories --}}
        @forelse($post->categoryPosts as $category_post)
            @if($category_post->category_id == 1)
                <div class="badge bg-success bg-opacity-30">
            @elseif($category_post->category_id == 2)
                <div class="badge bg-primary bg-opacity-30">
            @elseif($category_post->category_id == 3)
                <div class="badge bg-warning bg-opacity-30">                
            @elseif($category_post->category_id == 4)
                <div class="badge bg-danger bg-opacity-30">
            @elseif($category_post->category_id == 5)
                <div class="badge bg-info bg-opacity-30">
            @else
                <div class="badge bg-secondary bg-opacity-30">
            @endif    
                    <a href="{{ route('category.show', $category_post->category_id) }}" class="text-decoration-none text-white " >{{ $category_post->category->name }}</a>                
                </div>
            
        @empty
            <div class="badge bg-dark">Uncategorized</div>
        @endforelse
    </div>
</div>

<!-- owner and description -->
{{-- <a href="{{ route('profile.show', $post->user->id) }}" class="text-decoration-none text-dark fw-bold">{{ $post->user->name}}</a> --}}
<br>
<h3>{{ $post->title }}</h3>
@if($post->term_start && $post->term_end && $post->term_start != $post->term_end)
    <p class="text-muted text-uppercase xsmall">{{ date('M d, Y', strtotime($post->term_start))}} ~ {{ date('M d, Y', strtotime($post->term_end))}}</p>
@elseif($post->term_start)
    <p class="text-muted text-uppercase xsmall">{{ date('M d, Y', strtotime($post->term_start))}}</p>
@else 
    <p>---</p>
@endif
<p class="fw-light {{ $noClamp ?? false ? '' : 'description' }}">
    {{ $post->description }}
</p>


