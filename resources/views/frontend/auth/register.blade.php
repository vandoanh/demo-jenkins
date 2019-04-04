@extends('frontend.layouts.auth')

@section('css')
<!-- css link here -->
@stop

@section('content')
<section class="container c-mt-15 c-center">
    <div class="row">
        <div class="col-sm-5">
            <p><a href="{!! route('home') !!}"><img src="{!! url_static('images/logo-eas.png') !!}" alt="logo eas"></a></p>
            @if (config('site.general.social_login.enable'))
                @if (config('site.general.social_login.facebook.enable'))
                    <p class="mt-5"><a href="{!! route('auth.social.login', ['facebook']) !!}" class="btn blue c-w320"><i class="fab fa-facebook padd-icon font-30 mr-3"></i><span>Đăng ký với Facebook</span></a></p>
                @endif
                @if (config('site.general.social_login.google.enable'))
                    <p><a href="{!! route('auth.social.login', ['google']) !!}" class="btn red c-w320"><i class="fab fa-google-plus padd-icon font-30 mr-3"></i><span>Đăng ký với Google+<span></span></span></a></p>
                @endif
            @endif
        </div>
        <div class="col-sm-2">
            <img src="{!! url_static('images/line-or.png') !!}" alt="logo eas">
        </div>
        <div class="col-sm-5">
            <form action="{{ route('auth.register.post') }}" method="post" id="frmRegister" name="frmRegister">
                {{ csrf_field() }}
                <div class="form-group row">
                    <div class="col">
                        <input type="email" class="form-control" placeholder="Email" name="email" id="email" value="{{ old('email') }}" />
                        <span class="icon"><i class="fa fa-envelope font-16"></i></span>
                        {!! show_error($errors, 'email') !!}
                    </div>
                </div>
                <div class="form-group row">
                    <div class="col">
                        <input type="text" class="form-control" placeholder="Họ & tên" name="fullname" id="fullname" value="{{ old('fullname') }}" />
                        <span class="icon"><i class="fa fa-user font-20"></i></span>
                        {!! show_error($errors, 'fullname') !!}
                    </div>
                </div>
                <div class="form-group row">
                    <div class="col">
                        <input type="password" class="form-control" placeholder="Mật khẩu" name="password" id="password" />
                        <span class="icon"><i class="fa fa-lock font-20"></i></span>
                        {!! show_error($errors, 'password') !!}
                    </div>
                </div>
                <div class="form-group row">
                    <div class="col">
                        <input type="password" class="form-control" placeholder="Nhập lại mật khẩu" name="confirm_password" id="confirm_password" />
                        <span class="icon"><i class="fa fa-lock font-20"></i></span>
                        {!! show_error($errors, 'confirm_password') !!}
                    </div>
                </div>
                <div class="form-group row">
                    <div class="col-sm-7">
                        <input type="text" class="form-control datepicker" placeholder="Ngày sinh" name="birthday" id="birthday" value="{{ old('birthday') }}" />
                        <span class="icon"><i class="fa fa-birthday-cake font-16"></i></span>
                        {!! show_error($errors, 'birthday') !!}
                    </div>
                    <div class="col-sm-5">
                        <select class="classic" name="gender" id="gender">
                            @foreach (config('constants.user.gender') as $name => $value)
                                <option value="{{ $value }}"{!! old('gender', config('constants.user.gender.unknown')) == $value ? ' selected="selected"' : '' !!}>{{ ucfirst($name) }}</option>
                            @endforeach
                        </select>
                        {!! show_error($errors, 'gender') !!}
                    </div>
                </div>
                <p><button type="submit" class="btn btn--big btn-outline-danger c-full text-upcase font-bold">Đăng ký</button></p>
                <p>Bạn là thành viên. <a href="{!! route('auth.login') !!}"><span class="text-red text-upcase font-bold">Đăng nhập</span></a></p>
            </form>
        </div>
    </div>
</section>
@stop

@section('javascript')
<!-- js link here -->
<script type="text/javascript">
    $('document').ready(function() {
        common.showDatePicker();
    });
</script>
@stop
