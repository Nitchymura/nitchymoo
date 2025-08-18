<div class="row align-items-center">
    <div class="col-auto">
        {{-- like/heart button --}}
        @if($post->isLiked())
            {{-- red heart/unlike --}}
            <form action="{{route('like.delete', $post->id)}}" method="post">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn p-0">
                    <i class="fa-solid fa-heart text-danger"></i>&nbsp; {{ $post->likes->count() }}
                </button>
            </form>
        @else
            <form action="{{route('like.store', $post->id)}}" method="post">
                @csrf
                <button type="sumbit" class="btn p-0">
                    <i class="fa-regular fa-heart"></i>&nbsp;  {{ $post->likes->count() }}
                </button>
            </form>
        @endif
    </div>
    {{-- <div class="col-auto px-0 me-1">
        <!-- No. of likes -->
        @if($post->likes->count()>=1)
            <button class="btn btn-white border-0" data-bs-toggle="modal" data-bs-target="#like-list{{ $post->id }}">
                {{ $post->likes->count() }}
            </button>   
        @else
            0
        @endif
        @include('user.posts.contents.modals.like-list')
    </div> --}}
    {{-- <div class="col-1"></div> --}}
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
<p class="fw-light description">{{ $post->description }}</p>
