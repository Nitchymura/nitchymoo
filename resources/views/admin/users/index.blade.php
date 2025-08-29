@extends('admin.menu')

@section('title', 'Admin: Users')

@section('sub_content')
    <form action="{{ route('admin.users') }}" method="get">
        <input type="text" name="search" placeholder="search..." class="form-control form-control-sm w-25 my-3 ms-auto">
    </form>
    <table class="table border bg-white table-hover align-middle text-secondary">
        <thead class="table-success text-secondary text-uppercase small">
            <tr>
                <th></th>
                <th>Name</th>
                <th>Role ID</th>
                <th>e mail</th>
                <th>created at</th>
                <th>status</th>
                <th></th>
            </tr>
        </thead>
        <tbody>
            @forelse($all_users as $user)
                <tr>
                    <td>
                        @if($user->avatar)
                            <img src="{{ $user->avatar }}" alt="" class="rounded-circle avatar-md d-block mx-auto">
                        @else
                            <i class="fa-solid fa-circle-user text-secondary icon-md d-block text-center"></i>
                        @endif
                    </td>
                    <td><a href="{{ route('profile.show', $user->id) }}" class="text-decoration-none text-dark fw-bold">{{ $user->name }}</a></td>
                    <td class="ps-4">
                        @if($user->id != 1)
                            <button class="dropdown-item text-primary" data-bs-toggle="modal" data-bs-target="#user-roleid{{ $user->id }}">
                                {{ $user->role_id }}
                        </button>
                        @include('admin.users.modals.role-id')
                        @else
                            {{ $user->role_id }}
                        @endif
                    </td>
                    <td>{{ $user->email }}</td>
                    <td>
                        {{date('M d, Y H:i:s', strtotime($user->created_at))}}
                    </td>
                    <td>
                        {{-- status --}}
                        @if($user->trashed())
                            <i class="fa-regular fa-circle"></i> Inactive
                        @else
                            <i class="fa-solid fa-circle text-success"></i> Active
                        @endif
                    </td>
                    <td>
                        @if($user->id != Auth::user()->id)
                        <div class="dropdown">
                            <button class="btn btn-sm" data-bs-toggle="dropdown" >
                                <i class="fa-solid fa-ellipsis"></i>
                            </button>

                            @if($user->trashed())
                                <div class="dropdown-menu">
                                    <button class="dropdown-item text-dark" data-bs-toggle="modal" data-bs-target="#activate-user{{ $user->id }}">
                                        <i class="fa-solid fa-user-slash"></i> Activate {{ $user->name }}
                                    </button>
                                </div>
                            @else
                                <div class="dropdown-menu">
                                    <button class="dropdown-item text-danger" data-bs-toggle="modal" data-bs-target="#deactivate-user{{ $user->id }}">
                                        <i class="fa-solid fa-user-slash"></i> Deactivate {{ $user->name }}
                                    </button>
                                </div>
                            @endif
                        </div>
                        @include('admin.users.status')
                        @endif
                    </td>
                </tr>
            @empty
                <tr>
                    <td class="text-center" colspan="6">No users found.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
    {{ $all_users->links() }}

    <hr>
    <h3>User Role Permissions Table</h3>
    <table class="table border bg-white align-middle text-secondary text-center">
        <thead class="table-info text-secondary text-uppercase small">
            <tr>
                <th>role id</th>
                <th>class</th>
                <th>Admin page</th>
                <th>Post</th>
                <th>home page</th>
                <th>post page</th>
                <th>like, comment</th>
            </tr>
        </thead>
        <tbody>
            <tr class="text-center">
                <td>1</td>
                <td>Admin</td>
                <td><i class="fa-regular fa-circle "></i></td>
                <td><i class="fa-regular fa-circle"></i></td>
                <td><i class="fa-regular fa-circle"></i></td>
                <td><i class="fa-regular fa-circle"></i></td>
                <td><i class="fa-regular fa-circle"></i></td>
            </tr>
            <tr>
                <td>2</td>
                <td>User</td>
                <td><i class="fa-solid fa-xmark"></i></td>
                <td><i class="fa-regular fa-circle"></i></td>
                <td><i class="fa-regular fa-circle"></i></td>
                <td><i class="fa-regular fa-circle"></i></td>
                <td><i class="fa-regular fa-circle"></i></td>
            </tr>
            <tr>
                <td>3</td>
                <td>Guest</td>
                <td><i class="fa-solid fa-xmark"></i></td>
                <td><i class="fa-solid fa-xmark"></i></td>
                <td><i class="fa-regular fa-circle"></i></td>
                <td><i class="fa-regular fa-circle"></i></td>
                <td><i class="fa-regular fa-circle"></i></td>
            </tr>
            <tr>
                <td>-</td>
                <td>Not User</td>
                <td><i class="fa-solid fa-xmark"></i></td>
                <td><i class="fa-solid fa-xmark"></i></td>
                <td><i class="fa-regular fa-circle"></i></td>
                <td><i class="fa-solid fa-xmark"></i></td>
                <td><i class="fa-solid fa-xmark"></i></td>
            </tr>
            
        </tbody>
    </table>

@endsection