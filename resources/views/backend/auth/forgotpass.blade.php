@extends('backend.layouts.auth')

@section('css')
<!-- css link here -->
@stop

@section('content')
<form action="{{ route('backend.auth.forgot-password.post') }}" method="post">
    {{ csrf_field() }}
	<div class="form-group has-feedback">
        <label class="form-label form-required" for="email">Email</label>
        <div class="input-group">
            <input type="email" class="form-control" placeholder="Please enter your email" id="email" name="email" value="{{ old('email') }}" />
            <div class="input-group-append">
                <div class="input-group-text">
                    <i class="fas fa-envelope"></i>
                </div>
            </div>
        </div>
        {!! show_error($errors, 'email') !!}
	</div>
    @if (config('site.captcha.enable'))
        <div class="form-group">
            <div class="g-recaptcha" data-sitekey="{!! config('site.captcha.site_key') !!}"></div>
            {!! show_error($errors, 'g-recaptcha-response') !!}
        </div>
    @endif
	<div class="form-group">
		<button type="submit" class="btn btn-primary btn-block btn-flat"><i class="fas fa-unlock"></i> Reset password</button>
	</div>
</form>
@stop

@section('javascript')
<!-- js link here -->
@if (config('site.captcha.enable'))
    <script type="text/javascript" src="https://www.google.com/recaptcha/api.js"></script>
@endif
@stop
