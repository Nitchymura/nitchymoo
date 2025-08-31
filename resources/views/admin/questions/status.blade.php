@if(!$faq->trashed())
{{-- DEACTIVATE --}}
<div class="modal fade" id="deactivate-faq{{ $faq->id }}">
    <div class="modal-dialog">
        <div class="modal-content border-danger">
            <div class="modal-header border-danger">
                <h3 class="h3 text-danger"><i class="fa-solid fa-eye-slash"></i>Hide FAQ</h3>
            </div>
            <div class="modal-body">
                <p>Are you sure you want to hide this FAQ ?</p>              
                {{-- <img src="{{$faq->image}}" alt="" class="img-lg mb-2"> --}}
                <p class="fw-bold">Q: "{{ $faq->question }}"</p>     
                <p class="fw-bold">A: "{{ $faq->answer }}"</p>     
            </div>
            <div class="modal-footer border-0">
                <form action="{{ route('admin.faqs.deactivate', $faq->id)}}" method="post">
                    @csrf
                    @method('DELETE')
                    <button type="button" data-bs-dismiss="modal" class="btn btn-sm btn-outline-danger">Cancel</button>
                    <button type="submit" class="btn btn-sm btn-danger">Hide</button>
                </form>
            </div>
        </div>
    </div>
</div>
@else
{{-- ACTIVATE --}}
<div class="modal fade" id="activate-faq{{$faq->id}}">
    <div class="modal-dialog">
        <div class="modal-content border-primary">
            <div class="modal-header border-primary">
                <h3 class="h3 text-primary"><i class="fa-solid fa-eye"></i> Unhide faq</h3>
            </div>
            <div class="modal-body">
                <p>Are you sure you want to unhide this faq?</p>
                <p class="fw-bold">Q: "{{ $faq->question }}"</p>     
                <p class="fw-bold">A: "{{ $faq->answer }}"</p>  
            </div>
            <div class="modal-footer border-0">
                <form action="{{route('admin.faqs.activate', $faq->id)}}" method="post">
                    @csrf
                    @method('PATCH')
                    <button type="button" data-bs-dismiss="modal" class="btn btn-sm btn-outline-primary">Cancel</button>
                    <button type="submit" class="btn btn-sm btn-primary">Unhide</button>
                </form>
            </div>
        </div>
    </div>
</div>

@endif