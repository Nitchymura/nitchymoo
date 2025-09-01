@extends('admin.menu')

@section('title', 'Admin: Comments')

@section('sub_content')
    <form action="{{ route('admin.comments') }}" method="get">
        <input type="text" name="search" placeholder="search..." class="form-control form-control-sm w-25 my-3 ms-auto">
    </form>
    <table class="table border bg-qhite table-hover align-middle text-secondary">
        <thead class="table-primary text-secondary text-uppercase small">
            <tr>
                <th></th>
                <th></th>
                <th>body</th>
                <th>owner</th>
                <th>created at</th>
                <th>status</th>
                <th class="text-center"><i class="fa-solid fa-eye"></i> / <i class="fa-solid fa-eye-slash"></th>
                <th></th>
            </tr>
        </thead>
        <tbody>
            @forelse($all_comments as $comment)
                <tr>
                    <td>{{ $comment->id }}</td>
                    <td>
                        @if($comment->post->image)
                            <a href="{{ route('post.show', $comment->post->id) }}"><img src="{{ $comment->post->image }}" alt="" class="img-sm d-block mx-auto"></a>
                        @endif
                    </td>
                    <td>{{ $comment->body }}</td>
                    <td><a href="{{ route('profile.show', $comment->user->id) }}" class="text-decoration-none text-dark fw-bold">{{ $comment->user->name }}</a></td>
                    <td>
                        {{date('M d, Y H:i', strtotime($comment->created_at))}}
                    </td>
                    <td>
                        {{-- status --}}
                        @if($comment->trashed())
                            <i class="fa-solid fa-circle-minus text-secondary"></i> Hidden
                        @else
                            <i class="fa-solid fa-circle text-primary"></i> Visible
                        @endif
                    </td>
                    <td class="text-center">
                        {{-- @if($comment->user->id != Auth::user()->id) --}}
                        <div class="dropdown">
                            <button class="btn btn-sm" data-bs-toggle="dropdown" >
                                <i class="fa-solid fa-ellipsis"></i>
                            </button>

                            @if($comment->trashed())
                                <div class="dropdown-menu">
                                    <button class="dropdown-item text-dark" data-bs-toggle="modal" data-bs-target="#activate-comment{{ $comment->id }}">
                                        <i class="fa-solid fa-eye"></i> Unhide comment {{ $comment->id }}
                                    </button>
                                </div>
                            @else
                                <div class="dropdown-menu">
                                    <button class="dropdown-item text-danger" data-bs-toggle="modal" data-bs-target="#deactivate-comment{{ $comment->id }}">
                                        <i class="fa-solid fa-eye-slash"></i> Hide comment {{ $comment->id }}
                                    </button>
                                </div>
                            @endif
                        </div>
                        @include('admin.comments.status')
                        {{-- @endif --}}
                    </td>
                    <td>
                        <button class="btn btn-sm btn-outline-danger" data-bs-toggle="modal" data-bs-target="#delete-comment{{$comment->id}}">
                            <i class="fa-solid fa-trash "></i>
                        </button>
                        @include('admin.comments.delete')
                    </td>
                </tr>
            @empty
                <tr>
                    <td class="text-center" colspan="6">No comments found.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
    {{ $all_comments->links() }}

    
@endsection