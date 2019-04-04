@extends('backend.layouts.auth')

@section('css')
<!-- css link here -->
@stop

@section('content')
<form action="{{ route('backend.auth.reset-password.post') }}" method="post">
    {{ csrf_field() }}
    {!! show_error($errors, 'token') !!}
    <input type="hidden" class="form-control" id="token" name="token" value="{{ $token }}" />
	<div class="form-group has-feedback">
        <label class="form-label form-required" for="password">New Password</label>
        <div class="input-group">
            <input type="password" class="form-control" placeholder="" id="password" name="password" value="" />
            <div class="input-group-append">
                <div class="input-group-text">
                    <i class="fas fa-key"></i>
                </div>
            </div>
        </div>
        {!! show_error($errors, 'password') !!}
	</div>
        <div class="form-group has-feedback">
        <label class="form-label form-required" for="password_confirmation">Confirm Password</label>
        <div class="input-group">
            <input type="password" class="form-control" placeholder="" id="password_confirmation" name="password_confirmation" value="" />
            <div class="input-group-append">
                <div class="input-group-text">
                    <i class="fas fa-key"></i>
                </div>
            </div>
        </div>
        {!! show_error($errors, 'password_confirmation') !!}
    </div>
    @if (config('site.captcha.enable'))
        <div class="form-group">
            <div class="g-recaptcha" data-sitekey="{!! config('site.captcha.site_key') !!}"></div>
            {!! show_error($errors, 'g-recaptcha-response') !!}
        </div>
    @endif
	<div class="form-group">
		<button type="submit" class="btn btn-primary btn-block btn-flat"><i class="fas fa-lock"></i> Update password</button>
	</div>
</form>
@stop

@section('javascript')
@stop
