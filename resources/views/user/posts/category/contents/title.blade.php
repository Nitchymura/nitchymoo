<div class="card-header bg-white py-3">
    <div class="row align-items-center">
        <div class="col-auto">
            <!-- post owner icon/avatar -->
            <a href="{{ route('profile.show', $post->user->id) }}">
                @if($post->user->avatar)
                    <img src="{{ $post->user->avatar }}" alt="" class="rounded-circle avatar-sm">
                @else
                    <i class="fa-solid fa-circle-user text-secondary icon-sm"></i>
                @endif
            </a>
        </div>
        <div class="col ps-0">
            <!-- post owner's name -->
            <a href="{{ route('profile.show', $post->user->id) }}" class="text-decoration-none text-dark">
                {{ $post->user->name }}
            </a>
        </div>
        <div class="col-auto">
            {{-- buttons --}}
            <div class="dropdown">
                <button class="btn btn-sm" data-bs-toggle="dropdown">
                    <i class="fa-solid fa-ellipsis"></i>
                </button>

                @if($post->user_id == Auth::user()->id)
                    <div class="dropdown-menu">
                        {{-- edit--}}
                        <a href="{{ route('post.edit', $post->id) }}" class="dropdown-item">
                            <i class="fa-regular fa-pen-to-square"></i> Edit
                        </a>
                        {{-- delete --}}
                        <button class="dropdown-item text-danger" data-bs-toggle="modal" data-bs-target="#delete-post{{ $post->id }}">
                            <i class="fa-regular fa-trash-can"></i> Delete
                        </button>
                    </div>  
                    @include('user.posts.category.contents.modals.delete')  
                @else
                    @if($post->user->isFollowed())
                        {{-- unfollow --}}
                        <div class="dropdown-menu">
                            <form action="{{ route('follow.delete', $post->user->id) }}" method="post">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="dropdown-item text-danger">Unfollow</button>
                            </form>
                        </div>
                    @else
                          <div class="dropdown-menu">
                            <form action="{{ route('follow.store', $post->user->id) }}" method="post">
                                @csrf
                                <button type="submit" class="dropdown-item text-primary">Follow</button>
                            </form>
                        </div>
                    @endif 
                @endif
            </div>
        </div>
        
    </div>
</div>