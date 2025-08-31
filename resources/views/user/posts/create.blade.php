@extends('layouts.app')

@section('title', 'Create Post')

@section('content')

    <form action="{{ route('post.store') }}" method="post" enctype="multipart/form-data">
        @csrf
        <div class="row mt-5">
            <h3 class="mt-5 text-primary">Create Post</h3>
            <div class="col">
                <p class="mb-2 fw-bold">Category <span class="fw-light">(up to 3)</span></p>
                <div>
                    @forelse($all_categories as $category)
                        <div class="form-check form-check-inline">                
                            <input type="checkbox" name="categories[]" id="{{ $category->name }}"  value="{{ $category->id }}" class="form-check-input">
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
        </div>

        
        <div class="row">
            <div class="col-6">
                <label for="title" class="form-label fw-bold mt-3">Title</label>
                <input type="text" class="form-control" name="title" id="title" value="{{ old('title') }}">        
                @error('title')
                    <p class="mb-0 text-danger small">{{ $message }}</p>
                @enderror
            </div>
            <div class="col-6">
                <label for="subtitle" class="form-label fw-bold mt-3">Subtitle(optional)</label>
                <input type="text" class="form-control" name="subtitle" id="subtitle" value="{{ old('subtitle') }}">        
                @error('subtitle')
                    <p class="mb-0 text-danger small">{{ $message }}</p>
                @enderror
            </div>
        </div>

        <div class="row">
            <div class="col-6">
                <label for="city" class="form-label fw-bold mt-3">City / Prefecture</label>
                <input type="text" name="city" id="city" class="form-control" value="{{ old('city') }}">
            </div>
                <div class="col-6">
                <label for="country" class="form-label fw-bold mt-3">Country</label>
                <input type="text" name="country" id="country" class="form-control" value="{{ old('country') }}">
            </div>
        </div>
        
        <div class="row">
            <div class="col-6">
                <label for="term-start" class="form-label fw-bold mt-3">Start date</label>
                <input type="date" name="term_start" id="term-start" class="form-control" value="{{ old('term_start') }}">
            </div>
                <div class="col-6">
                <label for="term-end" class="form-label fw-bold mt-3">End date</label>
                <input type="date" name="term_end" id="term-end" class="form-control" value="{{ old('term_end') }}">
            </div>
        </div>


        <div>
            <label for="description" class="form-label fw-bold mt-3">Description</label>
        <textarea name="description" id="description" rows="3" placeholder="What's on your mind" class="form-control">{{ old('description') }}</textarea>
        @error('description')
            <p class="mb-0 text-danger small">{{ $message }}</p>
        @enderror
        </div>

        <div>
            <label for="translation" class="form-label fw-bold mt-3">Translation(optional)</label>
        <textarea name="translation" id="translation" rows="3" placeholder="日本語訳があれば..." class="form-control">{{ old('translation') }}</textarea>
        @error('translation')
            <p class="mb-0 text-danger small">{{ $message }}</p>
        @enderror
        </div>
        

        <div class="row">
            <div class="col">
                <label for="image" class="form-label fw-bold mt-3">Main Image</label>
                <div class="col" id="main-image-preview">
                    @if(!empty($post->image)) {{-- 編集時の既存画像 --}}
                        <img src="{{ $post->image }}" alt="Main Image"  class="d-block w-50 img-thumbnail mb-2" >
                    @endif
                </div>
            </div>
        </div>
        {{-- <label for="image" class="form-label fw-bold mt-3">Main Image</label> --}}
        <!-- プレビュー表示領域 -->
        {{-- <div class="col mt-2" id="main-image-preview">
            @if(!empty($post->image)) 
                <img src="{{ $post->image }}" alt="Main Image" class="d-block w-50 img-thumbnail mb-2" >
            @endif
        </div> --}}
        
        <input type="file" name="image" id="image" class="form-control mt-2">

        <p class="mb-0 form-text">
            Acceptable formats: jpeg, jpg, png, gif <br>
            Max size is 1048 KB
        </p>
        @error('image')
            <p class="mb-0 text-danger small">{{ $message }}</p>
        @enderror

        @include('user.posts.contents.photos.photos')
       
        <button type="submit" class="btn btn-primary mt-4 px-4 w-25">Post</button>
    </div>
    </form>

<script src="{{ asset('js/main-image-preview.js') }}"></script>
<script src="{{ asset('js/edit-photo.js') }}"></script>
<script src="{{ asset('js/photos-uploader-bridge.js') }}"></script>
@endsection