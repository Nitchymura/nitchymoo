@extends('layouts.app')

@section('title', 'Edit Post')

@section('content')
    <form action="{{ route('post.update', $post->id) }}" method="post" enctype="multipart/form-data">
        @csrf
        @method('PATCH')
    <div class="row mt-5">
        <h3 class="mt-5 text-primary">Edit Post</h3>
        <div class="col">
            <p class="mb-2 fw-bold mt-">Category <span class="fw-light">(up to 3)</span></p>
            <div>
                @forelse($all_categories as $category)
                    <div class="form-check form-check-inline"> 
                        @if(in_array($category->id, $selected_categories))
                            <input type="checkbox" name="categories[]" id="{{ $category->name }}"  value="{{ $category->id }}" class="form-check-input" checked>
                        @else
                            <input type="checkbox" name="categories[]" id="{{ $category->name }}"  value="{{ $category->id }}" class="form-check-input">
                        @endif
                        <label for="{{ $category->name }}" class="form-check-label">{{ $category->name }}</label>              
                    </div>
                @empty
                    <span class="fst-italic">No categories. Please add categories before posting</span>
                @endforelse
            </div>
            @error('categories')
                <p class="mb-0 text-danger small">{{ $message }}</p>
            @enderror
        </div>

        <div class="row">
            <div class="col">
                <label for="title" class="form-label fw-bold mt-3">Title</label>
                <input type="text" class="form-control" name="title" id="title" value="{{ old('title', $post->title) }}">        
                @error('title')
                    <p class="mb-0 text-danger small">{{ $message }}</p>
                @enderror
            </div>
        </div>

        <div class="row">
            <div class="col-6">
                <label for="term-start" class="form-label fw-bold mt-3">Start date</label>
                <input type="date" name="term_start" id="term-start" class="form-control" value="{{ old('term_start', $post->term_start) }}">
            </div>
            <div class="col-6">
                <label for="term-end" class="form-label fw-bold mt-3">End date</label>
                <input type="date" name="term_end" id="term-end" class="form-control" value="{{ old('term_end', $post->term_end) }}">
            </div>
        </div>

        <label for="description" class="form-label fw-bold mt-3">Description</label>
        <textarea name="description" id="description" rows="3" placeholder="What's on your mind" class="form-control">{{ old('description', $post->description) }}</textarea>
        @error('description')
            <p class="mb-0 text-danger small">{{ $message }}</p>
        @enderror

        
        {{-- <img src="{{ $post->image }}" alt="" class="d-block w-50 img-thumbnail mb-2">
        <input type="file" name="image" id="image" class="form-control"> --}}
        

        <div class="row">
            <div class="col">
                <label for="image" class="form-label fw-bold mt-3">Main Image</label>
                @if($post->image)
                    <img src="{{ $post->image }}" alt="" id="main-image-preview" class="d-block w-100 img-thumbnail mb-2">
                @else
                    <i class="fa-solid fa-image text-secondary icon-lg d-block text-center" id="image-icon"></i>
                @endif
            </div>
        </div>    

        <div class="row">
            <div class="col">
                <!-- 画像ファイル選択 -->
                <input type="file" name="image" id="image" class="form-control" onchange="previewImage(event)">
                <p class="mb-0 form-text">
                    Acceptable formats: jpeg, jpg, png, gif <br>
                    Max size is 1048 KB
                </p>
            </div>
        </div>

        @error('image')
            <p class="mb-0 text-danger small">{{ $message }}</p>
        @enderror

        @include('user.posts.contents.photos.photos')
        {{-- <div class="row mx-0 px-0 text-center">
            <div class="text-start pb-3 px-0">
                <label for="photo" class="form-label mt-3">Other Photos</label>
                <div class="col-12 px-0">
                    @foreach($all_bodies as $body)
                        <img src="{{ $body->photo }}" alt="" class="img-thumbnail img-lg">
                    @endforeach
                    <input type="file" id="photo" class="custom-file-input form-control input-box w-100" multiple>
                    <p id="image-error" class="text-danger small d-none">Please upload at leaset one image.</p>
                    @error('photo')
                        <p class="mb-0 text-danger small">{{ $message }}</p>
                    @enderror
                </div>
                <div class="col-3">
                    <button type="button" class="btn btn-green custom-file-label w-100 me-0 d-none" id="upload-btn">
                        <i class="fa-solid fa-plus icon-xs d-inline"></i>Photo
                    </button>
                </div>
            </div>
                <p class="mt-0 xsmall text-start">
                    Acceptable formats: jpeg, jpg, png, gif only.<br>Max size is 1048 KB
                </p>
        </div> --}}

        <button type="submit" class="btn btn-warning mt-4 px-4 w-25">Save</button>
    </div>

    </form>

<script src="{{ asset('js/main-image-preview.js') }}"></script>
<script src="{{ asset('js/edit-photo.js') }}"></script>
<script src="{{ asset('js/photos-uploader-bridge.js') }}"></script>
@endsection