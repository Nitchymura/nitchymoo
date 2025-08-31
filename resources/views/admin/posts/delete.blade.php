<div class="modal fade" id="delete-post{{ $post->id }}">
    <div class="modal-dialog">
        <div class="modal-content border-danger">
            <div class="modal-header border-danger">
                <h3 class="h3 text-danger"><i class="fa-solid fa-eye-slash"></i>Delete post</h3>
            </div>
            <div class="modal-body">
                <p>Are you sure you want to completely delete? <br><span class="text-danger fw-bold">Please make sure this action cannot be undone!!</span></p>
                <img src="{{$post->image}}" alt="" class="img-lg mb-2">
                <p class="text-secondary">{{ $post->description }}</p> 
            </div>
            <div class="modal-footer border-0">
                <form action="{{ route('admin.posts.delete', $post->id)}}" method="post">
                    @csrf
                    @method('DELETE')
                    <button type="button" data-bs-dismiss="modal" class="btn btn-sm btn-outline-danger">Cancel</button>
                    <button type="submit" class="btn btn-sm btn-danger">Delete</button>
                </form>
            </div>
        </div>
    </div>
</div>
