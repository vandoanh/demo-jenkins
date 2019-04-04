@extends('backend.layouts.layout')

@section('content')
<form id="formPost" action="{!! route('backend.user.update.password') !!}" method="POST" novalidate id="frmUpdate" name="frmUpdate">
    {{ csrf_field() }}
    <div class="form-row justify-content-center">
        <div class="col-12 col-md-7 col-lg-7 col-xl-5">
            <div class="form-group">
                <label class="form-label form-required" for="old_password">Old Password</label>
                <input type="password" class="form-control" placeholder="Please enter your old password" name="old_password" id="old_password" />
                {!! show_error($errors, 'old_password') !!}
            </div>
            <div class="form-group">
                <label class="form-label form-required" for="new_password">New Password</label>
                <input type="password" class="form-control" placeholder="Please enter your new password" name="new_password" id="new_password" />
                {!! show_error($errors, 'new_password') !!}
            </div>
            <div class="form-group">
                <label class="form-label form-required" for="password_confirm">Password Confirm</label>
                <input type="password" class="form-control" placeholder="Please enter your confirm password" name="password_confirm" id="password_confirm" />
                {!! show_error($errors, 'password_confirm') !!}
            </div>
            <div class="form-row form-group mb0">
                <div class="col-6">
                    <a href="{!! route('backend.user.profile') !!}" role="button" class="btn btn-default"><i class="fas fa-angle-double-left"></i> Back</a>
                </div>
                <div class="col-6 text-right">
                    <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Save</button>
                </div>
            </div>
        </div>
    </div>
</form>
@stop

@section('javascript')
<!-- js link here -->
@stop
