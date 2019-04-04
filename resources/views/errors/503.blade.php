@extends('errors.error')

@section('code', '503')
@section('title', __('Service Unavailable'))

@section('image')
    <div class="image image-503"></div>
@endsection

@section('message', __($exception->getMessage() ?: 'Sorry, we are doing some maintenance. Please check back soon.'))
