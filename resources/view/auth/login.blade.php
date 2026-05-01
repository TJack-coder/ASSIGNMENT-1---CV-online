@extends('layouts.app')

@section('content')
<div class="container mt-5">
    <div class="card mx-auto" style="max-width: 450px;">
        <div class="card-header bg-primary text-white">
            <h4>Login</h4>
        </div>
        <div class="card-body">
            <form method="POST" action="{{ route('login') }}">
                @csrf
                <div class="mb-3">
                    <label>Email</label>
                    <input type="email" name="email" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label>Password</label>
                    <input type="password" name="password" class="form-control" required>
                </div>
                <button type="submit" class="btn btn-primary w-100">Login</button>
            </form>
            <p class="text-center mt-3">
                Don't have account? <a href="{{ route('register') }}">Register as Job Seeker</a>
            </p>
        </div>
    </div>
</div>
@endsection