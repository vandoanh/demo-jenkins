@extends('frontend.layouts.auth')

@section('css')
<!-- css link here -->
@stop

@section('content')
<section class="container c-mt-15 c-center"><p>
    <a href="{!! route('home') !!}"><img src="{!! url_static('images/logo-eas.png') !!}" alt="logo eas"></a></p>
    <div class="d-flex justify-content-center">
        <form action="{{ route('auth.forgot-password.post') }}" method="post">
            {{ csrf_field() }}
            <div class="form-group row">
                <div class="col">
                    <input type="email" class="form-control" placeholder="Email" name="email" id="email" value="{{ old('email') }}" />
                    <span class="icon"><i class="fa fa-envelope font-16"></i></span>
                    {!! show_error($errors, 'email') !!}
                </div>
            </div>
            @if (config('site.captcha.enable'))
                <div class="form-group row">
                    <div class="col">
                        <div class="g-recaptcha" data-sitekey="{!! config('site.captcha.site_key') !!}"></div>
                        {!! show_error($errors, 'g-recaptcha-response') !!}
                    </div>
                </div>
            @endif
            <p><button type="submit" class="btn btn--big btn-outline-danger c-full text-upcase font-bold">Lấy lại mật khẩu</button></p>
        </form>
    </div>
</section>
@stop

@section('javascript')
<!-- js link here -->
@if (config('site.captcha.enable'))
    <script type="text/javascript" src="https://www.google.com/recaptcha/api.js?hl={{ config('app.locale') }}"></script>
@endif
@stop
