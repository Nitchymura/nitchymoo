<div class="modal fade" id="user-roleid{{ $user->id }}">
    <div class="modal-dialog">
        <div class="modal-content border-primary">
            <div class="modal-header border-primary">
                <h3 class="h4 text-primary"><i class="fa-regular fa-pencil-to-square"></i> Change Role ID</h3>
            </div>
            <form action="{{ route('admin.user.roleid', $user->id) }}" method="post">
                    @csrf
                    @method('PATCH')
            <div class="modal-body">
                <p>You can change <span class="fw-bold">{{ $user->name }}</span> 's role ID.</p>
                <select name="role_id" id="role_id" class="form-control w-50">
                    <option value="2" >{{ old('role_id') == '2' ? 'selected' : '' }}2: User</option>
                    <option value="3" >{{ old('role_id') == '3' ? 'selected' : '' }}3: Guest</option>
                </select>
            </div>
            <div class="modal-footer border-0">                    
                    <button type="button" data-bs-dismiss="modal" class="btn btn-sm btn-outline-primary">Cancel</button>
                    <button type="submit" class="btn btn-sm btn-primary">Save</button>
                </form>
            </div>
        </div>
    </div>
</div>