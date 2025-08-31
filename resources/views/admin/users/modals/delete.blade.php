<div class="modal fade" id="delete-user{{$user->id}}">
    <div class="modal-dialog">
        <div class="modal-content border-danger">
            <div class="modal-header border-danger">
                <h3 class="h3 text-danger"><i class="fa-solid fa-trash-can"></i> Delete user</h3>
            </div>
            <div class="modal-body">
                Are you sure you want to completely delete 
                @if($user->avatar)
                    <img src="{{$user->avatar}}" alt="" class="rounded-circle avatar-sm">
                @else
                    <i class="fa-solid fa-circle-user text-secondary icon-sm align-middle"></i>
                @endif
                <strong>{{$user->name}}</strong>?
                <br><span class="text-danger fw-bold">Please make sure this action cannot be undone!!</span>
            </div>
            <div class="modal-footer border-0">
                <form action="{{ route('admin.users.delete', $user->id)}}" method="post">
                    @csrf
                    @method('DELETE')
                    <button type="button" data-bs-dismiss="modal" class="btn btn-sm btn-outline-danger">Cancel</button>
                    <button type="submit" class="btn btn-sm btn-danger">Delete</button>
                </form>
            </div>
        </div>
    </div>
</div>