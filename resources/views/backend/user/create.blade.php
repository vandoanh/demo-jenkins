@extends('backend.layouts.layout')

@section('content')
<form id="formPost" action="{!! route('backend.user.store') !!}" method="POST" novalidate>
    {{ csrf_field() }}
    <div class="form-row justify-content-center">
        <div class="col-12 col-md-7 col-lg-7 col-xl-5">
            <div class="form-row">
                <div class="col-12 col-sm-6 form-group">
                    <label for="email" class="form-label form-required">Email</label>
                    <input type="text" class="form-control" id="email" name="email" value="{{ old('email') }}" placeholder="Email">
                    {!! show_error($errors, 'email') !!}
                </div>
                <div class="col-12 col-sm-6 form-group">
                    <label for="fullname" class="form-label form-required">Fullname</label>
                    <input type="text" class="form-control" id="fullname" name="fullname" value="{{ old('fullname') }}" placeholder="Fullname">
                    {!! show_error($errors, 'fullname') !!}
                </div>
            </div>
            <div class="form-row">
                <div class="col-12 col-sm-4 form-group">
                    <label for="birthday" class="form-label">Birthday</label>
                    <div class="input-group date datepicker">
                        <input type="text" class="form-control" placeholder="Birthday" name="birthday" id="birthday" value="{{ old('birthday') }}"/>
                        <div class="input-group-append input-group-addon">
                            <div class="input-group-text">
                                <i class="fas fa-calendar"></i>
                            </div>
                        </div>
                    </div>
                    {!! show_error($errors, 'birthday') !!}
                </div>
                <div class="col-6 col-sm-4 form-group">
                    <label class="form-label" for="gender">Gender</label>
                    <select class="form-control custom-select" name="gender" id="gender">
                        @foreach (config('constants.user.gender') as $name => $value)
                            <option value="{{ $value }}" {!! old('gender', config('constants.user.gender.unknown')) == $value ? ' selected="selected"' : '' !!}>{{ ucfirst($name) }}</option>
                        @endforeach
                    </select>
                    {!! show_error($errors, 'gender') !!}
                </div>
                <div class="col-6 col-sm-4 form-group">
                    <label class="form-label" for="user_type">User type</label>
                    <select class="form-control custom-select" name="user_type" id="user_type">
                        @foreach (config('constants.user.type') as $name => $value)
                            <option value="{{ $value }}" {!! old('user_type', config('constants.user.type.member')) == $value ? ' selected="selected"' : '' !!}>{{ ucfirst($name) }}</option>
                        @endforeach
                    </select>
                    {!! show_error($errors, 'user_type') !!}
                </div>
            </div>
            <div class="form-row form-group mb0">
                <div class="col-6">
                    <a href="{!! route('backend.user.index') !!}" role="button" class="btn btn-default"><i class="fas fa-angle-double-left"></i> Back</a>
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
