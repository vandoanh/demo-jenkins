@extends('errors.error')

@section('code', '419')
@section('title', __('Page Expired'))

@section('image')
    <div class="image image-419"></div>
@endsection

@section('message', __('Sorry, your session has expired. Please refresh and try again.'))
