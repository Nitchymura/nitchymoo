@extends('admin.menu')

@section('title', 'Admin: Posts')

@section('sub_content')
    <form action="{{ route('admin.posts') }}" method="get">
        <input type="text" name="search" placeholder="search..." class="form-control form-control-sm w-25 my-3 ms-auto">
    </form>
    <table class="table border bg-qhite table-hover align-middle text-secondary">
        <thead class="table-primary text-secondary text-uppercase small">
            <tr>
                <th></th>
                <th></th>
                <th>category</th>
                <th>owner</th>
                <th>created at</th>
                <th>status</th>
                <th class="text-center"><i class="fa-solid fa-eye"></i> / <i class="fa-solid fa-eye-slash"></th>
                <th></th>
            </tr>
        </thead>
        <tbody>
            @forelse($all_posts as $post)
                <tr>
                    <td>{{ $post->id }}</td>
                    <td>
                        @if($post->image)
                            <a href="{{ route('post.show', $post->id) }}"><img src="{{ $post->image }}" alt="" class="img-lg d-block mx-auto"></a>
                        @endif
                    </td>
                    <td>
                        
                        @if($post->categoryPosts)
                            @foreach($post->categoryPosts as $category_post)
                                @php
                                // 今表示中のカテゴリを $category と仮定
                                $badgeClass = match($category_post->category_id) {
                                    1 => 'bg-success',
                                    2 => 'bg-primary',
                                    3 => 'bg-warning',
                                    4 => 'bg-danger',
                                    5 => 'bg-info',
                                    6 => 'bg-secondary',
                                    default => 'bg-white text-dark border border-dark',
                                };
                                @endphp
                                <div>
                                    <a href="{{ route('admin.posts', ['category' => $category_post->category_id]) }}" class="top-badge badge badge-sm mb-1 text-decoration-none {{ $badgeClass }} {{ $category_post->category_id }}">
                                        {{ $category_post->category->name }}
                                    </a>             
                                </div>    
                            @endforeach
                        @else
                            <div class="badge bg-dark">Uncategorized</div>
                        @endif
                    </td>
                    <td><a href="{{ route('profile.show', $post->user->id) }}" class="text-decoration-none text-dark fw-bold">{{ $post->user->name }}</a></td>
                    <td>
                        {{date('M d, Y', strtotime($post->created_at))}}
                    </td>
                    <td>
                        {{-- status --}}
                        @if($post->trashed())
                            <i class="fa-solid fa-circle-minus text-secondary"></i> Hidden
                        @else
                            <i class="fa-solid fa-circle text-primary"></i> Visible
                        @endif
                    </td>
                    <td class="text-center">
                        {{-- @if($post->user->id != Auth::user()->id) --}}
                        <div class="dropdown">
                            <button class="btn btn-sm" data-bs-toggle="dropdown" >
                                <i class="fa-solid fa-ellipsis"></i>
                            </button>

                            @if($post->trashed())
                                <div class="dropdown-menu">
                                    <button class="dropdown-item text-dark" data-bs-toggle="modal" data-bs-target="#activate-post{{ $post->id }}">
                                        <i class="fa-solid fa-eye"></i> Unhide post {{ $post->id }}
                                    </button>
                                </div>
                            @else
                                <div class="dropdown-menu">
                                    <button class="dropdown-item text-danger" data-bs-toggle="modal" data-bs-target="#deactivate-post{{ $post->id }}">
                                        <i class="fa-solid fa-eye-slash"></i> Hide post {{ $post->id }}
                                    </button>
                                </div>
                            @endif
                        </div>
                        @include('admin.posts.status')
                        {{-- @endif --}}
                    </td>
                    <td>
                        <button class="btn btn-sm btn-outline-danger" data-bs-toggle="modal" data-bs-target="#delete-post{{$post->id}}">
                            <i class="fa-solid fa-trash "></i>
                        </button>
                        @include('admin.posts.delete')
                    </td>
                </tr>
            @empty
                <tr>
                    <td class="text-center" colspan="6">No posts found.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
    {{ $all_posts->links() }}

@endsection