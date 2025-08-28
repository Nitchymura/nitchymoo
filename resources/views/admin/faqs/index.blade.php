@extends('admin.menu')

@section('title', 'Admin: FAQs')

@section('sub_content')
    {{-- <form action="{{ route('admin.faqs.store') }}" method="post" class="row gx-2 mb-4">
        @csrf       
            <div class="col-4 mt-3">
                <input type="text" name="question" id="question" class="form-control " placeholder="Add a question..." value="{{old('question')}}">
                @error('question')
                    <p class="mb-0 text-danger small">{{$message}}</p>
                @enderror
            </div>     
            <div class="col-auto mt-3">
                <button type="submit" class="btn  btn-primary "><i class="fa-solid fa-plus"></i> Add</button>
            </div>   
        </form>
    <table class="table border bg-qhite table-hover align-middle text-secondary">
        <thead class="table-primary text-secondary text-uppercase small">
            <tr>
                <th></th>
                <th>question</th>
                <th>answer</th>
                <th>created at</th>
                <th></th>
            </tr>
        </thead>
        <tbody>
            @forelse($all_faqs as $faq)
                <tr>
                    <td>{{ $faq->id }}</td>
                    <td>{{ $faq->question }}</td>
                    
                    <td>{{ $faq->answer }}</td>
                    <td>
                        {{date('M d, Y H:m:s', strtotime($faq->created_at))}}
                    </td>
                    <td>
                        <!-- edit -->
                        <button class="btn btn-sm btn-outline-warning" data-bs-toggle="modal" data-bs-target="#edit-faq{{$faq->id}}">
                            <i class="fa-solid fa-pen-to-square"></i>
                        </button>
                        &nbsp;
                        <!-- delete -->
                        <button class="btn btn-sm btn-outline-danger" data-bs-toggle="modal" data-bs-target="#delete-faq{{$faq->id}}">
                            <i class="fa-solid fa-trash "></i>
                        </button>
                        @include('admin.faqs.actions')
                    </td>
                </tr>
            @empty
                <tr>
                    <td class="text-center" colspan="5">No faqs found.</td>
                </tr>
            @endforelse
        </tbody>
    </table> --}}
    {{-- {{ $all_faqs->links() }} --}}

@endsection