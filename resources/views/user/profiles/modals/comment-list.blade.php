<style>

.modal-body{
    overflow-y: scroll;
    height: 500px;
}
</style>

<div class="modal fade" id="comment-list{{ $user->id }}">
    <div class="modal-dialog">
        <div class="modal-content border-0">
            <div class="modal-header">
                <h3 class="h3 text-secondary">Recent Comments</h3>
            </div>
            <div class="modal-body px-4">
                <div class="w-100 mx-auto">
                    @foreach($all_comments as $comment)
                    <div class="row border border-primary rounded-3 align-items-center mb-3 p-3">
                        <p class="text-secondary">{{ $comment->body }}</p>
                        <hr>
                        <p class="text-secondary">Replied to <span class="text-primary"><a href="{{ route('post.show', $comment->post->id) }}" class="text-decoration-none">{{ $comment->post->user->name }}</a>'s post</span></p>
                    </div>
                    @endforeach
                </div>
            </div>
            <div class="modal-footer">
                <button data-bs-dismiss="modal" class="btn btn-sm ms-auto btn-outline-secondary">Close</button>
            </div>
        </div>
    </div>
</div>