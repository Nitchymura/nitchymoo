@extends('layouts.app')

{{-- @section('title', 'Admin') --}}

@section('content')

{{-- @if(request()->is('admin/*')) --}}
<main>
    <div class="row justify-content-center mt-5">
        <div class="col-2 my-5">
            <div class="list-group">
                <a href="{{ route('admin.users') }}" class="list-group-item {{ request()->is('admin/users*') ? 'active' : '' }}">
                    <i class="fa-solid fa-users"></i> Users
                </a>
                <a href="{{ route('admin.posts') }}" class="list-group-item {{ request()->is('admin/posts*') ? 'active' : '' }}">
                    <i class="fa-solid fa-newspaper"></i> Posts
                </a>
                
                <a href="{{ route('admin.comments') }}" class="list-group-item {{ request()->is('admin/comments*') ? 'active' : '' }}">
                    <i class="fa-solid fa-comments"></i> Comments
                </a>
                <a href="{{ route('admin.categories') }}" class="list-group-item {{ request()->is('admin/categories*') ? 'active' : '' }}">
                    <i class="fa-solid fa-tags"></i> Categories
                </a>
                <a href="{{ route('admin.faqs') }}" class="list-group-item {{ request()->is('admin/categories*') ? 'active' : '' }}">
                    <i class="fa-solid fa-circle-question"></i> FAQ
                </a>
            </div>
        </div>
        <div class="col-9 my-4">
            @yield('sub_content')
        </div>
    </div>
</main>
{{-- @endif --}}

@endsection