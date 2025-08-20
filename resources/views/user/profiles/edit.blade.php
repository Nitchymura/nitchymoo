@extends('layouts.app')

@section('title', 'Edit Profile')

@section('content')
<div class="row" style="height: 40px"></div>
    <div class="row justify-content-center mt-5">
        <div class="col-md-8 col-sm-11">
            <form action="{{ route('profile.update') }}" method="post" class="shadow rounded-3 bg-white p-5 mb-5" enctype="multipart/form-data">
                @csrf
                @method('PATCH')
                <h2 class="h4 text-secondary mb-3">Update Profile</h2>
                <div class="row mb-3">
                    <div class="col-4">
                        <!-- プレビュー用の img は常に用意する -->
                        <img src="{{ Auth::user()->avatar ?? '' }}" 
                            alt="" 
                            id="avatar-preview" 
                            class="rounded-circle img-lg"
                            style="{{ Auth::user()->avatar ? '' : 'display:none;' }}">
                        <!-- ユーザーアイコンを代わりに表示 -->
                        <i class="fa-solid fa-circle-user text-secondary icon-lg d-block text-center" 
                        id="user-icon" 
                        style="{{ Auth::user()->avatar ? 'display:none;' : '' }}"></i>
                    </div>

                    <div class="col-auto align-self-end">
                        <div class="row">
                            <div class="col-auto">
                                <!-- 画像ファイル選択 -->
                                <input type="file" name="avatar" id="avatar" class="form-control form-control-sm w-auto" onchange="previewImage(event)">
                            </div>
                            <div class="col-1">
                                @if(Auth::user()->avatar)
                                <button type="button" class="btn btn-sm btn-danger delete-avatar-profile" id="delete-avatar" >
                                    <i class="fa-solid fa-trash-can"></i>
                                </button>
                                @endif
                            </div>
                        </div>

                        <!-- 画像削除フラグを隠しフィールドに設定 -->
                        <input type="hidden" id="avatar-deleted" name="avatar_deleted" value="false">

                        <p class="mb-0 form-text">
                            Acceptable formats: jpeg, jpg, png, gif <br>
                            Max size is 1048 KB
                        </p>
                        @error('avatar')
                            <p class="mb-0 text-danger small">{{ $message }}</p>
                        @enderror
                    </div>
                </div>


                <label for="name" class="form-label fw-bold mt-3">Name</label>
                <input type="text" name="name" id="name" value="{{ old('name', Auth::user()->name) }}" class="form-control">
                @error('name')
                    <p class="mb-0 text-danger small">{{ $message }}</p>
                @enderror

                <label for="email" class="form-label fw-bold mt-3">Email</label>
                <input type="email" name="email" id="email" value="{{ old('email', Auth::user()->email) }}"  class="form-control">
                @error('email')
                    <p class="mb-0 text-danger small">{{ $message }}</p>
                @enderror

                @if(Auth::user()->role_id == 1)
                <label for="intro" class="form-label fw-bold mt-3">Introduction</label>
                <textarea name="introduction" id="intro" rows="3" class="form-control">{{ old('introduction',Auth::user()->introduction) }}</textarea>
                @endif

                <button type="submit" class="btn btn-warning mt-3 px-5">Save</button>
            </form>
<script src="{{ asset('js/edit-profile.js') }}"></script>
@endsection

