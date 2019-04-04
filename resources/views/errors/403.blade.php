@extends('errors.error')

@section('code', '403')
@section('title', __('Unauthorized'))

@section('image')
    <div class="image image-403"></div>
@endsection

@section('message', __('Sorry, you are not authorized to access this page.'))
