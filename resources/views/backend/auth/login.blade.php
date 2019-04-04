@extends('backend.layouts.auth')

@section('css')
<!-- css link here -->
@stop 

@section('content')
<form action="{{ route('backend.auth.login.post') }}" method="post" id="frmLogin" name="frmLogin">
    {{ csrf_field() }}
    <div class="form-group">
        <label class="form-label form-required" for="email">Email</label>
        <div class="input-group">
            <input type="email" class="form-control" placeholder="Please enter your email" name="email" id="email" value="{{ old('email') }}"
            />
            <div class="input-group-append">
                <div class="input-group-text">
                    <i class="fas fa-envelope"></i>
                </div>
            </div>
        </div>
        {!! show_error($errors, 'email') !!}
    </div>
    <div class="form-group">
        <label class="form-label form-required" for="password">Password</label>
        <div class="input-group">
            <input type="password" class="form-control" placeholder="Please enter your password" name="password" id="password" value="{{ old('password') }}"
            />
            <div class="input-group-append">
                <div class="input-group-text">
                    <i class="fas fa-lock"></i>
                </div>
            </div>
        </div>
        {!! show_error($errors, 'password') !!}
    </div>
    <div class="form-group row">
        <div class="col-6 form-check mt-2">
            <input type="checkbox" id="remember" name="remember" value="1">
            <label class="form-check-label" for="remember">Remember</label>
        </div>
        <div class="col-6">
            <button type="submit" class="btn btn-primary btn-block btn-flat"><i class="fas fa-sign-in-alt"></i> Login</button>
        </div>
    </div>
    <div class="form-group text-center">
        <a href="{{ route('backend.auth.forgot-password') }}">Forgot password</a>
    </div>
</form>

@stop 
@section('javascript')
<!-- js link here -->
@stop