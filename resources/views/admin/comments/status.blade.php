@if(!$comment->trashed())
{{-- DEACTIVATE --}}
<div class="modal fade" id="deactivate-comment{{ $comment->id }}">
    <div class="modal-dialog">
        <div class="modal-content border-danger">
            <div class="modal-header border-danger">
                <h3 class="h3 text-danger"><i class="fa-solid fa-eye-slash"></i>Hide comment</h3>
            </div>
            <div class="modal-body">
                <p>Are you sure you want to hide this comment ?</p>              
                {{-- <img src="{{$comment->image}}" alt="" class="img-lg mb-2"> --}}
                <p class="text-secondary">{{ $comment->body }}</p>               
            </div>
            <div class="modal-footer border-0">
                <form action="{{ route('admin.comments.deactivate', $comment->id)}}" method="post">
                    @csrf
                    @method('DELETE')
                    <button type="button" data-bs-dismiss="modal" class="btn btn-sm btn-outline-danger">Cancel</button>
                    <button type="submit" class="btn btn-sm btn-danger">Hide</button>
                </form>
            </div>
        </div>
    </div>
</div>
@else
{{-- ACTIVATE --}}
<div class="modal fade" id="activate-comment{{$comment->id}}">
    <div class="modal-dialog">
        <div class="modal-content border-primary">
            <div class="modal-header border-primary">
                <h3 class="h3 text-primary"><i class="fa-solid fa-eye"></i> Unhide comment</h3>
            </div>
            <div class="modal-body">
                <p>Are you sure you want to unhide this comment?</p>
                {{-- <img src="{{$comment->image}}" alt="" class="img-lg mb-2"> --}}
                <p class="text-secondary">{{ $comment->body }}</p>
            </div>
            <div class="modal-footer border-0">
                <form action="{{route('admin.comments.activate', $comment->id)}}" method="post">
                    @csrf
                    @method('PATCH')
                    <button type="button" data-bs-dismiss="modal" class="btn btn-sm btn-outline-primary">Cancel</button>
                    <button type="submit" class="btn btn-sm btn-primary">Unhide</button>
                </form>
            </div>
        </div>
    </div>
</div>

@endif