@extends('backend.layouts.auth')

@section('css')
<!-- css link here -->
@stop

@section('content')

	<div class="form-group has-feedback">
            <div class="input-group">
                Password Updated. <a href="{{ route('backend.auth.login') }}">Click Here to go Login Page.</a>
            </div>
        </div>
        
@stop

@section('javascript')
<!-- js link here -->
@stop
