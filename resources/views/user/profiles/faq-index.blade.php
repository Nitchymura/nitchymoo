@extends('layouts.app')

@section('title', 'FAQ')

@section('content')
    
    <div class="row" style="height: 50px">
    </div> 
    <div class="row mt-5">        
        <h3 class="h3 text-success mb-4">Frequently Asked Questions about me</h3>
            <div class="row">
                @forelse($all_faqs as $faq)
                <div class="col-lg-4 col-md-6 col-sm-12 px-2">
                    <div class="card mb-4">
                        <div class="card-header">
                            <h5 class="mb-0">{{ $faq->question }}</h5>
                        </div>
                        {{-- body --}}
                        <div class="card-body">
                            @if($faq->answer)
                                {{ $faq->answer}}
                            @else
                                <span class="text-muted">I'll answer!</span>
                            @endif
                        </div>
                    </div>
                </div>
                @empty
                    <p class="text-center text-muted">Nothing found.</p>
                @endforelse
            </div>
            {{-- <div class="d-flex me-auto">
                {{ $all_posts->links() }}
            </div> --}}
    <div class="row mt-5"> 
        <h3 class="h4 text-primary mb-4">Do you want to ask me a question?</h3>
        <form action="{{ route('question.store') }}" method="post" class="row gx-2 mb-4">
        @csrf       
            <div class="col">
                <input type="text" name="question" id="question" class="form-control " placeholder="What do you want to know about me?" value="{{old('question')}}">
                @error('question')
                    <p class="mb-0 text-danger small">{{$message}}</p>
                @enderror
            </div>     
            <div class="col-auto">
                <button type="submit" class="btn  btn-primary "><i class="fa-solid fa-plus"></i> Ask</button>
            </div>   
        </form>
    </div>
                
    
@endsection