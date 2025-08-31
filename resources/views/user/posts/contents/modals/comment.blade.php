<!-- コメントモーダル -->
<div class="modal fade" id="commentModal{{ $post->id }}" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content border-info">
            <div class="modal-header border-info">
                <h5 class="modal-title">Comment on "{{ $post->title }}"</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            
                <form action="{{ route('comment.store', $post->id) }}" method="post">
                    @csrf
                    <div class="modal-body border-info">
                        <div>
                            @forelse($post->comments->sortByDesc('created_at')->take(5)->sortBy('created_at') as $comment)
                                <div class="mb-2">
                                    @if($comment->user->avatar)
                                        <img src="{{ $comment->user->avatar }}" alt="" class="rounded-circle avatar-mini">
                                    @else
                                        <i class="fa-solid fa-circle-user text-secondary icon-mini align-items-center"></i>
                                    @endif
                                    <a href="{{ route('profile.show', $comment->user->id) }}" class="text-decoration-none text-dark fw-bold ms-2">{{ $comment->user->name }}</a>
                                    &nbsp;
                                    <span class="fw-light comment-content">{{ $comment->body }}</span>
                                </div>
                            @empty
                                {{-- <p class="text-secondary">No comments yet.</p> --}}
                            @endforelse
                        </div>
                        <div class="">
                            <textarea name="comment_body{{ $post->id }}" rows="3" class="form-control" placeholder="Write your comment...">{{ old('comment_body'.$post->id) }}</textarea>
                            @error('comment_body'.$post->id)
                                <p class="mb-0 text-danger small">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div class="modal-footer border-0">
                        <button type="submit" class="btn btn-primary btn-sm px-3">Post</button>
                    </div>
                    
                </form>
            
        </div>
    </div>
</div>
