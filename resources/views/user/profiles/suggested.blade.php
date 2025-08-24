@extends('layouts.app')

@section('title', 'Suggested Users')

@section('content')

    <div class="row justify-content-center mt-5">
        <div class="col-md-6 col-sm-8 mt-5">
            <h4 class="h3 text-center text-secondary mb-3">Suggested Users</h4>
       
        @forelse($suggested_users as $user)
            <div class="row mb-3">
                <div class="col-auto my-auto">
                    <a href="{{ route('profile.show', $user->id) }}" class="text-decoration-none">
                    @if($user->avatar)
                        <img src="{{ $user->avatar }}" alt="" class="rounded-circle avatar-md d-block mx-auto">
                    @else
                        <i class="fa-solid fa-circle-user text-secondary  icon-md d-block text-center"></i>
                    @endif
                    </a>
                </div>
                <div class="col">
                    {{-- 名前（リンク） --}}
                    <a href="{{ route('profile.show', $user->id) }}" class="text-decoration-none text-dark fw-bold d-block">{{ $user->name }}</a>

                    {{-- メール --}}
                    <div class="text-secondary">{{ $user->email }}</div>

                    {{-- フォロー情報 --}}
                    <div class="text-secondary">
                        @if ($user->followingYou())
                            {{-- このユーザーがAuthユーザーをフォローしているか --}}
                            Follows you
                        @else
                            @if($user->followers->count() == 0)
                                No followers yet
                            @else
                                {{ $user->followers->count() }}{{ $user->followers->count() == 1 ? ' follower' : ' followers' }}
                            @endif
                        @endif
                    </div>
                </div>

                {{-- <div class="col">
                    <a href="{{ route('profile.show', $user->id) }}" class="text-decoration-none text-dark fw-bold ms-0">{{ $user->name }}</a>
                    <div class="row text-secondary">{{ $user->email }}</div>
                    <div class="row tex-secondary">
                        @if(Auth::user()->isFollowed())
                            Follows you
                        @else
                            @if($user->followers->count() == 0)
                                No followers yet
                            @else
                                {{ $user->followers->count() }}{{ $user->followers->count()==1 ? ' follower' : ' followers' }}
                            @endif
                        @endif
                    </div>
                </div> --}}
                <div class="col-auto my-auto">
                    <form action="{{ route('follow.store', $user->id) }}" method="post">
                        @csrf
                        <button type="submit" class="btn btn-sm btn-primary fw-bold">Follow</button>
                    </form>
                </div>
            </div>
        @empty
            <h4 class="h5 text-center text-secondary">No suggested users.</h4>
        @endforelse
        </div>
    </div>

@endsection