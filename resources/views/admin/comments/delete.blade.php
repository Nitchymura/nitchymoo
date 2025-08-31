{{-- DEACTIVATE --}}
<div class="modal fade" id="delete-comment{{ $comment->id }}">
    <div class="modal-dialog">
        <div class="modal-content border-danger">
            <div class="modal-header border-danger">
                <h3 class="h3 text-danger"><i class="fa-solid fa-eye-slash"></i>Delete comment</h3>
            </div>
            <div class="modal-body">
                <p>Are you sure you want to completely delete this comment ?<br><span class="text-danger fw-bold">Please make sure this action cannot be undone!!</span></p>              
                {{-- <img src="{{$comment->image}}" alt="" class="img-lg mb-2"> --}}
                <p class="text-secondary">{{ $comment->body }}</p>               
            </div>
            <div class="modal-footer border-0">
                <form action="{{ route('admin.comments.delete', $comment->id)}}" method="post">
                    @csrf
                    @method('DELETE')
                    <button type="button" data-bs-dismiss="modal" class="btn btn-sm btn-outline-danger">Cancel</button>
                    <button type="submit" class="btn btn-sm btn-danger">Delete</button>
                </form>
            </div>
        </div>
    </div>
</div>
