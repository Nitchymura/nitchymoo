<div class="modal fade" id="delete-post{{ $post->id }}">
    <div class="modal-dialog">
        <div class="modal-content border-danger">
            <div class="modal-header border-danger">
                <h3 class="h4 text-danger"><i class="fa-regular fa-trash-can"></i> Delete Post</h3>
            </div>
            <div class="modal-body">
                <p>Are you sure you wan to delete this post?</p>
                <img src="{{ $post->image }}" alt="" class="img-lg d-block mb-2">
                <span class="fw-light text-secondary">{{ $post->description }}</span>
            </div>
            <div class="modal-footer border-0">
                <form action="{{ route('post.delete', $post->id) }}" method="post">
                    @csrf
                    @method('DELETE')
                    <button type="button" data-bs-dismiss="modal" class="btn btn-sm btn-outline-danger">Cancel</button>
                    <button type="submit" class="btn btn-sm btn-danger">Delete</button>
                </form>
            </div>
        </div>
    </div>
</div>