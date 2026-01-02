@extends('layouts.login_master')

@section('content')
<div class="page-content login-cover">
    <!-- Main content -->
    <div class="content-wrapper">
        <!-- Content area -->
        <div class="content d-flex justify-content-center align-items-center">
            <!-- Register card -->
            <form class="login-form" method="POST" action="{{ route('register') }}">
                @csrf
                <div class="card mb-0" style="min-width: 400px;">
                    <div class="card-body">
                        <div class="text-center mb-3">
                            <i class='bx bx-user-circle' style="font-size: 80px; color: #6c757d;"></i>
                        </div>

                        <div class="text-center mb-3">
                            <i class="icon-user-plus icon-2x text-primary border-primary border-3 rounded-round p-3 mb-3 mt-1"></i>
                            <h5 class="mb-0">Create your account</h5>
                            <span class="d-block text-muted">Register below</span>
                        </div>

                        @if ($errors->any())
                            <div class="alert alert-danger alert-styled-left alert-dismissible">
                                <button type="button" class="close" data-dismiss="alert"><span>&times;</span></button>
                                <span class="font-weight-semibold">Oops!</span> {!! implode('<br>', $errors->all()) !!}
                            </div>
                        @endif

                        <!-- Full Name -->
                        <div class="form-group">
                            <input type="text" name="name" class="form-control" placeholder="Full Name" value="{{ old('name') }}" required autofocus>
                        </div>

                        <!-- User Role -->
                        <div class="form-group">
                            <select name="user_type" class="form-control" required>
                                <option value="">-- Choose Role --</option>
                                <option value="admin" {{ old('user_type') == 'admin' ? 'selected' : '' }}>Admin</option>
                                <option value="teacher" {{ old('user_type') == 'teacher' ? 'selected' : '' }}>Teacher</option>
                                <option value="parent" {{ old('user_type') == 'parent' ? 'selected' : '' }}>Parent</option>
                            </select>
                        </div>

                        <!-- Username -->
                        <div class="form-group">
                            <input type="text" name="username" class="form-control" placeholder="Username (optional)" value="{{ old('username') }}">
                        </div>

                        <!-- Email -->
                        <div class="form-group">
                            <input type="email" name="email" class="form-control" placeholder="Email Address" value="{{ old('email') }}" required>
                        </div>

                        <!-- Password -->
                        <div class="form-group">
                            <input type="password" name="password" class="form-control" placeholder="Password" required>
                        </div>

                        <!-- Confirm Password -->
                        <div class="form-group">
                            <input type="password" name="password_confirmation" class="form-control" placeholder="Confirm Password" required>
                        </div>

                        <!-- Submit -->
                        <div class="form-group">
                            <button type="submit" class="btn btn-primary btn-block">Register <i class="icon-circle-right2 ml-2"></i></button>
                        </div>

                        <!-- Already have account? -->
                        <div class="text-center mb-3">
                            <span>Already have an account?</span>
                            <a href="{{ route('login') }}" class="btn btn-outline-primary btn-sm ml-2">
                                Login
                            </a>
                        </div>

                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
