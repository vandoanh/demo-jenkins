@extends('errors.error')

@section('code', '429')
@section('title', __('Too Many Requests'))

@section('image')
    <div class="image image-429"></div>
@endsection

@section('message', __('Sorry, you are making too many requests to our servers.'))
