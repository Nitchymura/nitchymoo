@extends('layouts.app')

@section('title', $user->name. ' - Following')

@section('content')
    @include('user.profiles.header')

    @if($user->follows->isNotEmpty())
        <div class="row justify-content-center">
            <div class="col-6">
                <h4 class="h5 text-center text-secondary mb-3">Following</h4>
                <hr>
                @foreach($user->follows as $follow)
                    <div class="row mb-3 alighn-items-center">
                        <div class="col-auto">
                            {{-- post owner icon/avatar --}}
                            <a href="{{ route('profile.show', $follow->followed->id) }}">
                                @if($follow->followed->avatar)
                                    <img src="{{ $follow->followed->avatar }}" alt="" class="rounded-circle avatar-sm">
                                @else
                                    <i class="fa-solid fa-circle-user text-secondary icon-sm"></i>
                                @endif
                            </a>
                        </div>
                        <div class="col ps-0 text-truncate fw-bold">
                            <a href="{{ route('profile.show', $follow->followed->id) }}" class="text-decoration-none text-dark">{{ $follow->followed->name }}</a>
                        </div>
                        <div class="col-auto">
                            {{-- follow --}}
                            @if($follow->followed->id != Auth::user()->id)
                                @if($follow->followed->isFollowed() )
                                    <form action="{{ route('follow.delete', $follow->followed->id) }}" method="post">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-outline-secondary rounded-5 fw-bold">Following</button>
                                    </form>
                                @else
                                    <form action="{{ route('follow.store', $follow->followed->id) }}" method="post">
                                        @csrf
                                        <button type="submit" class="btn btn-sm btn-info text-white rounded-5 fw-bold">Follow</button>
                                    </form>
                                @endif
                            @endif
                        </div>
                    </div>
                    <hr>
                @endforeach
            </div>
        </div>
    @else
        <h4 class="h5 text-center text-secondary">No followers yet.</h4>
    @endif


@endsection