@extends('errors.error')

@section('code', '404')
@section('title', __('Page Not Found'))

@section('image')
    <div class="image image-404"></div>
@endsection

@section('message', __('Sorry, the page you are looking for could not be found.'))
