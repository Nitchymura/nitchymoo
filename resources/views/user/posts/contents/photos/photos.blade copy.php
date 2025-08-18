<!-- post/Event photos -->
<div class="mb-4 rounded">
    <label for="photos" class="form-label fw-bold mt-3">Other photos</label>
    <div class="row">
        @for ($i = 1; $i <= 6; $i++)
        @php
            // 編集時には既存の写真を取得
            $existingPhoto = isset($post) && $post->postBodies
                ? $post->postBodies->firstWhere('priority', $i)
                : null;
        @endphp
        <div class="col-md-4 mb-3">
            <div class="position-relative">
                <div class="photo-preview mb-0" id="preview_{{ isset($post) ? $post->id : 'new' }}_{{ $i }}">
                    <!-- 既存の画像がある場合は表示 -->
                    @if($existingPhoto && $existingPhoto->photo)
                        <img src="{{ $existingPhoto->photo }}" alt="Photo {{ $i }}" id="preview_img_{{ isset($post) ? $post->id : 'new' }}_{{ $i }}" class="image-lg img-thumbnail mb-2">
                        <button type="button"
                            class="btn btn-danger delete-photo-btn"
                            data-photo-id="{{ $existingPhoto->id }}"
                            data-preview-id="{{ isset($post) ? $post->id : 'new' }}_{{ $i }}">
                            <i class="fa-solid fa-trash-can trash-can-position py-1 px-1"></i>
                        </button>
                    @else
                        <div class="mb-2" id="placeholder_{{ isset($post) ? $post->id : 'new' }}_{{ $i }}">
                            <i class="fa-solid fa-image text-secondary icon-lg"></i>
                        </div>
                    @endif
                    <input type="file"
                        id="photo_{{ isset($post) ? $post->id : 'new' }}_{{ $i }}"
                        name="photos[{{ isset($post) ? $post->id : 'new' }}][{{ $i }}]"
                        class="form-control photo-input @error('photos.' . $i) is-invalid @enderror"
                        accept="image/*">
                    <input type="hidden" name="priorities[{{ isset($post) ? $post->id : 'new' }}][{{ $i }}]" value="{{ $i }}">
                    <input type="hidden" name="existing_photos[{{ isset($post) ? $post->id : 'new' }}][{{ $i }}]" value="{{ $existingPhoto ? $existingPhoto->id : '' }}">
                    <input type="hidden" name="delete_photos[{{ isset($post) ? $post->id : 'new' }}][{{ $i }}]" id="delete_photo_{{ isset($post) ? $post->id : 'new' }}_{{ $i }}" value="0">

                    @error('photos.' . $i)
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>
        </div>
        @endfor
    </div>
</div>

<script src="{{ asset('js/edit-photo.js') }}"></script>
