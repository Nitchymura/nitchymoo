<div class="mb-5 row mt-5 pt-5">
    <div class="col-4">
        {{-- avatar/icon --}}       
        {{-- <div type="button" data-bs-toggle="modal" data-bs-target="#comment-list{{ $user->id }}"> --}}
            @if($user->avatar)
                <img src="{{ $user->avatar }}" alt="" class="rounded-circle img-lg d-block mx-auto">
            @else
                <i class="fa-solid fa-circle-user text-secondary icon-lg d-block text-center"></i>
            @endif
        {{-- </div>  --}}
        {{-- @include('user.profiles.modals.comment-list') --}}
    </div>
    <div class="col mb-3">
        <div class="row mb-3 align-items-end">
            <div class="col-auto">
                <h2 class="display-6 mb-0">{{ $user->name }}</h2>
            </div>
            <div class="col-md col-sm-12">
                @if($user->id == Auth::user()->id)
                    {{-- edit profile --}}
                    <a href="{{ route('profile.edit') }}" class="btn btn-sm btn-outline-secondary fw-bold mb-2">Edit Profile</a>
                    <a href="{{ route('profile.change-password') }}" class="btn btn-sm btn-outline-danger fw-bold mb-2 ms-3">Change Password</a>
                @else
                    {{-- follow --}}
                    @if($user->isFollowed())
                        <form action="{{ route('follow.delete', $user->id) }}" method="post">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-outline-secondary fw-bold mb-2">Following</button>
                        </form>
                    @else
                        <form action="{{ route('follow.store', $user->id) }}" method="post">
                            @csrf
                            <button type="submit" class="btn btn-sm btn-primary fw-bold mb-2">Follow</button>
                        </form>
                    @endif
                @endif
            </div>
        </div>
        <div class="row mb-3">
            <div class="col-auto">
                <a href="{{ route('profile.show', $user->id) }}" class="text-decoration-none text-dark">
                    <span class="fw-bold">{{ $user->posts->count() }}</span> {{ $user->posts->count()==1 ? 'post' : 'posts' }}
                </a>
            </div>
            <div class="col-auto">
                <a href="{{ route('profile.followers', $user->id) }}" class="text-decoration-none text-dark">
                    <span class="fw-bold">{{ $user->followers->count() }}</span> {{ $user->followers->count()==1 ? 'follower' : 'followers' }}
                </a>
            </div> 
            <div class="col-auto">
                <a href="{{ route('profile.following', $user->id) }}" class="text-decoration-none text-dark">
                    <span class="fw-bold">{{ $user->follows->count() }}</span> following
                </a>
            </div>
        </div>
        @if($user->id == Auth::user()->id)
        <div class="row mb-3">
            <div class="col-auto">
                <a href="{{ route('all.suggested') }}" class="text-decoration-none text-dark">
                    <span class="fw-bold">Suggested Users for you</span>{{ count($suggested_users) > 0 ? ' ('.count($suggested_users).')' : '' }}
                </a>
            </div>
        </div>
        @endif

        
    </div>
    <div class="row justify-content-center">
        <div class="col-10">
            <p class="">{{ $user->introduction }}</p>
        </div>
    </div>
    
</div>