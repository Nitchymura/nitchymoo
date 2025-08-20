@extends('layouts.app')

@section('title', 'Edit Profile')

@section('content')
<div class="row" style="height: 40px"></div>
    <div class="row justify-content-center mt-5">
        <div class="col-md-8 col-sm-11">
            {{-- UPDATE PASSWORD --}}
            <form action="{{ route('profile.update-password') }}" method="post" class="shadow rounded-3 bg-white p-5">
                @csrf
                @method('PATCH')
                @if(session('success_password_change'))
                    <p class="mb-3 text-success fw-bold">{{ session('success_password_change') }}</p>
                @endif
                <h2 class="h4 text-secondary mb-3">Update Password</h2>

                <label for="old-password" class="form-label fw-bold">Old Password</label>
                <input type="password" name="old_password" id="old-password" class="form-control">
                @if(session('wrong_password_error'))
                <p class="mb-0 text-danger small">{{ session('wrong_password_error') }}</p>
                @endif

                <label for="new-password" class="form-label fw-bold mt-3">New Password</label>
                <input type="password" name="new_password" id="new-password" class="form-control">
                @if(session('new_password_error'))
                <p class="mb-0 text-danger small">{{ session('new_password_error') }}</p>
                @endif
                @error('new_password')
                    <p class="mb-0 text-danger small">{{ $message }}</p>
                @enderror

                <label for="confirm-password" class="form-label fw-bold mt-3">Confirm New Password</label>
                <input type="password" name="new_password_confirmation" id="confirm-password" class="form-control">

                <input type="submit" value="Update Password" class="btn btn-warning px-5 mt-3">
            </form>
        </div>
    </div>
<script src="{{ asset('js/edit-profile.js') }}"></script>
@endsection

