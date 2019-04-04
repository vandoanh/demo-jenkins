@extends('errors.error')

@section('code', '500')
@section('title', __('Error'))

@section('image')
    <div class="image image-500"></div>
@endsection

@section('message', __('Whoops, something went wrong on our servers.'))
