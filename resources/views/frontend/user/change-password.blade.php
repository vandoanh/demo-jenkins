@extends('frontend.layouts.layout')

@section('content')
<section class="container">
    <div class="row">
        <div class="col-12 col-lg-4 col-xl-3">
            @include('frontend.user.partials.menu')
        </div>
        <div class="col-12 col-lg-8 col-xl-9">
            <form action="{!! route('user.update.password') !!}" method="post" id="frmUpdate" name="frmUpdate">
                {{ csrf_field() }}
                <div class="form-group row">
                    <div class="col">
                        <input type="password" class="form-control" placeholder="Mật khẩu cũ" name="old_password" id="old_password"/>
                        <span class="icon"><i class="fa fa-lock font-20"></i></span>
                        {!! show_error($errors, 'old_password') !!}
                    </div>
                </div>
                <div class="form-group row">
                    <div class="col">
                        <input type="password" class="form-control" placeholder="Mật khẩu mới" name="new_password" id="new_password" />
                        <span class="icon"><i class="fa fa-lock font-20"></i></span>
                        {!! show_error($errors, 'new_password') !!}
                    </div>
                </div>
                <div class="form-group row">
                    <div class="col">
                        <input type="password" class="form-control" placeholder="Nhập lại mật khẩu mới" name="password_confirm" id="password_confirm" />
                        <span class="icon"><i class="fa fa-lock font-20"></i></span>
                        {!! show_error($errors, 'password_confirm') !!}
                    </div>
                </div>
                <p class="text-center">
                    <button type="submit" name="change_profile" class="btn btn-danger"><i class="fas fa-save"></i> Thay đổi mật khẩu</button>
                </p>
            </form>
        </div>
    </div>
</section>
@stop

@section('javascript')
<!-- js link here -->
@stop
