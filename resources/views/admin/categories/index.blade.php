@extends('admin.menu')

@section('title', 'Admin: Categories')

@section('sub_content')
    <form action="{{ route('admin.categories.store') }}" method="post" class="row gx-2 mb-4">
        @csrf       
            <div class="col-4 mt-3">
                <input type="text" name="name" id="name" class="form-control " placeholder="Add a category..." value="{{old('name')}}">
                @error('name')
                    <p class="mb-0 text-danger small">{{$message}}</p>
                @enderror
            </div>     
            <div class="col-auto mt-3">
                <button type="submit" class="btn  btn-primary "><i class="fa-solid fa-plus"></i> Add</button>
            </div>   
        </form>

    <table class="table border bg-qhite table-hover align-middle text-secondary">
        <thead class="table-warning text-secondary text-uppercase small">
            <tr>
                <th></th>
                <th>name</th>
                <th>count</th>
                <th>last updated</th>
                <th></th>
            </tr>
        </thead>
        <tbody>
            @forelse($all_categories as $category)
                <tr>
                    <td>{{ $category->id }}</td>
                    <td>
                        <a href="{{ route('category.show', $category->id) }}" class="text-decoration-none text-dark fw-bold" >{{ $category->name }}</a> 
                    </td>
                    <td>
                       {{ $category->categoryPosts->count() }}
                    </td>
                    <td>
                        {{date('M d, Y H:m:s', strtotime($category->updated_at))}}
                    </td>
                    <td>
                        {{-- edit --}}
                        <button class="btn btn-sm btn-outline-warning" data-bs-toggle="modal" data-bs-target="#edit-category{{$category->id}}">
                            <i class="fa-solid fa-pen-to-square"></i>
                        </button>
                        &nbsp;
                        {{-- delete --}}
                        <button class="btn btn-sm btn-outline-danger" data-bs-toggle="modal" data-bs-target="#delete-category{{$category->id}}">
                            <i class="fa-solid fa-trash "></i>
                        </button>
                        @include('admin.categories.actions')
                    </td>
                </tr>                
            @empty
                <tr>
                    <td class="text-center" colspan="6">No categories found.</td>
                </tr>
            @endforelse
                <tr>
                    <td>0</td>
                    <td>Uncategorized</td>
                    <td>
                        {{ $uncategorized_post }}
                    </td>
                    <td></td>
                    <td></td>
                </tr>
        </tbody>
    </table>
    {{ $all_categories->links() }}

@endsection