@php
    // create なら 'new'、edit なら $post->id
    $pid = isset($post) ? $post->id : 'new';
    $slots = 6; // ← スロット数
@endphp

<!-- まとめアップローダ（見えるのはコレだけ） -->
<div class="mb-4 rounded">
    <label class="form-label fw-bold mt-3">Other photos (up to {{ $slots }})</label>
    <input type="file" id="photos-uploader" class="form-control" accept="image/*" multiple>
    <div class="form-text">Select multiple images; they will fill empty slots automatically.</div>

    <div class="row mt-3">
        @for ($i = 1; $i <= $slots; $i++)
            @php
                // 編集時：既存の写真（priority一致）
                $existingPhoto = isset($post) && $post->postBodies
                    ? $post->postBodies->firstWhere('priority', $i)
                    : null;
            @endphp

            <div class="col-md-4 col-lg-2 mb-3">
                <div class="position-relative">
                    <div class="photo-preview mb-0" id="preview_{{ $pid }}_{{ $i }}">
                        {{-- 既存画像があれば表示 --}}
                        @if($existingPhoto && $existingPhoto->photo)
                            <img src="{{ $existingPhoto->photo }}" alt="Photo {{ $i }}" id="preview_img_{{ $pid }}_{{ $i }}" class="image-lg img-thumbnail mb-2 ">
                            <button type="button"
                                    class="btn btn-danger delete-photo-btn"
                                    data-photo-id="{{ $existingPhoto->id }}"
                                    data-preview-id="{{ $pid }}_{{ $i }}">
                                <i class="fa-solid fa-trash-can py-1 px-1"></i>
                            </button>
                        {{-- @else
                            <div class="mb-2 text-center" id="placeholder_{{ $pid }}_{{ $i }}">
                                <i class="fa-solid fa-image text-secondary icon-lg"></i>
                            </div> --}}
                        @endif

                        {{-- ★各スロットの本体inputは非表示：従来name/IDを維持（サーバ側はそのまま受け取れる） --}}
                        <input type="file"
                               id="photo_{{ $pid }}_{{ $i }}"
                               name="photos[{{ $pid }}][{{ $i }}]"
                               class="form-control photo-input d-none @error('photos.' . $i) is-invalid @enderror"
                               accept="image/*">
                        <input type="hidden" name="priorities[{{ $pid }}][{{ $i }}]" value="{{ $i }}">
                        <input type="hidden" name="existing_photos[{{ $pid }}][{{ $i }}]" value="{{ $existingPhoto ? $existingPhoto->id : '' }}">
                        <input type="hidden" name="delete_photos[{{ $pid }}][{{ $i }}]" id="delete_photo_{{ $pid }}_{{ $i }}" value="0">

                        @error('photos.' . $i)
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>
        @endfor
    </div>
</div>
