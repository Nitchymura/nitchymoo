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

    <div class="col-auto">
        @if($post->comments->count() >= 1)
            <i class="fa-solid fa-comment text-info"></i>&nbsp;  {{ $post->comments->count() }}
        @else
            <i class="fa-regular fa-comment text-dark"></i>&nbsp;  {{ $post->comments->count() }}
        @endif
    </div>

    @if($post->postBodies->count() > 0)
    <div class="col-auto">
        <i class="fa-solid fa-photo-film text-secondary"></i>&nbsp;  {{ $post->postBodies->count() }}
    </div>
        
    @endif
    

</div>


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
