<div class="card-header bg-white py-3">
    <div class="row align-items-center">
        <div class="col-auto">
            {{-- post owner icon/avatar --}}
            <a href="{{ route('profile.show', $post->user->id) }}">
                @if($post->user->avatar)
                    <img src="{{ $post->user->avatar }}" alt="" class="rounded-circle avatar-sm">
                @else
                    <i class="fa-solid fa-circle-user text-secondary icon-sm"></i>
                @endif
            </a>
        </div>
        <div class="col ps-0">
            {{-- post owner's name --}}
            <a href="{{ route('profile.show', $post->user->id) }}" class="text-decoration-none text-dark">
                {{ $post->user->name }}
            </a>
        </div>


        <div class="col-auto">
            {{-- buttons --}}
            {{-- <div class="dropdown">
                <button class="btn btn-sm" data-bs-toggle="dropdown">
                    <i class="fa-solid fa-ellipsis"></i>
                </button> --}}
            @auth
                @if($post->user_id == Auth::user()->id)
                <div class="dropdown">
                    <button class="btn btn-sm" data-bs-toggle="dropdown">
                        <i class="fa-solid fa-ellipsis"></i>
                    </button>
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
                </div>    
                    @include('user.posts.contents.modals.delete')  
                @else
                    {{-- @if($post->user->isFollowed())
                        <!-- unfollow -->
                        <div class="">
                            <form action="{{ route('follow.delete', $post->user->id) }}" method="post">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-outline-secondary">Unfollow</button>
                            </form>
                        </div>
                    @else
                          <div class="">
                            <form action="{{ route('follow.store', $post->user->id) }}" method="post">
                                @csrf
                                <button type="submit" class="btn btn-sm btn-primary">Follow</button>
                            </form>
                        </div>
                    @endif  --}}

                    @php
                    $isFollowing = \App\Models\Follow::where('follower_id', Auth::id())
                                    ->where('followed_id', $post->user->id)
                                    ->exists();
                    @endphp
                    <div>
                        <form action="{{ route('follow.toggle', $post->user->id) }}"
                            method="POST"
                            data-user-id="{{ $post->user->id }}"
                            class="follow-form">
                        @csrf
                        <button type="button"
                                class="btn btn-sm follow-btn {{ $isFollowing ? 'btn-outline-secondary' : 'btn-primary' }}">
                            <span class="label">{{ $isFollowing ? 'Following' : 'Follow' }}</span>
                        </button>
                        </form>
                    </div>
                @endif
            @endauth
            {{-- </div> --}}
        </div>
        
    </div>
</div>
