@extends('backend.layouts.layout')

@section('content')
<form action="{!! route('backend.user.update', [$userInfo->id]) !!}" method="post" id="frmUpdate" name="frmUpdate">
    {{ csrf_field() }}
    <div class="form-row justify-content-center">
        <div class="col-12 col-md-7 col-lg-7 col-xl-5">
            <div class="form-row">
                <div class="col-12 col-sm-6 form-group">
                    <label class="form-label form-required" for="fullname">Fullname</label>
                    <input type="text" class="form-control" placeholder="Please enter your fullname" name="fullname" id="fullname" value="{{ old('fullname', $userInfo->fullname) }}" />
                    {!! show_error($errors, 'fullname') !!}
                </div>
                <div class="col-12 col-sm-6 form-group">
                    <label class="form-label" for="birthday">Birthday</label>
                    <div class="input-group date datepicker">
                        <input type="text" class="form-control" placeholder="Birthday" name="birthday" id="birthday" value="{{ old('birthday', format_date($userInfo->birthday, 'd/m/Y')) }}"/>
                        <div class="input-group-append input-group-addon">
                            <div class="input-group-text">
                                <i class="fas fa-calendar"></i>
                            </div>
                        </div>
                    </div>
                    {!! show_error($errors, 'birthday') !!}
                </div>
            </div>
        <div class="form-row">
            <div class="col-12 col-sm-4 form-group">
                <label class="form-label" for="gender">Gender</label>
                <select class="form-control custom-select" name="gender" id="gender">
                    @foreach (config('constants.user.gender') as $name => $value)
                        <option value="{{ $value }}" {!! old('gender', $userInfo->gender) == $value ? ' selected="selected"' : '' !!}>{{ ucfirst($name) }}</option>
                    @endforeach
                </select>
                {!! show_error($errors, 'gender') !!}
            </div>
            <div class="col-12 col-sm-4 form-group">
                <label class="form-label" for="user_type">User type</label>
                <select class="form-control custom-select" name="user_type" id="user_type">
                    @foreach (config('constants.user.type') as $name => $value)
                        <option value="{{ $value }}" {!! old('user_type', $userInfo->user_type) == $value ? ' selected="selected"' : '' !!}>{{ ucfirst($name) }}</option>
                    @endforeach
                </select>
                {!! show_error($errors, 'user_type') !!}
            </div>
            <div class="col-12 col-sm-4 form-group">
                <label class="form-label" for="status">Status</label>
                <select class="form-control custom-select" name="status" id="status">
                    @foreach (config('constants.user.status') as $name => $value)
                        <option value="{{ $value }}" {!! old('status', $userInfo->status) == $value ? ' selected="selected"' : '' !!}>{{ ucfirst($name) }}</option>
                    @endforeach
                </select>
                {!! show_error($errors, 'status') !!}
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
</form>
@stop

@section('javascript')
<!-- js link here -->
@stop
