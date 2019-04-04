@extends('frontend.layouts.auth')

@section('css')
<!-- css link here -->
@stop

@section('content')
<section class="container c-mt-15 c-center">
    <p><a href="{!! route('home') !!}"><img src="{!! url_static('images/logo-eas.png') !!}" alt="logo eas"></a></p>
    <div class="d-flex justify-content-center">
        <form action="{{ route('auth.reset-password.post', [$token]) }}" method="post">
            {{ csrf_field() }}
            {!! show_error($errors, 'token') !!}
            <div class="form-group row">
                <div class="col">
                    <input type="email" class="form-control" placeholder="Email" name="email" id="email" value="{{ old('email') }}" />
                    <span class="icon"><i class="fa fa-envelope font-16"></i></span>
                    {!! show_error($errors, 'email') !!}
                </div>
            </div>
            <div class="form-group row">
                <div class="col">
                    <input type="password" class="form-control" placeholder="Mật khẩu" name="password" id="password" />
                    <span class="icon"><i class="fas fa-key font-16"></i></span>
                    <small class="form-text text-muted">Mật khẩu phải từ 8 tới 20 ký tự, có ít nhất 1 ký tự hoa, 1 ký tự số và 1 ký tự đặc biệt.</small>
                    {!! show_error($errors, 'password') !!}
                </div>
            </div>
            <div class="form-group row">
                <div class="col">
                    <input type="password" class="form-control" placeholder="Nhập lại mật khẩu" name="confirm_password" id="confirm_password" />
                    <span class="icon"><i class="fas fa-key font-16"></i></span>
                    {!! show_error($errors, 'confirm_password') !!}
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
            <p><button type="submit" class="btn btn--big btn-outline-danger c-full text-upcase font-bold">Cập nhật mật khẩu</button></p>
        </form>
    </div>
</section>
@stop

@section('javascript')
<!-- js link here -->
@if (config('site.captcha.enable'))
    <script type="text/javascript" src="https://www.google.com/recaptcha/api.js"></script>
@endif
@stop
