<div class="mt-2">
    @if($comment->user->avatar)
        <img src="{{ $comment->user->avatar }}" alt="" class="rounded-circle avatar-mini">
    @else
        <i class="fa-solid fa-circle-user text-secondary icon-mini"></i>
    @endif
    <a href="{{ route('profile.show', $post->user->id) }}" class="text-decoration-none text-dark fw-bold">{{ $comment->user->name }}</a>
    &nbsp;
    <span class="fw-light">{{ $comment->body }}</span>

    <div class="xsmall text-secondary">
        <span>{{ date('D, M d Y', strtotime($comment->created_at)) }}</span>

        @if($comment->user_id == Auth::user()->id)
            &middot;
            <form action="{{ route('comment.delete', $comment->id) }}" method="post" class="d-inline">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn p-0 text-danger xsmall">Delete</button>
            </form>
        @endif
    </div>
</div>