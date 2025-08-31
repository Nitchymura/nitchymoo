
<div class="modal fade" id="delete-faq{{$faq->id}}">
    <div class="modal-dialog">
        <div class="modal-content border-danger">
            <div class="modal-header border-danger">
                <h3 class="h3 text-dark"><i class="fa-solid fa-trash-can"></i> Delete FAQ</h3>
            </div>
            <div class="modal-body">
                <p class="text-dark">Are you sure you want to delete this FAQ?</p> 
                <p class="fw-bold">Q: "{{ $faq->question }}"</p>     
                <p class="fw-bold">A: "{{ $faq->answer }}"</p>          
            </div>
            <div class="modal-footer border-0">
                <form action="{{ route('admin.faqs.delete', $faq->id)}}" method="post">
                    @csrf
                    @method('DELETE')
                    <button type="button" data-bs-dismiss="modal" class="btn btn-sm btn-outline-danger">Cancel</button>
                    <button type="submit" class="btn btn-sm btn-danger">Delete</button>
                </form>
            </div>
        </div>
    </div>
</div>


<div class="modal fade" id="edit-faq{{$faq->id}}">
    <div class="modal-dialog">
        <div class="modal-content border-warning">
            <div class="modal-header border-warning">
                <h3 class="h3 text-dark"><i class="fa-solid fa-pen-to-square"></i> Edit FAQ</h3>
            </div>
            <form action="{{ route('admin.faqs.update', $faq->id) }}" method="post" class="edit-faq-form">
    @csrf
    @method('PATCH')

    <div class="modal-body">
        <label for="question-{{ $faq->id }}">Question</label>
        <input id="question-{{ $faq->id }}" type="text" name="question"
               class="form-control"
               value="{{ old('question', $faq->question) }}">
        @error('question')
            <p class="mb-0 text-danger small">{{ $message }}</p>
        @enderror

        <label for="answer-{{ $faq->id }}" class="mt-2">Answer</label>
        <textarea id="answer-{{ $faq->id }}" type="text" name="answer"
               class="form-control"
               value="{{ old('answer', $faq->answer) }}">{{ old('answer', $faq->answer) }}</textarea>
        @error('answer')
            <p class="mb-0 text-danger small">{{ $message }}</p>
        @enderror
    </div>

    <div class="modal-footer border-0">
        <button type="button" data-bs-dismiss="modal" class="btn btn-sm btn-outline-warning">Cancel</button>
        <button type="submit" class="btn btn-sm btn-warning">Update</button>
    </div>
</form>
         
        </div>
    </div>
</div>



