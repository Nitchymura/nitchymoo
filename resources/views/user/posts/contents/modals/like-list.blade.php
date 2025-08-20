<div class="modal fade" id="like-list{{ $post->id }}" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content border-0">
            <div class="modal-header border-0">
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body px-5">
                <div class="w-75 mx-auto">
                    @foreach($post->likes as $like)
                    <div class="row align-items-center mb-3">
                        <div class="col-auto">
                            @if($like->user && $like->user->avatar)
                                <img src="{{ $like->user->avatar }}" alt="" class="rounded-circle avatar-sm">
                            @else
                                <i class="fa-solid fa-circle-user text-secondary icon-sm"></i>
                            @endif
                        </div>
                        <div class="col">
                            <span class="text-secondary">{{ $like->user ? $like->user->name : 'Deleted User' }}</span>
                        </div>
                        <div class="col-auto">
                            @if($like->user && $like->user->id != Auth::id())
                                @if($like->user->isFollowed())
                                    <form action="{{ route('follow.delete', $like->user->id) }}" method="post">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-outline-secondary">Following</button>
                                    </form>
                                @else
                                    <form action="{{ route('follow.store', $like->user->id) }}" method="post">
                                        @csrf
                                        <button type="submit" class="btn btn-sm btn-primary">Follow</button>
                                    </form>
                                @endif
                            @endif
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>