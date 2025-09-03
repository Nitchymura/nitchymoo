<div class="mt-3 bg-white">
    <form action="{{ route('comment.store', $post->id) }}" method="post">
        @csrf
        <div class="input-group">
            <textarea name="comment_body{{ $post->id }}" rows="1" class="form-control form-control-sm">{{ old('comment_body'.$post->id) }}</textarea>
            <button type="submit" class="btn btn-sm btn-outline-secondary">Post</button>           
        </div>
        @error('comment_body'.$post->id)
            <p class="mb-0 text-danger small">{{ $message }}</p>
        @enderror
    </form>
</div>